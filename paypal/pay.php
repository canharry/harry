<?php

require 'start.php';

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Address;
//print_r($_GET);die;
if(!isset($_GET['success'], $_GET['paymentId'], $_GET['PayerID'])){
    die();
}

if((bool)$_GET['success']=== 'false'){

    echo 'Transaction cancelled!';
    die();
}

$paymentID = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

$payment = Payment::get($paymentID, $paypal);

$execute = new PaymentExecution();
$execute->setPayerId($payerId);

try{
    $result = $payment->execute($execute, $paypal);
}catch(Exception $e){
    die($e);
}
echo '支付成功！感谢支持!';