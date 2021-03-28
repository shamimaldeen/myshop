<?php
function getPaid($installment_id=null){
    if($installment_id!=null) {
        $ins_time = DB::table('instalment_times')->where('order_instalment_id', $installment_id)->select(DB::Raw('sum(pay_amount) as paid'), DB::Raw('sum(amount) as totalInstallment'))->first();
        $paid = $ins_time->paid;

    }else{
        $paid = 0;

    }
    return $paid;
}

function getTotaldiscount($installment_id=null){
    if($installment_id!=null) {
        $ins_time = DB::table('instalment_times')->where('order_instalment_id', $installment_id)->select( DB::Raw('sum(amount) as totalInstallment'),DB::Raw('sum(ins_discount) as totaldiscount'))->first();
       $totaldiscount = $ins_time->totaldiscount;
    }else{

        $totaldiscount=0;
    }
    return $totaldiscount;

}

function getInstallmentDetailsById($installment_id){
    if($installment_id!=null) {
        $ins_time = DB::table('instalment_times')->where('order_instalment_id', $installment_id)->select(DB::Raw('sum(pay_amount) as totalpaid'), DB::Raw('sum(amount) as totalInstallment'),DB::Raw('sum(ins_discount) as totaldiscount'))->first();
        $instalmentsdata = DB::table('instalment_times')->where('order_instalment_id', $installment_id)->select(DB::Raw('pay_amount as paid_amount'), DB::Raw('ins_discount as discount'))->first();
        $totalamount = DB::table('instalment_times')->where('order_instalment_id', $installment_id)->where('status', 0 )->select(DB::Raw('sum(amount) as totalamount'))->first();
        $totaldiscount = $ins_time->totaldiscount;
        $due = ($ins_time->totalInstallment - $totaldiscount) - $ins_time->totalpaid;
        $total = $ins_time->totalInstallment;
        $totalpaid = $ins_time->totalpaid;
        $paid = $instalmentsdata->paid_amount;
        $discount = $instalmentsdata->discount;
        $totalbil =$totalamount->totalamount + $paid+ $discount;

    }else{
        $due = 0;
        $total = 0;
        $paid = 0;
        $totalpaid = 0;
        $totaldiscount = 0;
        $discount = 0;
    }
    return ['due'=>$due,'total'=>$totalbil,'paid'=>$paid,'total_discount'=>$totaldiscount,'discount'=>$discount,'totalpaid' =>$totalpaid ];
}

function getCustomerById($id){
    return DB::table('customers')->where('id',$id)->first();
}
function getOrderById($id){
    return DB::table('orders')->where('id',$id)->first();
}

 function smsGateWay($msg,$mobile){
     //$data = array();
     $data[] = array(
         'recipient' => $mobile,
         'mask' => 'AmanElec..s',
         'message' =>$msg,
     );

//     $msg[] = array(
//         'recipient' => 'another destination mobile number here',
//         'mask' => 'Your mask here',
//         'message' => 'Your text here',
//     );

     $auth = array(
         'username' => 'newaman',
         'api_key' => '24d2065342e0bd646ec944935b0418af216',
         'api_secret' => '901acbe760530bdba4c35b137afbc13c216',
     );

     $fields = array(
         'auth' => $auth,
         'sms_data' => $data
     );

     $headers = array
     (
         'Content-Type: application/json'
     );


     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'http://202.164.208.212/smsnet/bulk/api');
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
     $result = curl_exec($ch); //this is json data
     curl_close($ch);

//print_r($msg) ;
    // print_r($result);
//dd($result);
 }