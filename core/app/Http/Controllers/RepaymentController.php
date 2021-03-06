<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Customer;
use App\InstalmentTime;
use App\Order;
use App\OrderInstalment;
use App\Providers\HelperProvider;
use App\Repayment;
use App\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use DB;

class RepaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function newDueRepayment()
    {
        $data['page_title'] = 'Due RePayment';
        $data['customer'] = Customer::all();
        return view('repayment.due-payment-new', $data);
    }

    public function getCustomerDue()
    {
        $customer_id = Input::get('customer_id');
        $order = Order::whereCustomer_id($customer_id)->whereStatus(2)->get();
        return Response::json($order);
    }

    public function getOrderDetails($id)
    {
        $order = Order::findOrFail($id);
        return Response::json($order);
    }

    public function submitDueRepayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'pay_amount' => 'required|numeric',
            'repay_due_discount' => 'required|numeric',
            'payment_status' => 'required'
        ]);
        $order = Order::findOrFail($request->order_id);
        $order->pay_amount += $request->pay_amount;

        $custom = 'DU-' . date('ymdHis');

        $re['custom'] = $custom;
        $re['ref_custom'] = $order->custom;
        $re['type'] = 1;// Due Repayment
        $re['payment_date'] = $request->payment_date;
        $re['customer_id'] = $order->customer_id;
        $re['order_id'] = $order->id;
        $re['repay_due_discount'] = $request->repay_due_discount;
        $re['pay_amount'] = $request->pay_amount;
        $re['payment_status'] = $request->payment_status;

        $post_due = ($order->due_amount - $request->repay_due_discount)-$request->pay_amount;

        if ($request->payment_status == 0) {
            $order->repay_due_discount += $request->repay_due_discount;
            $order->due_amount -= $request->repay_due_discount + $request->pay_amount;
            $order->due_payment_date = $request->due_payment_date;
            $re['post_due'] = $post_due;
        } else {
            $order->due_amount = 0;
            $order->status = 1;
            $re['post_due'] = 0;
        }

        Repayment::create($re);

        $basic = BasicSetting::first();

        $tr['custom'] = $custom;
        $tr['type'] = 11; // Due Re Payment
        $tr['balance'] = $request->pay_amount;
        $tr['status'] = 0;// Davit
        $tr['post_balance'] = $basic->balance + $request->pay_amount;

        TransactionLog::create($tr);

        $basic->balance += $request->pay_amount;

        $basic->save();

        $order->save();
        $data = array(
            'customer_name' => $order->customer->name,
            'customer_phone' => $order->customer->phone,
            'custom' => $order->custom,
            'total' => $request->total_due,
            'repay_due_discount' => $request->repay_due_discount,
            'paid' => $request->pay_amount,
            'due'  => $request->due_amount,
            'bill_custom' => $custom
        );

        $msg ="?????????????????? ??????????????????,".$data['customer_name']." ???????????? ?????? ".$data['custom']."
?????????????????? ".$data['total']." ????????????,  ??????????????????????????? ".$data['repay_due_discount']." ????????????, ????????? ?????? ".$data['bill_custom']." ?????????????????? ".$data['paid']." ????????????, ???????????? " .$data['due']." ????????????
?????????????????????: 01967676551
New Aman Electronics,";
        smsGateWay($msg,$data['customer_phone']);

        session()->flash('message', 'Repayment Successfully Completed');
        session()->flash('type', 'success');
        return redirect()->route('due-repayment-receipt-view', $custom);
    }

    public function dueRepaymentReceiptView($custom)
    {
        $data['page_title'] = 'Due Repayment Receipt';
        $data['sell'] = Repayment::whereCustom($custom)->firstOrFail();
       // dd($data);
        return view('repayment.due-repayment-receipt-view', $data);
    }

    public function dueRepaymentReceiptPrint($custom)
    {
        $data['page_title'] = 'Due Repayment Print';
        $data['sell'] = Repayment::whereCustom($custom)->firstOrFail();
        return view('repayment.due-repayment-receipt-print', $data);
    }

    public function historyDueRepayment()
    {
        $data['page_title'] = 'Due Repayment History';
        $data['history'] = Repayment::whereType(1)->latest()->get();
        return view('repayment.due-repayment-history', $data);
    }




    // due report
    public function DueReport()
    {
        $page_title = 'Due Report';
        $orders = DB::table('orders')
            ->leftjoin('customers','customer_id','=','customers.id')
            ->leftJoin('order_instalments','order_id','=','orders.id')
            ->select('orders.*','customers.name as customer_name','customers.phone as customer_phone','order_instalments.id as order_installment_id','order_instalments.total_amount as total')
            ->where('orders.deleted_at',null)
            ->get();
        //dd($orders);

        $ondue  = Order::wherepayment_type(1)->latest()->get();
        return view('repayment.due-report',compact('orders','ondue','page_title'));
    }




    public function deleteDueRepayment(Request $request)
    {
        $request->validate([
            'delete_id' => 'required'
        ]);

        $repayment = Repayment::findOrFail($request->delete_id);

        $order = Order::findOrFail($repayment->order_id);

        $order->pay_amount -= $repayment->pay_amount;
        $order->due_amount += $repayment->pay_amount;
        $order->status = 2;

        $order->save();

        $basic = BasicSetting::first();

        $tr['custom'] = $repayment->custom;
        $tr['type'] = 12; // Return Due Re Payment
        $tr['balance'] = $repayment->pay_amount;
        $tr['status'] = 1;// Cadet
        $tr['post_balance'] = $basic->balance - $repayment->pay_amount;
        TransactionLog::create($tr);
        $basic->balance -= $repayment->pay_amount;
        $basic->save();
        $repayment->delete();

        session()->flash('message', 'Repayment Deleted Successfully.');
        session()->flash('type', 'success');
        return redirect()->back();

    }

    public function upcomingDueRepayment()
    {
        $data['page_title'] = 'Upcoming Due Repayment';
        $tomorrow = Carbon::tomorrow();
        $today = Carbon::now()->format('Y-m-d');
        $next = Carbon::parse()->addDays('7')->format('Y-m-d');

        $date[0] = $today;
        $date[1] = $next;
        $data['date'] = $date;

        $data['history'] = Order::whereDate('due_payment_date', '<=', $tomorrow)->whereStatus(2)->get();
//        dd($data['history']);
//        exit;
        return view('repayment.due-upcoming-repayment', $data);
    }

    public function repaymentSearch()
    {
        $date = Input::get('repayment_date');
        $start = explode(' / ', $date);
        $data['page_title'] = 'Upcoming Due Repayment Search';
        $data['date'] = $start;
        $data['history'] = Order::whereStatus(2)->whereBetween('due_payment_date', [$start[0], $start[1]])->get();
        return view('repayment.due-upcoming-repayment', $data);
    }

    public function instalmentRepayment()
    {
        $data['page_title'] = 'Instalment Repayment';
        $data['customer'] = Customer::all();
        return view('repayment.instalment-repayment', $data);
    }

    public function CheckCustomerInstalment()
    {
        $customer_id = Input::get('customer_id');
        $instalment = OrderInstalment::whereCustomer_id($customer_id)->whereStatus(0)->get();
        return Response::json($instalment);
    }

    public function instalmentRepaymentList()
    {
        $custom = Input::get('custom');
        $data['page_title'] = $custom . ' - Repayment List';
        $order = OrderInstalment::whereCustom($custom)->firstOrFail();
        $data['list'] = InstalmentTime::whereOrder_instalment_id($order->id)->get();
     // dd($data);


        return view('repayment.instalment-repayment-list', $data);


    }

    public function getInstalmentDetails($id)
    {
        $res = InstalmentTime::findOrFail($id);
        $res['custom'] = $res->instalment->custom;
        return Response::json($res);
    }

    public function submitInstalmentRepayment(Request $request)
    {

//       $data =  $request->all();
//       dd($data);
        $instalment = InstalmentTime::findOrFail($request->instalmentTime_id);
        $payable = $instalment->amount - $request->ins_discount;
        if($payable <= $request->pay_amount){
            $amount = 0;
        }else{
            $amount =  $payable-$request->pay_amount;
        }


        $custom = 'IN-' . date('ymdHis');

        $re['custom'] = $custom;
        $re['ref_custom'] = $instalment->instalment->custom;
        $re['type'] = 2;// Instalment Repayment
        $re['payment_date'] = $request->payment_date;
        $re['customer_id'] = $instalment->instalment->customer_id;
        $re['order_id'] = $instalment->instalment->order_id;
        $re['ins_discount'] = $request->ins_discount;
        $re['pay_amount'] = $request->pay_amount;
        $re['post_due'] = $instalment->amount;
        $re['time_id'] = $request->instalmentTime_id;
        $re['amount'] = $request->instalmentTime_id;

        //dd($re);
        Repayment::create($re);

        $instalment->pay_amount = $request->pay_amount;
        $instalment->updated_at = $request->payment_date;


        $instalment->ins_discount = $request->ins_discount;
        $instalment->status = 1;
        $instalment->custom = $custom;

        $net_amount = $instalment->amount - $instalment->ins_discount;
        $extra_amount =$request->pay_amount - $net_amount ;

        $checkLast = InstalmentTime::whereOrder_instalment_id($request->orderInstalment_id)->whereStatus(0)->count();

        if ($checkLast == 1) {

            if (($request->total_instalment - $request->pay_amount) > 0) {

                $lastInstal = InstalmentTime::whereOrder_instalment_id($request->orderInstalment_id)->whereStatus(0)->first();

                $mainInstalment = OrderInstalment::findOrFail($request->orderInstalment_id);
                $newIn['order_instalment_id'] = $mainInstalment->id;
                $newIn['amount'] = ($request->total_instalment -$request->ins_discount) - $request->pay_amount;
                $newIn['pay_date'] = Carbon::parse($lastInstal->pay_date)->addDays($mainInstalment->instalment->difference);
               // dd($newIn);

                InstalmentTime::create($newIn);

            } else {
                $mainInstalment = OrderInstalment::findOrFail($request->orderInstalment_id);
                $order = Order::findOrFail($mainInstalment->order_id);
                $order->status = 1;
                $order->save();
                $mainInstalment->status = 1;
                $mainInstalment->save();
            }


        } else {
            $nextInstalment = InstalmentTime::whereNotIn('id', [$request->instalmentTime_id])->whereOrder_instalment_id($request->orderInstalment_id)->orderBy('id', 'DESC')->whereStatus(0)->get();
            if($nextInstalment!=null){
                foreach ($nextInstalment as $key=>$ni){
                    if($nextInstalment[$key]->amount <= $extra_amount ){
                        $extra_amount = $extra_amount - $nextInstalment[$key]->amount;
                        $nextInstalment[$key]->amount = $nextInstalment[$key]->amount;
                        $nextInstalment[$key]->custom = $custom;
                        $nextInstalment[$key]->status = 0;
                        $nextInstalment[$key]->save();
                        $mainInstalment = OrderInstalment::findOrFail($request->orderInstalment_id);
                        $order = Order::findOrFail($mainInstalment->order_id);
                        $order->status = 1;
                        $order->save();
//                        $mainInstalment->status = 1;
//                        $mainInstalment->save();
//                        $extra_amount = $extra_amount - $nextInstalment[$key]->amount;
                    }else {
//                        if ($extra_amount > 0){
//                            $nextInstalment[$key]->amount -= $extra_amount;
//                            $nextInstalment[$key]->status = 0;
//                           $nextInstalment[$key]->save();
//                    }
                        break;
                    }
                }

            }

//            $nextInstalment->amount += $extra_amount;
//            $nextInstalment->save();
        }

        if($instalment->amount == 0){
            $instalment->status = 1;
        }
        $instalment->save();

        $basic = BasicSetting::first();

        $tr['custom'] = $custom;
        $tr['type'] = 13; // Instalment Payment
        $tr['balance'] = $request->pay_amount;
        $tr['status'] = 0;// Davit
        $tr['post_balance'] = $basic->balance + $request->pay_amount;


        TransactionLog::create($tr);

        $basic->balance += $request->pay_amount;

        $basic->save();
        $orderInstalment = OrderInstalment::where('order_id',$re['order_id'])
            ->select('custom','total_amount','grander_one_phone','grander_two_phone','grander_one_name','grander_two_name')
            ->first();

        $total__due_amount = $orderInstalment->total_amount;
        $sl_custom = $orderInstalment->custom;
        $grander_one_phone = $orderInstalment->grander_one_phone;
        $grander_two_phone = $orderInstalment->grander_two_phone;
        $grander_one_name = $orderInstalment->grander_one_name;
        $grander_two_name = $orderInstalment->grander_two_name;
        $message = 'Instalment Payment succesfully done';

        //grander sms
        $installmentData = getInstallmentDetailsById($request->orderInstalment_id);
        $instalmentinfo = DB::table('instalment_times')->where('order_instalment_id',$request->orderInstalment_id)->where('status',1)->select(DB::Raw('pay_amount as paid_amount'),DB::Raw('ins_discount as discount'))->orderBy('id','DESC')->first();
        //dd($instalmentinfo);
        $smspaid = $instalmentinfo->paid_amount;

        $customer = getCustomerById($instalment->instalment->customer_id);
        $order = getOrderById($instalment->instalment->order_id);
        $total_amount= $order->total_amount;
        $pay_amount= $order->pay_amount;
        $amnt =$order->total_amount-$order->pay_amount;
        $instalment_charge =$order->due_amount - $amnt;
        $net_total = $total_amount + $instalment_charge;
        $data = array(
            'total' =>$net_total,
            'due'   => $installmentData['due'],
            'paid'   => $smspaid,
            //'paid'  => $installmentData['paid'],
            'totalpaid'  => $installmentData['totalpaid'] + $pay_amount,
            'total_discount'  => $installmentData['total_discount'],
            'sl_custom' =>$sl_custom,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_phone2' => $customer->phone2,
            'grander_one'  => $grander_one_phone,
            'grander_two'  => $grander_two_phone,
            'grander_one_name'  => $grander_one_name,
            'grander_two_name'  => $grander_two_name,
            'custom' => $custom
        );


        $client_msg = "?????????????????? ??????????????????, ".$data['customer_name']." ????????? ??????- ".$data['sl_custom']." ????????????????????? ????????? ".$data['total']." ????????????, ????????????????????? ?????? ".$data['custom']." ?????????????????? ".$data['paid']." ????????????, ??????????????????????????? ".$data['total_discount']." ????????????, ????????????????????? ?????????????????? ".$data['totalpaid']." ????????????, ????????????????????? ???????????? " .$data['due']." ????????????,
?????????????????????: 01967676551
?????????????????? ????????????????????? ???????????????????????? ???????????? ???????????? ?????????????????????";
        $grander_one_msg = "?????????????????? ?????????????????????????????????, ".$data['grander_one_name']." ????????? ??????- ".$data['sl_custom']." ????????????????????? ????????? ".$data['total']." ????????????, ????????????????????? ?????? ".$data['custom']." ?????????????????? ".$data['paid']." ????????????, ??????????????????????????? ".$data['total_discount']." ????????????, ????????????????????? ?????????????????? ".$data['totalpaid']." ????????????, ????????????????????? ???????????? " .$data['due']." ????????????,
?????????????????????: 01967676551
?????????????????? ????????????????????? ???????????????????????? ???????????? ???????????? ?????????????????????";

        $grander_two_msg = "?????????????????? ?????????????????????????????????, ".$data['grander_two_name']." ????????? ??????- ".$data['sl_custom']." ????????????????????? ????????? ".$data['total']." ????????????, ????????????????????? ?????? ".$data['custom']." ?????????????????? ".$data['paid']." ????????????, ??????????????????????????? ".$data['total_discount']." ????????????, ????????????????????? ?????????????????? ".$data['totalpaid']." ????????????, ????????????????????? ???????????? " .$data['due']." ????????????,
?????????????????????: 01967676551
?????????????????? ????????????????????? ???????????????????????? ???????????? ???????????? ?????????????????????";

        smsGateWay($client_msg,$data['customer_phone']);
        smsGateWay($grander_one_msg,$data['grander_one']);
        smsGateWay($grander_two_msg,$data['grander_two']);

        session()->flash('message', ' Repayment Successfully Completed');
        session()->flash('type', 'success');
        return redirect()->route('instalment-repayment-invoice', $custom);


    }

    public function invoiceInstalmentRepayment($invoice)
    {
        $data['page_title'] = 'Instalment Repayment Receipt';
        $data['sell'] = InstalmentTime::whereCustom($invoice)->firstOrFail();
       // dd($data);
        return view('repayment.instalment-repayment-receipt-view', $data);
    }

    public function printInstalmentRepayment($invoice)
    {
        $data['page_title'] = 'Instalment Repayment Receipt';
        $data['sell'] = InstalmentTime::whereCustom($invoice)->firstOrFail();
       // dd($data['sell']);
        return view('repayment.instalment-repayment-receipt-print', $data);
    }

    public function instalmentRepaymentHistory()
    {
        $page_title = 'Instalment Repayment History';
        $history1  = Repayment::with('customer')->whereType(2)->latest()->get()->toArray();
       // dd($history1);
        return view('repayment.instalment-repayment-history', compact('page_title','history1'));
    }

    public function deleteInstalmentRepayment(Request $request)
    {
        $request->validate([
            'delete_id' => 'required'
        ]);

        $repayment = Repayment::findOrFail($request->delete_id);

        $time = InstalmentTime::findOrFail($repayment->time_id);

        $orderInstalment = OrderInstalment::findOrFail($time->order_instalment_id);
        $orderInstalment->status = 0;
        $orderInstalment->save();

        $order = Order::findOrFail($orderInstalment->order_id);
        $order->status = 3;
        $order->save();

        $extra_amount = $time->amount - $time->pay_amount;

        $time->pay_amount = 0;
        $time->status = 0;
        $time->save();


        $checkLast = InstalmentTime::whereOrder_instalment_id($time->order_instalment_id)->count();


        if ($checkLast > 1) {
            $nextInstalment = InstalmentTime::whereNotIn('id', [$time->id])->whereOrder_instalment_id($time->order_instalment_id)->first();
            $nextInstalment->amount -= $extra_amount;
            $nextInstalment->save();
        }


        $basic = BasicSetting::first();

        $tr['custom'] = $repayment->custom;
        $tr['type'] = 14; // Instalment Re Payment
        $tr['balance'] = $repayment->pay_amount;
        $tr['status'] = 1;// Credit
        $tr['post_balance'] = $basic->balance - $repayment->pay_amount;


        TransactionLog::create($tr);

        $basic->balance -= $repayment->pay_amount;

        $basic->save();

        $repayment->delete();

        session()->flash('message', 'Repayment Successfully Deleted');
        session()->flash('type', 'success');
        return redirect()->back();

    }

    public function upcomingInstalmentRepayment()
    {
        $data['page_title'] = 'Upcoming Instalment Repayment';
        $tomorrow = Carbon::tomorrow();
        $today = Carbon::now()->format('Y-m-d') . ' 00:00:00';
        $today1 = Carbon::now()->format('Y-m-d');
        $next = Carbon::parse()->addMonth()->format('Y-m-d') . ' 23:59:59';
        $next1 = Carbon::parse()->addMonth()->format('Y-m-d');
        $date[0] = $today1;
        $date[1] = $next1;
        $data['date'] = $date;
        $data['history'] = InstalmentTime::whereDate('pay_date','<=',$tomorrow)->whereStatus(0)->get();
        return view('repayment.instalment-upcoming-repayment', $data);
    }

    public function searchInstalmentRepayment()
    {
        $date = Input::get('repayment_date');
        $start = explode(' / ', $date);
        $data['page_title'] = 'Upcoming Instalment Repayment Search';
        $data['date'] = $start;
        $start1 = $start[0] . ' 00:00:00';
        $end1 = $start[1] . ' 23:59:59';
        $data['history'] = InstalmentTime::whereStatus(0)->whereBetween('pay_date', [$start1, $end1])->get();
        return view('repayment.instalment-upcoming-repayment', $data);
    }


    /**
     * Get SIngle Customer By Customer Id
     * @param $id
     */
    private function getSingleCustomerPhone($id)
    {
        //$customer = Customer::findOrFail(id);
        $customer = DB::table('customers')->where('id',$id)->first();
        return $customer->phone;
    }




}

//select sum(amount) as total_due, custom, order_instalment_id from instalment_times where status=0 group by order_instalment_id
