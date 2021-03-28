<?php

namespace App\Providers;

use App\BasicSetting;
use App\InstalmentTime;
use App\Order;
use App\Section;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $basic = BasicSetting::first();

        /*Config::set('mail.driver','mail');*/
        Config::set('mail.host','smtp.mailtrap.io');
        Config::set('mail.username','a3745e46dbdc10');
        Config::set('mail.password','42bd108412d40e');
        Config::set('mail.port','25');
        Config::set('mail.ENCRYPTION','tls');
        Config::set('mail.from',$basic->email);
        Config::set('mail.name',$basic->title);

        View::share('site_title',$basic->title);
        View::share('basic',$basic);


        $tomorrow = Carbon::tomorrow();
        $today = Carbon::now()->format('Y-m-d');
        $nextduedate = DB::table('orders')->whereStatus(2)->whereDate('due_payment_date','<=',$tomorrow)
            ->select('due_payment_date')->get();

        if(count($nextduedate)) {
            $next_due = $nextduedate[0]->due_payment_date;
        }else{
            $next_due = null;
        }
         if($next_due != null){
             $dueCount = Order::whereStatus(2)->wheredate('due_payment_date','<=',$tomorrow)->count();
            // $dueCount = Order::whereStatus(2)->whereBetween('due_payment_date',[$today,$next_due])->count();
         }else{
             $dueCount = 0;
         }




        //upcoming instalment
        $nextdate = DB::table('instalment_times')->whereStatus(0)->whereDate('pay_date','<=',$tomorrow)->select('pay_date')->get();
         if(count($nextdate)>0){
             $next_pay_date = $nextdate[0]->pay_date;
         }else{
             $next_pay_date = null;
         }
        if($next_pay_date != null){
            $instalmentCount = InstalmentTime::whereStatus(0)->wheredate('pay_date','<=',$tomorrow)->count();
        }else{
            $instalmentCount = 0;
        }
        $today1 = Carbon::now()->format('Y-m-d').' 00:00:00';
        $next1 = Carbon::parse()->addMonth()->format('Y-m-d').' 23:59:59';
//        $instalmentCount = 0;
        View::share('dueCount',$dueCount);
        View::share('instalmentCount',$instalmentCount);


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
