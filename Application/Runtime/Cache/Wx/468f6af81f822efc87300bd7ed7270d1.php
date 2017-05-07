<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>

    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
   
    <title>支付订单</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/css/goods_pay.css">
    <script type="text/javascript" src="/Public/wx/Static/js/jquery.min.js"></script>
    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
</head>
<header class="flex header">
    <i class="back" onclick="window.location.href='/Orders/my_orders/status/0'"></i>
    <h1 class="flex-auto">支付订单</h1>
    <i></i>
</header>
<body>
<style>
    .header i.back {
        background: url(/Public/wx/img/icon.png) no-repeat -12rem -6rem;
        background-size: 15rem;
        vertical-align: middle;
    }
</style>
<div class="main goods-detail" style="margin-top:5rem">
    <!--<div class="list flex goods-name">-->
        <!--<div class="flex-auto">X系列情侣项链X系列情侣项链X系列情侣项链X系列情侣项链X系列情侣项链</div>-->
        <!--<div class="price">&yen;99</div>-->
    <!--</div>-->
    <p class="hint" style="height:1rem" ></p>
    <div class="list no-border">
        <div><i class="icon-price"></i>余额 <?php echo ($score); ?></div>
    </div>
</div>

<p class="hint" >请选择以下支付方式：</p>
<div class="pay-box">
    <label class="pay-wx pay-way">
        微信支付<input name="pay_way" type="radio" value="wx" checked><i class="choose"><span>&yen;<?php echo ($tol_price); ?></span></i>
    </label>
    <label class="pay-zfb pay-way">
        <input name="pay_way" type="radio" value="balance">余额支付<i class="choose"><span>&yen;<?php echo ($tol_price); ?></span></i>
    </label>
</div>
<footer class="footer">
    <a href="javascript:void(0);" onclick="pay();" class="footer-btn">确认支付</a>
</footer>
 <script id='spay-script' src='https://jspay.beecloud.cn/1/pay/jsbutton/returnscripts?appId=cb015ccd-da98-46cd-ac59-4cf40d0f10b1'></script>
<script>
    function pay(){
        var order_no='<?php echo ($_GET['order_no']); ?>';
        var type=$('input[type=radio]:checked').val();
//        if(type=='wx'){
//            layer.msg('微信支付');
//        }else{
//            layer.msg('余额支付');
//        }
        $.post('/Orders/pay',{order_no:order_no,type:type},function(data){
            console.log(data);
            if(data.code=='1000'){
                window.location.href='/Orders/my_orders/status/1';
            }
            if(data.code=='1001'){
                var data=data.result;
                asyncPay(data.title,data.amount,data.sn,data.sign,data.openid);
            }
            if(data.code=='1002'){
                layer.msg(data.result,{time:1000});
            }
        });
    }
    function bcPay(title,amount,sn,sign,openid) {
        var to_url='http://nuoya.86tudi.com/Orders/my_orders/status/1';
        // var amount=parseInt(amount);
        BC.click({
            "title":title,
            "amount":amount,
            "out_trade_no":sn, //唯一订单号
            "sign" :sign,
            'instant_channel':'wx',
            'return_url':to_url,
            "openid":openid,
        });
    }

    function asyncPay(title,amount,sn,sign,openid) {
        if (typeof BC == "undefined") {
            if (document.addEventListener) { // 大部分浏览器
                document.addEventListener('beecloud:onready', bcPay, false);
            } else if (document.attachEvent) { // 兼容IE 11之前的版本
                document.attachEvent('beecloud:onready', bcPay);
            }
        } else {
            bcPay(title,amount,sn,sign,openid);
        }
    }
</script>
</body>
</html>