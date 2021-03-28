<?php

namespace App\Http\Controllers;

use App\InstalmentTime;
use App\OrderInstalment;
use Illuminate\Http\Request;
use PDF;

class RepaymentPDFController extends Controller
{
        public function repaymentPDFHistory($custom){
            $order = OrderInstalment::where('custom',$custom)->first();
           // dd($order);
            $data = InstalmentTime::whereOrder_instalment_id($order->id)->get();
            $pdf = PDF::loadView('pdf.installment-payment-history', compact('data'));
            return $pdf->download('installment_payment_history.pdf');
        }
}
