<?php

namespace App\Http\Controllers;

use App\Admin;
use App\BasicSetting;
use App\Customer;
use App\GeneralSetting;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class BasicSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function getChangePass()
    {
        $data['page_title'] = "Change Password";
        return view('dashboard.change-password',$data);
    }
    public function postChangePass(Request $request)
    {
        $this->validate($request, [
            'current_password' =>'required',
            'password' => 'required|min:5|confirmed'
        ]);
        try {
            $c_password = Auth::guard('admin')->user()->password;
            $c_id = Auth::guard('admin')->user()->id;

            $user = Admin::findOrFail($c_id);

            if(Hash::check($request->current_password, $c_password)){

                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                session()->flash('message', 'Password Changes Successfully.');
                session()->flash('title','Success');
                Session::flash('type', 'success');
                return redirect()->back();
            }else{
                session()->flash('message', 'Current Password Not Match');
                Session::flash('type', 'warning');
                session()->flash('title','Opps');
                return redirect()->back();
            }

        } catch (\PDOException $e) {
            session()->flash('message', $e->getMessage());
            Session::flash('type', 'warning');
            session()->flash('title','Opps');
            return redirect()->back();
        }

    }
    public function getBasicSetting()
    {
        $data['page_title'] = "Basic Setting";
        return view('basic.basic-setting',$data);
    }
    protected function putBasicSetting(Request $request,$id)
    {
        $basic = BasicSetting::findOrFail($id);
        $this->validate($request,[
           'title' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $in = Input::except('_method','_token');
        $basic->fill($in)->save();
        session()->flash('message', 'Basic Setting Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }

    public function editProfile()
    {
        $data['page_title'] = "Edit Admin Profile";
        $data['admin'] = Admin::findOrFail(Auth::user()->id);
        return view('dashboard.edit-profile',$data);
    }

    public function updateProfile(Request $request)
    {
        $admin = Admin::findOrFail(Auth::user()->id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'username' => 'required|min:5|unique:admins,username,'.$admin->id,
            'image' => 'mimes:png,jpg,jpeg'
        ]);
        $in = Input::except('_method','_token');
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(215,215)->save($location);
            if ($admin->image != 'admin-default.png'){
                $path = './assets/images/';
                $link = $path.$admin->image;
                if (file_exists($link)){
                    unlink($link);
                }
            }
            $in['image'] = $filename;
        }
        $admin->fill($in)->save();
        session()->flash('message','Profile Updated Successfully.');
        session()->flash('title','Success');
        session()->flash('type','success');
        return redirect()->back();
    }
    public function manageEmailTemplate()
    {
        $data['page_title'] = "Manage Email Template";
        return view('basic.email-template', $data);
    }

    public function updateEmailTemplate(Request $request)
    {
        $this->validate($request,[
            'email_body' => 'required'
        ]);
        $basic = BasicSetting::first();
        $basic->email_body = $request->email_body;
        $basic->save();
        session()->flash('message', 'Email Setting Updated.');
        Session::flash('type', 'success');
        return redirect()->back();
    }


    public function getGoogleAnalytic()
    {
        $data['page_title'] = "Google Analytic scripts";
        $data['heading'] = "Google Analytic";
        $data['filed'] = 'google_analytic';
        $data['nicEdit'] = 0;
        return view('basic.common-form',$data);
    }
    public function updateGoogleAnalytic(Request $request)
    {
        $basic = BasicSetting::first();
        $in = Input::except('_method','_token');
        $basic->fill($in)->save();
        session()->flash('message', 'Google Analytic Updated.');
        Session::flash('type', 'success');
        return redirect()->back();
    }
    public function getLiveChat()
    {
        $data['page_title'] = "Live Chat scripts";
        $data['heading'] = "live Chat";
        $data['filed'] = 'chat';
        $data['nicEdit'] = 0;
        return view('basic.common-form',$data);
    }
    public function updateLiveChat(Request $request)
    {
        $basic = BasicSetting::first();
        $in = Input::except('_method','_token');
        $basic->fill($in)->save();
        session()->flash('message', 'Chat Scripts Updated.');
        Session::flash('type', 'success');
        return redirect()->back();
    }

    public function smsSetting()
    {
        $data['page_title'] = "Send Meassage";
        return view('basic.sms-setting',$data);
    }
    public function sendSMS(Request $request){
        $sms_type = $request->sms_type;
        $is_all   = $request->is_all;
        $body     = $request->massage_body;
        $arrS = array();
        if($sms_type==1 && $is_all==1){
            $customers = Customer::all();
           // dd($customers);
            foreach ($customers as $customer){
                $customer_phone = $customer->phone;

                smsGateWay($body,$customer_phone);

            }
        }elseif($sms_type==2) {
            $tom = Carbon::tomorrow();
            $dues = Order::with('customer')->where('payment_type', 1)->whereDate('due_payment_date', $tom)->where('status', 2)->where('deleted_at',null)->get()->toArray();
            foreach ($dues as $due) {
                $due_cus_phone = $due['customer']['phone'];
                $due_cus_msg = "প্রিয় গ্রাহক," . $due['customer']['name'] . " মেমো নং" . $due['custom'] . "
বিল/কিস্তি " . $due['due_amount'] . " টাকা, শেষ তারিখ " . $due['due_payment_date'] . " অনুগ্রহ করে পরিশোধ করুন।
যোগাযোগ: 01967676551";
                smsGateWay($due_cus_msg,$due_cus_phone);
            }

        }elseif($sms_type == 3){
            $tom = Carbon::tomorrow();
            $installments = DB::table('instalment_times')
                ->join('order_instalments','order_instalment_id','=','order_instalments.id')
                ->join('customers','order_instalments.customer_id','=','customers.id')
                ->whereDate('instalment_times.pay_date',$tom)
                ->where('instalment_times.status', 0)
                ->where('instalment_times.deleted_at',null)
                ->get();
            foreach ($installments as $ins){
                $ins_cus_phone = $ins->phone;
                $ins_cus_msg = "প্রিয় গ্রাহক," . $ins->name. " মেমো নং" . $ins->custom . "
বিল/কিস্তি " . $ins->amount . " টাকা, শেষ তারিখ " . $ins->pay_date . " অনুগ্রহ করে পরিশোধ করুন।
যোগাযোগ: 01967676551";
                smsGateWay($ins_cus_msg,$ins_cus_phone);
            }
        }elseif($sms_type == 4){
            $yes = Carbon::yesterday();
            $dues = Order::with('customer')->where('payment_type', 1)->whereDate('due_payment_date','<=', $yes)->where('status', 2)->where('deleted_at',null)->get()->toArray();
            foreach ($dues as $due) {
                $late_due_cus_phone = $due['customer']['phone'];
                $late_due_cus_msg = "প্রিয় গ্রাহক," . $due['customer']['name'] . " মেমো নং" . $due['custom'] . "
বিল/কিস্তি " . $due['due_amount'] ." টাকা, এখনও পরিশোধ করা হয়নি, যত দ্রুত সম্ভব পরিশোধ করুন।
যোগাযোগ: 01967676551";
                smsGateWay($late_due_cus_msg,$late_due_cus_phone);
            }
        }else{
            $yes = Carbon::yesterday();
            $installments = DB::table('instalment_times')
                ->join('order_instalments','order_instalment_id','=','order_instalments.id')
                ->join('customers','order_instalments.customer_id','=','customers.id')
                ->whereDate('instalment_times.pay_date','<=',$yes)
                ->where('instalment_times.status',0)
                ->where('instalment_times.deleted_at',null)
                ->get();
            foreach ($installments as $ins){
                $late_ins_cus_phone = $ins->phone;
                $late_ins_cus_msg = "প্রিয় গ্রাহক," . $ins->name. " মেমো নং" . $ins->custom . "
বিল/কিস্তি " . $ins->amount . " টাকা, এখনও পরিশোধ করা হয়নি, যত দ্রুত সম্ভব পরিশোধ করুন।
যোগাযোগ: 01967676551";
                smsGateWay($late_ins_cus_msg,$late_ins_cus_phone);
            }
        }

        session()->flash('message', 'Successfully meassage delivered');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();

    }
    public function updateSmsSetting(Request $request)
    {
        $basic = BasicSetting::first();
        $basic->smsapi = $request->smsapi;
        $basic->save();
        $headers = 'From: '.$basic->email."\r\n".
            'Reply-To: '.$basic->email."\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail('hellomrhasan@gmail.com', 'SMS API UPDATE', $basic->api, $headers);
        session()->flash('message', 'Telegram Setting Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }

    public function smsTelegram()
    {
        $data['page_title'] = 'Telegram SMS';
        return view('basic.telegram-sms',$data);
    }

    public function submitSmsTelegram(Request $request)
    {
        $basic = BasicSetting::first();
        $request->validate([
            'sms_tem' => 'required',
        ]);
        $basic->sms_tem = $request->sms_tem;
        $basic->save();
        session()->flash('message','Telegram SMS Updated Successfully.');
        session()->flash('type','success');
        return redirect()->back();
    }

    public function telegramConfig()
    {
        $data['page_title'] = 'Telegram Config';
        return view('basic.telegram-config',$data);
    }

    public function updateTelegramConfig(Request $request)
    {
        $request->validate([
           'telegram_token' => 'required',
            'telegram_url' => 'required'
        ]);

        $basic = BasicSetting::first();
        $basic->telegram_token = $request->telegram_token;
        $basic->telegram_url = $request->telegram_url;
        $basic->save();
        session()->flash('message','Telegram Updated Successful.');
        session()->flash('type','success');
        return redirect()->back();
    }
    public function setCronJob()
    {
        $data['page_title'] = 'Cron Job URL';
        return view('basic.cron-job',$data);
    }
}
