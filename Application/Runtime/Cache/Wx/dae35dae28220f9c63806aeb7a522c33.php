<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>会员中心</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/user.css">

    <script type="text/javascript" src="/Public/wx/Static/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/wx/Static/js/wemall.js?222"></script>
    <script type="text/javascript" src="/Public/wx/Static/js/alert.js"></script>
    <script type="text/javascript" src="/Public/wx/Static/js/area.js"></script>
</head>
<script language="javascript">
    function callpay()
    {

        WeixinJSBridge.invoke('editAddress',{
            "appId" : "<?php echo $obj['appId'];?>",
            "scope" : "jsapi_address",
            "signType" : "sha1",
            "addrSign" : "<?php echo $signature;?>",
            "timeStamp" : "<?php echo $timestamp;?>",
            "nonceStr" : "<?php echo $noncestr;?>",
        },function(res){
            if(res.err_msg == 'edit_address:ok')
            {



            }

        });
    }
</script>
<body class="index-tab">
<div class="content">
    <header class="user-header main">
        <a class="wrapper" href="javascript:void(0);">
            <div class="left">
                <div class="user-img">
                    <img src="<?php echo ($user["headimg"]); ?>" style="width:100%">
                </div>
            </div>
            <div class="right ">
                <h2><?php echo ($user["nickname"]); if($user['is_star'] == '1'): ?>（公星）<?php endif; ?></h2>
                <p>会员ID：<?php echo ($user["memberid"]); ?></p>
                <?php if($recommend_nickname != ''): ?><p>推荐人：<?php echo ($recommend_nickname); ?></p><?php endif; ?>
            </div>
        </a>
    </header>
    <!--该版本暂不开放订单和购买功能-->
    <!--<div class="order main">-->
        <!--<p class="flex title">-->
            <!--<span class="flex-auto">我的订单</span>-->
            <!--<a href="/Orders/my_orders/status/3" class="icon-more">查看全部订单</a>-->
        <!--</p>-->
        <!--<ul class="order-box-wrapper flex">-->
            <!--<li class="flex-auto" style="position:relative"><a class="order-box-1" href="/Orders/my_orders/status/0">待付款</a><span style=" top: 3px;right:30%; color:#FFF; background-color:#F00; border-radius:50%; width:16px;  height:16px; text-align:center;font-size: 10px;position: absolute"><?php echo ($user["nopay"]); ?></span></li>-->
            <!--<li class="flex-auto" style="position:relative"><a class="order-box-2" href="/Orders/my_orders/status/1">待发货</a><span style=" top: 3px;right:30%; color:#FFF; background-color:#F00; border-radius:50%; width:16px;  height:16px; text-align:center;font-size: 10px;position: absolute"><?php echo ($user["nodeliver"]); ?></span></li>-->
            <!--<li class="flex-auto" style="position:relative"><a class="order-box-3" href="/Orders/my_orders/status/2">待收货</a><span style=" top: 3px;right:30%; color:#FFF; background-color:#F00; border-radius:50%; width:16px;  height:16px; text-align:center;font-size: 10px;position: absolute"><?php echo ($user["noaccept"]); ?></span></li>-->
            <!--<li class="flex-auto" style="position:relative"><a class="order-box-4" href="/Orders/my_orders/status/4">售后中</a><span style=" top: 3px;right:30%; color:#FFF; background-color:#F00; border-radius:50%; width:16px;  height:16px; text-align:center;font-size: 10px;position: absolute"><?php echo ($user["service"]); ?></span></li>-->
        <!--</ul>-->
    <!--</div>-->
    <div class="other main flex">
        <a href="/Myinfo/my_income" class="flex-auto"><span >
            积分总收入<br><strong><?php echo ($user["all"]); ?></strong>
        </span></a>
        <a href="/Myinfo/score_record" class="flex-auto"><span >
            我的积分<br><strong><?php echo ($user["score"]); ?></strong>
        </span></a>
    </div>
    <div class="main detail-list">
        <ul>
            <li class="detail-list-li detail-list-1">
                <a class="flex " href="javascript:void(0)">
                    <span class="flex-auto">直推会员</span>
                    <span class="num" style="margin-right:1rem"><?php echo ($user["num"]); ?></span>
                </a>
            </li>
            <li class="detail-list-li detail-list-1">
                <a class="flex icon-more" href="/Myinfo/my_team">
                    <span class="flex-auto">我的团队</span>
                    <!--<span class="num">{$zxiaji}</span>-->
                </a>
            </li>
            <li class="detail-list-li detail-list-2">
                <?php switch($user["is_distribution"]): case "1": ?><a class="flex icon-more" href="javascript:void(0)">
                            <span class="flex-auto">申请分销商</span>
                            <span class="num">已是分销商</span>
                        </a><?php break;?>
                    <?php case "2": ?><a class="icon-more" href="/Myinfo/apply_distribution" >
                            <span>申请分销商</span>
                        </a><?php break;?>
                    <?php case "3": ?><a class="flex icon-more" href="javascript:void(0)">
                            <span class="flex-auto">申请分销商</span>
                            <span class="num">待审核...</span>
                        </a><?php break;?>
                    <?php case "4": ?><a class="flex icon-more" href="/Myinfo/apply_distribution">
                            <span class="flex-auto">申请分销商</span>
                            <span class="num">已被驳回</span>
                        </a><?php break; endswitch;?>
            </li>
            <li class="detail-list-li detail-list-6">
                <a class="icon-more" href="/Myinfo/my_qrcode">
                    <span>我的二维码</span>
                </a>
            </li>

            <li class="detail-list-li detail-list-4">
                <?php switch($user["is_cert"]): case "1": ?><a class="flex icon-more" href="javascript:void(0)">
                            <span class="flex-auto">我的认证</span>
                            <span class="num">已认证</span>
                        </a><?php break;?>
                    <?php case "2": ?><a class="icon-more" href="/Myinfo/my_cert" >
                            <span>我的认证</span>
                        </a><?php break;?>
                    <?php case "3": ?><a class="flex icon-more" href="javascript:void(0)">
                            <span class="flex-auto">我的认证</span>
                            <span class="num">待审核...</span></a><?php break;?>
                    <?php case "4": ?><a class="flex icon-more" href="/Myinfo/my_cert">
                            <span class="flex-auto">我的认证</span>
                            <span class="num">已被驳回</span>
                        </a><?php break; endswitch;?>

            </li>

            <!--<li class="detail-list-li detail-list-6">-->
                <!--<a class="icon-more" href="/Myinfo/shopping_record">-->
                    <!--<span>购买记录</span>-->
                <!--</a>-->
            <!--</li>-->
            <li class="detail-list-li detail-list-6">
                <a class="icon-more" href="/Myinfo/my_income">
                    <span>我的收益</span>
                </a>
            </li>
            <li class="detail-list-li detail-list-3">
                <a class="icon-more" href="/Myinfo/apply_exchange">
                    <span>申请兑换</span>
                </a>
            </li>
            <li class="detail-list-li detail-list-5">
                <a class="icon-more"  href="/Myinfo/address_list">
                    <span>收货地址管理</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--底部-->
<footer class="bot-tab">
	<ul class="bot-tab-list">
		<li class="active bot-tab-new"><a href="/Shop/shop"><img src="/Public/wx/apla/img/1_03.png"></a></li>
		<li class="active bot-tab-new "><a href="/Goods/goods_show"><img src="/Public/wx/apla/img/1_05.png"></a></li>
		<li class="active bot-tab-new "><a href="/Cart/mycart"><img src="/Public/wx/apla/img/1_07.png"></a></li>
		<li class="active bot-tab-new "><a href="/Myinfo/myinfo"><img src="/Public/wx/apla/img/1_09.png"></a></li>
	</ul>
</footer>
<!---->

</body>
</html>