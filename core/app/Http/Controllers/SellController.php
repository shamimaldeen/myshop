<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Code;
use App\Customer;
use App\Instalment;
use App\InstalmentTime;
use App\Order;
use App\OrderInstalment;
use App\OrderItem;
use App\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Image;

class SellController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function newSell()
    {

        $data['page_title'] = 'New Sell';
        $data['customer'] = Customer::latest()->get();
        $data['instalment'] = Instalment::all();
        return view('sell.sell-new', $data);
    }

    public function checkInstalmentPercent($instalment_id, $total)
    {
        if ($total == 0 or $total == null) {
            $rr['errorStatus'] = 'yes';
            $rr['errorDetails'] = 'Your Subtotal Amount is Empty.';
        } else {
            $instalment = Instalment::findOrFail($instalment_id);
            $rr['total'] = $total + round(($total * $instalment->charge) / 100, 2);
            $rr['errorStatus'] = 'no';
            $rr['errorDetails'] = 'Instalment Charge is Added.';
        }
        return $result = json_encode($rr);
    }

    public function submitSell(Request $request)
    {
       //dd($request->all());
         $basic = BasicSetting::first();
        $custom = 'SL-' . date('ymdHis');
        $date = $request->created_at;

        $codes = [];
        foreach ($request->code as $cod) {
            $checkCode = Code::whereCode($cod)->count();
            if ($checkCode != 0) {
                if (!in_array($cod, $codes)) {
                    $codes[] = $cod;
                }
            }
        }

        if (count($codes) != 0) {       //if start

            if ($request->customer_type == 0) {         //new customer
                $request->validate([
                    'name' => 'required',
                    'email' => 'nullable|email|unique:customers',
                    'phone' => 'required|numeric|unique:customers',
                    'phone2' => 'nullable|numeric|unique:customers',
                    'address' => 'required'
                ]);
                $in = Input::except('_method', '_token');

                $customer = Customer::create($in);
                $upCustomer = Customer::findOrFail($customer->id);

                if ($request->product_price != null) {

                    if ($request->payment_type == 0) {//on paid

                        $orderT = Order::create();
                        $order = Order::find($orderT->id);

                        $product_price = 0;

                        foreach ($codes as $c) {
                            $pp = Code::whereCode($c)->firstOrFail();
                            $orderItem['order_id'] = $order->id;
                            $orderItem['custom'] = $custom;
                            $orderItem['created_at'] = $date;
                            $orderItem['product_id'] = $pp->product_id;
                            $orderItem['store_id'] = $pp->store_id;
                            $orderItem['code'] = $c;
                            $product_price += $pp->store->sell_price;
                            OrderItem::create($orderItem);
                            $pp->status = 1;
                            $pp->save();
                        }

                        $order->custom = $custom;
                        $order->created_at = $date;
                        $order->customer_id = $customer->id;
                        $order->product_price = $product_price;

                        $order->discount_type = $request->discount_type;
                        $order->discount = $request->discount;
                        if ($request->discount_type == 0) {
                            $order->product_total = $product_price;
                        } elseif ($request->discount_type == 1) {
                            $order->product_total = $product_price - round(($product_price * $request->discount) / 100, 2);
                        } else {
                            $order->product_total = $product_price - $request->discount;
                        }

                        $order->payment_type = $request->payment_type;
                        $order->total_amount = $request->on_total_price;
                        $order->pay_amount = $request->on_total_price;
                        $order->due_amount = 0;
                        $order->status = 1; // complete
                        $order->save();

                        $upCustomer->total_amount += $request->on_total_price;
                        $upCustomer->pay_amount += $request->on_total_price;
                        $upCustomer->save();

                        $tr['custom'] = $custom;
                        $tr['created_at'] = $date;
                        $tr['type'] = 9; // Sell Product
                        $tr['balance'] = $request->on_total_price;
                        $tr['status'] = 0;// David
                        $tr['post_balance'] = $basic->balance + $request->on_total_price;
                        TransactionLog::create($tr);

                        $basic->balance += $request->on_total_price;
                        $basic->save();


                    } elseif ($request->payment_type == 1)     //on due paid
                    {



                        $orderT = Order::create();
                        $order = Order::find($orderT->id);

                        $product_price = 0;
                        foreach ($codes as $c) {
                            $pp = Code::whereCode($c)->firstOrFail();
                            $orderItem['order_id'] = $order->id;
                            $orderItem['custom'] = $custom;
                            $orderItem['created_at'] = $date;
                            $orderItem['product_id'] = $pp->product_id;
                            $orderItem['store_id'] = $pp->store_id;
                            $orderItem['code'] = $c;
                            $product_price += $pp->store->sell_price;
                            OrderItem::create($orderItem);
                            $pp->status = 1;
                            $pp->save();
                        }
                        $order->custom = $custom;
                        $order->created_at = $date;
                        $order->customer_id = $customer->id;
                        $order->product_price = $product_price;

                        $order->discount_type = $request->discount_type;
                        $order->discount = $request->discount;
                        if ($request->discount_type == 0) {
                            $order->product_total = $product_price;
                        } elseif ($request->discount_type == 1) {
                            $order->product_total = $product_price - round(($product_price * $request->discount) / 100, 2);
                        } else {
                            $order->product_total = $product_price - $request->discount;
                        }

                        $order->payment_type = $request->payment_type;
                        $order->total_amount = $request->due_total_price;
                        $order->pay_amount = $request->due_pay_amount;
                        $order->due_amount = $request->due_total_price - $request->due_pay_amount;//$grandDueAmount;
                        $order->due_payment_date = $request->due_payment_date;
                        $order->status = 2; // Have Due Payment
                        $order->save();

                        $customer->total_amount += $request->due_total_price;
                        $customer->pay_amount += $request->due_pay_amount;
                        $customer->save();

                        $tr['custom'] = $custom;
                        $tr['created_at'] = $date;
                        $tr['type'] = 9; // Sell Product
                        $tr['balance'] = $request->due_pay_amount;
                        $tr['status'] = 0;// David
                        $tr['post_balance'] = $basic->balance + $request->due_pay_amount;
                        TransactionLog::create($tr);

                        $basic->balance += $request->due_pay_amount;
                        $basic->save();

                    } elseif ($request->payment_type == 2)      //Installment payment
                    {

                        if ($request->instalment_id == null) {
                            session()->flash('message', 'Select A Instalment Type');
                            session()->flash('type', 'warning');
                            return redirect()->back();
                        } else {
                            $instalment = Instalment::findOrFail($request->instalment_id);
                            $due_amount = ($request->ins_total_amount - $request->ins_pay_amount); //($request->due_total_price - $request->due_pay_amount);
                            $due_charge = ($instalment->charge / 100) * $due_amount;
                            $grandDueAmount = round($due_charge + $due_amount);

                            $orderT = Order::create();
                            $order = Order::find($orderT->id);

                            $product_price = 0;
                            foreach ($codes as $c) {
                                $pp = Code::whereCode($c)->firstOrFail();
                                $orderItem['order_id'] = $order->id;
                                $orderItem['custom'] = $custom;
                                $orderItem['created_at'] = $date;
                                $orderItem['product_id'] = $pp->product_id;
                                $orderItem['store_id'] = $pp->store_id;
                                $orderItem['code'] = $c;
                                $product_price += $pp->store->sell_price;
                                OrderItem::create($orderItem);
                                $pp->status = 1;
                                $pp->save();
                            }

                            $order->custom = $custom;
                            $order->created_at = $date;
                            $order->customer_id = $customer->id;
                            $order->product_price = $product_price;

                            $order->discount_type = $request->discount_type;
                            $order->discount = $request->discount;
                            if ($request->discount_type == 0) {
                                $order->product_total = $product_price;
                            } elseif ($request->discount_type == 1) {
                                $order->product_total = $product_price - round(($product_price * $request->discount) / 100, 2);
                            } else {
                                $order->product_total = $product_price - $request->discount;
                            }

                            $order->payment_type = $request->payment_type;
                            $order->instalment_id = $request->instalment_id;
                            $order->total_amount = $request->ins_total_amount;
                            $order->pay_amount = $request->ins_pay_amount;//$request->due_pay_amount;
                            $order->due_amount = $grandDueAmount;
                            $order->status = 3; // Instalmentt

                            $customer->total_amount += $request->ins_total_amount;
                            $customer->pay_amount += $request->ins_pay_amount;
                            $customer->save();

                            $orIns['custom'] = $custom;
                            $orIns['order_id'] = $order->id;
                            $orIns['created_at'] = $date;
                            $orIns['customer_id'] = $customer->id;
                            $orIns['instalment_id'] = $request->instalment_id;
                            $orIns['total_amount'] = $order->due_amount;

                            $orIns['customer_nid'] = $request->customer_nid;
                            $orIns['customer_father'] = $request->customer_father;
                            $orIns['grander_one_name'] = $request->grander_one_name;
                            $orIns['grander_one_father'] = $request->grander_one_father;
                            $orIns['grander_one_phone'] = $request->grander_one_phone;
                            $orIns['grander_one_address'] = $request->grander_one_address;
                            $orIns['grander_two_name'] = $request->grander_two_name;
                            $orIns['grander_two_father'] = $request->grander_two_father;
                            $orIns['grander_two_phone'] = $request->grander_two_phone;
                            $orIns['grander_two_address'] = $request->grander_two_address;


                            if ($request->hasFile('customer_image')) {
                                $image = $request->file('customer_image');
                                $filename = 'customer' . time() . '.' . $image->getClientOriginalExtension();
                                $location = 'assets/images/customer/' . $filename;
                                Image::make($image)->save($location);

                                $orIns['customer_image'] = $filename;
                            }

                            if ($request->hasFile('grander_one_image')) {
                                $image = $request->file('grander_one_image');
                                $filename = 'grander_one_image' . time() . '.' . $image->getClientOriginalExtension();
                                $location = 'assets/images/grander/' . $filename;
                                Image::make($image)->save($location);

                                $orIns['grander_one_image'] = $filename;
                            }

                            if ($request->hasFile('grander_two_image')) {
                                $image = $request->file('grander_two_image');
                                $filename = 'grander_two_image' . time() . '.' . $image->getClientOriginalExtension();
                                $location = 'assets/images/grander/' . $filename;
                                Image::make($image)->save($location);

                                $orIns['grander_two_image'] = $filename;
                            }

                            $orderInss = OrderInstalment::create($orIns);


                            $instalment = Instalment::findOrFail($request->instalment_id);
                            $parIns = ceil($grandDueAmount / $instalment->time);

                            if ($instalment->duration_type != 0){
                                $tt = '';
                                $date1 = $orderInss->created_at;

                                for ($i = 1; $i <= $instalment->time; $i++) {
                                    $insTime['order_instalment_id'] = $orderInss->id;
//                                    $insTime['amount'] = $parIns;
                                    $insTime['amount'] = $parIns;
                                    $tt =$date1->addDays($instalment->difference);
                                    $insTime['pay_date'] = $tt;
                                    // dd($insTime);
                                    InstalmentTime::create($insTime);
                                }
                            }else{
                                $tt = '';
//                                dd($instalment->time)
                                $datArr = [];
                                $date1 = $orderInss->created_at;
                                for ($i = 1; $i <= $instalment->time; $i++) {
                                    $insTime['order_instalment_id'] = $orderInss->id;

//                                    $insTime['amount'] = $parIns;
                                    $insTime['amount'] = $parIns;
//                                    $tt1 = Carbon::parse($tt)->addDays($instalment->difference);
                                    $tt1 = $date1;
                                    $date = $tt1->format('d');
                                    $month = $tt1->format('m');
                                    $year = $tt1->format('Y');
                                    $newMonth = $month + $i;
                                    $newYear = $year;
                                    if ($newMonth > 12) {
                                        $newMonth = $newMonth - 12;
                                        $newYear = $year + 1;
                                    }
                                    $tt = $date . '-' . $newMonth . '-' . $newYear . ' 12:00:00';
                                    $date = strtotime($tt);
                                    $t =  date('Y-m-d H:i:s', $date);
                                    $insTime['pay_date'] = $t;
                                    InstalmentTime::create($insTime);

                                }

                            }

                            $order->save();

                            $tr['custom'] = $custom;
                            $tr['created_at'] = $date;
                            $tr['type'] = 9; // Sell Product
                            $tr['balance'] = $request->ins_pay_amount;
                            $tr['status'] = 0;// David
                            $tr['post_balance'] = $basic->balance + $request->ins_pay_amount;
                            TransactionLog::create($tr);

                            $basic->balance += $request->ins_pay_amount;
                            $basic->save();


                        }
                    }

                } else {
                    session()->flash('message', 'Enter Valid Product First.');
                    session()->flash('type', 'warning');
                    return redirect()->back();
                }

            }
            else    //Existing Customer
            {
                if ($request->customer_id == null) {
                    session()->flash('message', 'Select A Exist Customer.');
                    session()->flash('type', 'warning');
                    return redirect()->back();
                } else {

                    $customer = Customer::find($request->customer_id);

                    if ($request->product_price != null) {

                        if ($request->payment_type == 0) {      //on paid

                            $orderT = Order::create();
                            $order = Order::find($orderT->id);

                            $product_price = 0;

                            foreach ($codes as $c) {
                                $pp = Code::whereCode($c)->firstOrFail();
                                $orderItem['order_id'] = $order->id;
                                $orderItem['custom'] = $custom;
                                $orderItem['created_at'] = $date;
                                $orderItem['product_id'] = $pp->product_id;
                                $orderItem['store_id'] = $pp->store_id;
                                $orderItem['code'] = $c;
                                $product_price += $pp->store->sell_price;
                                OrderItem::create($orderItem);
                                $pp->status = 1;
                                $pp->save();
                            }

                            $order->custom = $custom;
                            $order->created_at = $date;
                            $order->customer_id = $customer->id;
                            $order->product_price = $product_price;

                            $order->discount_type = $request->discount_type;
                            $order->discount = $request->discount;
                            if ($request->discount_type == 0) {
                                $order->product_total = $product_price;
                            } elseif ($request->discount_type == 1) {
                                $order->product_total = $product_price - round(($product_price * $request->discount) / 100, 2);
                            } else {
                                $order->product_total = $product_price - $request->discount;
                            }

                            $order->payment_type = $request->payment_type;
                            $order->total_amount = $request->on_total_price;
                            $order->pay_amount = $request->on_total_price;
                            $order->due_amount = 0;
                            $order->status = 1; // complete
                            $order->save();

                            $customer->total_amount += $request->on_total_price;
                            $customer->pay_amount += $request->on_total_price;
                            $customer->save();

                            $tr['custom'] = $custom;
                            $tr['created_at'] = $date;
                            $tr['type'] = 9; // Sell Product
                            $tr['balance'] = $request->on_total_price;
                            $tr['status'] = 0;// David
                            $tr['post_balance'] = $basic->balance + $request->on_total_price;
                            TransactionLog::create($tr);

                            $basic->balance += $request->on_total_price;
                            $basic->save();

                        } elseif ($request->payment_type == 1) {

                            $orderT = Order::create();
                            $order = Order::find($orderT->id);

                            $product_price = 0;
                            foreach ($codes as $c) {
                                $pp = Code::whereCode($c)->firstOrFail();
                                $orderItem['order_id'] = $order->id;
                                $orderItem['custom'] = $custom;
                                $orderItem['created_at'] = $date;
                                $orderItem['product_id'] = $pp->product_id;
                                $orderItem['store_id'] = $pp->store_id;
                                $orderItem['code'] = $c;
                                $product_price += $pp->store->sell_price;
                                OrderItem::create($orderItem);
                                $pp->status = 1;
                                $pp->save();
                            }
                            $order->custom = $custom;
                            $order->created_at = $date;
                            $order->customer_id = $customer->id;
                            $order->product_price = $product_price;

                            $order->discount_type = $request->discount_type;
                            $order->discount = $request->discount;
                            if ($request->discount_type == 0) {
                                $order->product_total = $product_price;
                            } elseif ($request->discount_type == 1) {
                                $order->product_total = $product_price - round(($product_price * $request->discount) / 100, 2);
                            } else {
                                $order->product_total = $product_price - $request->discount;
                            }

                            $order->payment_type = $request->payment_type;
                            $order->total_amount = $request->due_total_price;
                            $order->pay_amount = $request->due_pay_amount;
                            $order->due_amount = $request->due_total_price - $request->due_pay_amount;//$grandDueAmount;
                            $order->due_payment_date = $request->due_payment_date;
                            $order->status = 2; // Have Due Payment
                            $order->save();

                            $customer->total_amount += $request->due_total_price;
                            $customer->pay_amount += $request->due_pay_amount;
                            $customer->save();

                            $tr['custom'] = $custom;
                            $tr['created_at'] = $date;
                            $tr['type'] = 9; // Sell Product
                            $tr['balance'] = $request->due_pay_amount;
                            $tr['status'] = 0;// David
                            $tr['post_balance'] = $basic->balance + $request->due_pay_amount;
                            TransactionLog::create($tr);

                            $basic->balance += $request->due_pay_amount;
                            $basic->save();
                        } else {

                            if ($request->instalment_id == null) {
                                session()->flash('message', 'Select A Instalment Type');
                                session()->flash('type', 'warning');
                                return redirect()->back();
                            } else {
                                $instalment = Instalment::findOrFail($request->instalment_id);
                                $due_amount = ($request->ins_total_amount - $request->ins_pay_amount); //($request->due_total_price - $request->due_pay_amount);
                                $due_charge = ($instalment->charge / 100) * $due_amount;
                                $grandDueAmount = round($due_charge + $due_amount);

                                $orderT = Order::create();
                                $order = Order::find($orderT->id);

                                $product_price = 0;
                                foreach ($codes as $c) {
                                    $pp = Code::whereCode($c)->firstOrFail();
                                    $orderItem['order_id'] = $order->id;
                                    $orderItem['custom'] = $custom;
                                    $orderItem['created_at'] = $date;
                                    $orderItem['product_id'] = $pp->product_id;
                                    $orderItem['store_id'] = $pp->store_id;
                                    $orderItem['code'] = $c;
                                    $product_price += $pp->store->sell_price;
                                    OrderItem::create($orderItem);
                                    $pp->status = 1;
                                    $pp->save();
                                }

                                $order->custom = $custom;
                                $order->created_at = $date;
                                $order->customer_id = $customer->id;
                                $order->product_price = $product_price;

                                $order->discount_type = $request->discount_type;
                                $order->discount = $request->discount;
                                if ($request->discount_type == 0) {
                                    $order->product_total = $product_price;
                                } elseif ($request->discount_type == 1) {
                                    $order->product_total = $product_price - round(($product_price * $request->discount) / 100, 2);
                                } else {
                                    $order->product_total = $product_price - $request->discount;
                                }

                                $order->payment_type = $request->payment_type;
                                $order->instalment_id = $request->instalment_id;
                                $order->total_amount = $request->ins_total_amount;
                                $order->pay_amount = $request->ins_pay_amount;//$request->due_pay_amount;
                                $order->due_amount = $grandDueAmount;
                                $order->status = 3; // Instalmentt

                                $customer->total_amount += $request->ins_total_amount;
                                $customer->pay_amount += $request->ins_pay_amount;
                                $customer->save();

                                $orIns['custom'] = $custom;
                                $orIns['order_id'] = $order->id;
                                $orIns['created_at'] = $date;
                                $orIns['customer_id'] = $customer->id;
                                $orIns['instalment_id'] = $request->instalment_id;
                                $orIns['total_amount'] = $order->due_amount;

                                $orIns['customer_nid'] = $request->customer_nid;
                                $orIns['customer_father'] = $request->customer_father;
                                $orIns['grander_one_name'] = $request->grander_one_name;
                                $orIns['grander_one_father'] = $request->grander_one_father;
                                $orIns['grander_one_phone'] = $request->grander_one_phone;
                                $orIns['grander_one_address'] = $request->grander_one_address;
                                $orIns['grander_two_name'] = $request->grander_two_name;
                                $orIns['grander_two_father'] = $request->grander_two_father;
                                $orIns['grander_two_phone'] = $request->grander_two_phone;
                                $orIns['grander_two_address'] = $request->grander_two_address;


                                if ($request->hasFile('customer_image')) {
                                    $image = $request->file('customer_image');
                                    $filename = 'customer' . time() . '.' . $image->getClientOriginalExtension();
                                    $location = 'assets/images/customer/' . $filename;
                                    Image::make($image)->save($location);

                                    $orIns['customer_image'] = $filename;
                                }

                                if ($request->hasFile('grander_one_image')) {
                                    $image = $request->file('grander_one_image');
                                    $filename = 'grander_one_image' . time() . '.' . $image->getClientOriginalExtension();
                                    $location = 'assets/images/grander/' . $filename;
                                    Image::make($image)->save($location);

                                    $orIns['grander_one_image'] = $filename;
                                }

                                if ($request->hasFile('grander_two_image')) {
                                    $image = $request->file('grander_two_image');
                                    $filename = 'grander_two_image' . time() . '.' . $image->getClientOriginalExtension();
                                    $location = 'assets/images/grander/' . $filename;
                                    Image::make($image)->save($location);

                                    $orIns['grander_two_image'] = $filename;
                                }

                                $orderInss = OrderInstalment::create($orIns);


                                $instalment = Instalment::findOrFail($request->instalment_id);
                                $parIns = ceil($grandDueAmount / $instalment->time);

                                if ($instalment->duration_type != 0){
                                    $tt = '';
                                    $date1 = $orderInss->created_at;

                                    for ($i = 1; $i <= $instalment->time; $i++) {
                                        $insTime['order_instalment_id'] = $orderInss->id;
//                                    $insTime['amount'] = $parIns;
                                        $insTime['amount'] = $parIns;
                                        $tt =$date1->addDays($instalment->difference);
                                        $insTime['pay_date'] = $tt;
                                       // dd($insTime);
                                        InstalmentTime::create($insTime);
                                    }
                                }else{
                                    $tt = '';
//                                dd($instalment->time)
                                    $datArr = [];
                                    $date1 = $orderInss->created_at;
                                    for ($i = 1; $i <= $instalment->time; $i++) {
                                        $insTime['order_instalment_id'] = $orderInss->id;

//                                    $insTime['amount'] = $parIns;
                                        $insTime['amount'] = $parIns;
//                                    $tt1 = Carbon::parse($tt)->addDays($instalment->difference);
                                        $tt1 = $date1;
                                        $date = $tt1->format('d');
                                        $month = $tt1->format('m');
                                        $year = $tt1->format('Y');
                                        $newMonth = $month + $i;
                                        $newYear = $year;
                                        if ($newMonth > 12) {
                                            $newMonth = $newMonth - 12;
                                            $newYear = $year + 1;
                                        }
                                        $tt = $date . '-' . $newMonth . '-' . $newYear . ' 12:00:00';
                                        $date = strtotime($tt);
                                        $t =  date('Y-m-d H:i:s', $date);
                                        $insTime['pay_date'] = $t;
                                        InstalmentTime::create($insTime);

                                    }

                                }

                                $order->save();

                                $tr['custom'] = $custom;
                                $tr['created_at'] = $date;
                                $tr['type'] = 9; // Sell Product
                                $tr['balance'] = $request->ins_pay_amount;
                                $tr['status'] = 0;// David
                                $tr['post_balance'] = $basic->balance + $request->ins_pay_amount;
                                TransactionLog::create($tr);

                                $basic->balance += $request->ins_pay_amount;
                                $basic->save();


                            }
                        }

                    } else {
                        session()->flash('message', 'Enter Valid Product First.');
                        session()->flash('type', 'warning');
                        return redirect()->back();
                    }
                }
            }

        } else {
            session()->flash('message', 'Enter Valid Bar Code.');
            session()->flash('type', 'warning');
            return redirect()->back();
        }
        $discount = $order->product_total - $product_price;
         $amnt =$order->total_amount-$order->pay_amount;
         $instalment_charge =$order->due_amount - $amnt;

       //$smsdata = ['total'=>$order->total_amount + $instalment_charge,'product_price'=>$request->product_price,'discount'=>$discount,'customer_name'=>$customer->name,'customer_phone'=>$customer->phone,'grand_1'=>$request->grander_one_phone,'grand_2'=>$request->grander_two_phone,'grand_one_name'=>$request->grander_one_name,'grand_two_name'=>$request->grander_two_name,'pay_amount'=> $order->pay_amount,'due'=>$order->due_amount,'invoice'=>$custom];
        $data = ['total'=>$order->total_amount + $instalment_charge,'product_price'=>$request->product_price,'discount'=>$discount,'customer_name'=>$customer->name,'customer_phone'=>$customer->phone,'grand_1'=>$request->grander_one_phone,'grand_2'=>$request->grander_two_phone,'grand_one_name'=>$request->grander_one_name,'grand_two_name'=>$request->grander_two_name,'pay_amount'=> $order->pay_amount,'due'=>$order->due_amount,'invoice'=>$custom];

        $msg ="?????????????????? ??????????????????," .$data['customer_name'].",  ????????? ?????? ".$data['invoice']." ?????????????????? ??????????????? ".$data['product_price']." ????????????, ??????????????????????????? ".$data['discount']." ????????????, ????????????????????? ????????? ".$data['total']." ????????????, ???????????????????????? ????????? ".$data['pay_amount']." ????????????, ?????????????????????  ???????????? " .$data['due']." ????????????
?????????????????????: 01967676551
New Aman Electronics, ????????????????????? ???";
       // dd($msg);
       $grander_one_msg = "?????????????????? ?????????????????????????????????,".$data['grand_one_name'].", ????????? ?????? ".$data['invoice']." ?????????????????? ??????????????? ".$data['product_price']." ????????????, ??????????????????????????? ".$data['discount']." ????????????, ????????????????????? ????????? ".$data['total']." ????????????, ???????????????????????? ????????? ".$data['pay_amount']." ????????????, ????????????????????? ???????????? " .$data['due']." ????????????,?????????????????????????????? ?????????????????? ?????????????????? ?????????????????? ?????????????????????
?????????????????????: 01967676551
New Aman Electronics, ????????????????????? ???";

        $grander_two_msg ="?????????????????? ?????????????????????????????????,".$data['grand_two_name'].", ????????? ?????? ".$data['invoice']." ?????????????????? ??????????????? ".$data['product_price']." ????????????, ??????????????????????????? ".$data['discount']." ????????????, ????????????????????? ????????? ".$data['total']." ????????????, ???????????????????????? ????????? ".$data['pay_amount']." ????????????, ????????????????????? ???????????? " .$data['due']." ????????????,?????????????????????????????? ?????????????????? ?????????????????? ?????????????????? ?????????????????????
?????????????????????: 01967676551
New Aman Electronics, ????????????????????? ???";

       smsGateWay($msg,$data['customer_phone']);
       smsGateWay($grander_one_msg,$data['grand_1']);
       smsGateWay($grander_two_msg,$data['grand_2']);


        session()->flash('message', 'Item Sell Successfully Completed.');
        session()->flash('type', 'success');
        return redirect()->route('sell-invoice', $custom);


    }

    public function sellInvoice($invoice)
    {
        $data['page_title'] = 'Sell Invoice';
        $data['sell'] = Order::whereCustom($invoice)->firstOrFail();
        $data['sellItem'] = OrderItem::whereCustom($invoice)->get();
        if ($data['sell']->payment_type == 2) {
            $orderInstalment = OrderInstalment::whereOrder_id($data['sell']->id)->first()->id;
            $data['instalmentList'] = InstalmentTime::whereOrder_instalment_id($orderInstalment)->get();
            $data['installmentData'] = getInstallmentDetailsById($orderInstalment);
           // dd($data['installmentData']);
        }


        return view('sell.sell-invoice',$data);
    }



    public function chalan($invoice)
    {
        $data['page_title'] = 'Chalan';
        $data['sell'] = Order::whereCustom($invoice)->firstOrFail();
        $data['sellItem'] = OrderItem::whereCustom($invoice)->get();
        if ($data['sell']->payment_type == 2) {
            $orderInstalment = OrderInstalment::whereOrder_id($data['sell']->id)->first()->id;
            $data['instalmentList'] = InstalmentTime::whereOrder_instalment_id($orderInstalment)->get();
            $data['installmentData'] = getInstallmentDetailsById($orderInstalment);
            //dd($data['installmentData']);
        }


        return view('sell.chalan',$data);
    }

    public function printInvoice($invoice)
    {
        $data['page_title'] = 'Sell Invoice';
        $data['sell'] = Order::whereCustom($invoice)->firstOrFail();
        $data['sellItem'] = OrderItem::whereCustom($invoice)->get();
        
          if ($data['sell']->payment_type == 2) {
            $orderInstalment = OrderInstalment::whereOrder_id($data['sell']->id)->first()->id;
            $data['instalmentList'] = InstalmentTime::whereOrder_instalment_id($orderInstalment)->get();
            $data['installmentData'] = getInstallmentDetailsById($orderInstalment);
        }
        return view('sell.invoice-print', $data);
    }



    public function printchalan($invoice)
    {
        $data['page_title'] = 'Chalan';
        $data['sell'] = Order::whereCustom($invoice)->firstOrFail();
        $data['sellItem'] = OrderItem::whereCustom($invoice)->get();

        if ($data['sell']->payment_type == 2) {
            $orderInstalment = OrderInstalment::whereOrder_id($data['sell']->id)->first()->id;
            $data['instalmentList'] = InstalmentTime::whereOrder_instalment_id($orderInstalment)->get();
            $data['installmentData'] = getInstallmentDetailsById($orderInstalment);
        }
        return view('sell.chalan-print', $data);
    }

    public function sellHistory()
    {
        $data['page_title'] = 'Sell History';
        $data['sell'] = Order::latest()->get();
        //dd($data['sell']);
        return view('sell.sell-history', $data);
    }


    public function sellDelete(Request $request)
    {
        $request->validate([
            'delete_id' => 'required'
        ]);

        $order = Order::findOrFail($request->delete_id);

        $orderItem = OrderItem::whereCustom($order->custom)->get();

        foreach ($orderItem as $ot) {
            $item = Code::whereCode($ot->code)->first();
            $item->status = 0;
            $item->save();
            $ot->delete();
        }

        if ($order->payment_type == 1) {
            // Due Payment
            $customer = Customer::findOrFail($order->customer_id);
            $customer->total_amount -= $order->total_amount;
            $customer->pay_amount -= $order->pay_amount;
            $customer->save();
        } elseif ($order->payment_type == 2) {
            // Instalment payment
            $customer = Customer::findOrFail($order->customer_id);
            $customer->total_amount -= $order->total_amount;
            $customer->pay_amount -= $order->pay_amount;
            $customer->save();
            $instalment = OrderInstalment::whereCustom($order->custom)->firstOrFail();
            InstalmentTime::whereOrder_instalment_id($instalment->id)->delete();
            $instalment->delete();
        } else {
            // On paid Payment
            $customer = Customer::findOrFail($order->customer_id);
            $customer->total_amount -= $order->total_amount;
            $customer->pay_amount -= $order->pay_amount;
            $customer->save();
        }
        $basic = BasicSetting::first();
        $tr['custom'] = $order->custom;
        $tr['type'] = 10; // Sell Product return
        $tr['balance'] = $order->pay_amount;
        $tr['status'] = 1;// Credit
        $tr['post_balance'] = $basic->balance - $order->pay_amount;
        TransactionLog::create($tr);

        $order->delete();

        $basic->balance -= $order->pay_amount;
        $basic->save();


        session()->flash('message', 'Sell Item Deleted Successfully.');
        session()->flash('type', 'success');
        return redirect()->back();

    }


}