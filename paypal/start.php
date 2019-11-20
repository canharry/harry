<?php
require "vendor/autoload.php"; //载入sdk的自动加载文件
define('SITE_URL', 'http://localhost/PayPal'); //网站url自行定义
//创建支付对象实例
$paypal = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        //模拟
        'AckAgdCn4XCrgnhpUr7JszYo-svo5MQIYP94Y96TJ19saTrTlFkst9V1P75YCNTM_Knxb3OmjIUlcuGM',
        'EKlhu2S9hWjJl9E9sg3YB4voaH7JR6JTXEr1R-lHbVzskMWl0cgFpIju1Fisd3z8HnL5RpMtl723fE7H'
    
    )
);