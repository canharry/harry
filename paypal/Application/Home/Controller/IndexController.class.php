<?php

namespace Home\Controller;

use Think\Controller;
use \PayPal\Api\PaymentExecution;
use \PayPal\Api\ShippingAddress;
use \PayPal\Api\Payer;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use \PayPal\Api\Details;
use \PayPal\Api\Amount;
use \PayPal\Api\Transaction;
use \PayPal\Api\RedirectUrls;
use \PayPal\Api\Payment;
use \PayPal\Exception\PayPalConnectionException;

class IndexController extends Controller
{
    public function index()
    {
        $this->display();
    }

    private function start()
    {
        //网站url自行定义
        define('SITE_URL', 'http://localhost/paypal/index/home/index');
        //创建支付对象实例
        vendor("pay.autoload");
        $paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AckAgdCn4XCrgnhpUr7JszYo-svo5MQIYP94Y96TJ19saTrTlFkst9V1P75YCNTM_Knxb3OmjIUlcuGM',
                'EKlhu2S9hWjJl9E9sg3YB4voaH7JR6JTXEr1R-lHbVzskMWl0cgFpIju1Fisd3z8HnL5RpMtl723fE7H'

            )
        );
        $paypal->setConfig(array('mode' => 'sandbox'));
        return $paypal;
    }

    public function checkout()
    {
        $paypal = $this->start();
        $product = I('post.product');
        $price = I('post.price');
        if (empty($product) && empty($price)) {
            echo '<Script language="JavaScript">alert("lose some params");history.go(-1)</Script>';
            die("lose some params");
        }
        $shipping = 0.00; //运费

        $total = $price + $shipping;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $details = new Details();
        $details->setShipping($shipping)
            ->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("支付描述内容1111")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(SITE_URL . '/pay?success=true')
            ->setCancelUrl(SITE_URL . '/pay?success=false');


        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($paypal);
        } catch (PayPalConnectionException $e) {
            echo $e->getData();
            die();
        }

        $approvalUrl = $payment->getApprovalLink();
        header("Location: {$approvalUrl}");
    }

    public function pay()
    {
        $paypal = $this->start();
        //print_r($_GET);die;
        if (!isset($_GET['success'], $_GET['paymentId'], $_GET['PayerID'])) {
            die();
        }

        if ((bool)$_GET['success'] === 'false') {

            echo 'Transaction cancelled!';
            die();
        }

        $paymentID = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];

        $payment = Payment::get($paymentID, $paypal);

        $execute = new PaymentExecution();
        $execute->setPayerId($payerId);
        print_r($_GET);die;
        try {
            $result = $payment->execute($execute, $paypal);
        } catch (Exception $e) {
            die($e);
        }
        //print_r($_GET);die;
        echo '<script>alert("支付成功！感谢支持!");window.location.href="index"</script>';
    }
}