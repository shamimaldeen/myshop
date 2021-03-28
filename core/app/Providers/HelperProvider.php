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

class HelperProvider extends ServiceProvider
{
    /**
     * Sms System
     * @provider metrotel
     * @param $number
     * @param $message
     */
    public static function send_sms($number, $message)
    {
        $url = "http://portal.metrotel.com.bd/smsapi";
        $data = [
            "api_key" => "R20000515e1ef3b81a91f3.43597052",
            "type" => "{text}",
            "contacts" => "88" . $number,
            "senderid" => "8809612440471",
            "msg" => "{" . $message . "}",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
