<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>支付页面</title>
</head>
<body>
<div>
    <form action="/paypal/index.php/Home/Index/checkout" method="post" autocomplete="off">
        <label for="item">
            产品名称
            <input type="text" name="product">
        </label>
        <br>
        <label for="amount">
            价格
            <input type="text" name="price">
        </label>
        <br>
        <input type="submit" value="去付款">
    </form>
</div>
</body>
</html>