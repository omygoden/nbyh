<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>我的订单</title>
    <link href="/Public/wx/apla/css/common.css" rel="stylesheet"/>
    <link href="/Public/wx/apla/css/order.css" rel="stylesheet"/>
    <!--<script type="text/javascript" src="/Public/wx/Static/js/jquery.min.js"></script>-->
</head>
<style type="text/css">
    .orange-color{
        color:#ff3d3d
    }
    .btn-orange {
        border: 1px solid #ff3d3d;
    }
    .green-color{
        color:green;
    }
    .btn-green {
        border: 1px solid green;
    }
    .get_height:last-child{
        height:5rem
    }
    img{
        width:auto
    }
</style>
<body class="top-header">
<header class="header flex">
    <i class="back" onclick="window.location.href='/Myinfo/myinfo'"></i>
    <h1 class="flex-auto">我的订单</h1>
    <i></i>
</header>
<header class="head" style="clear: both;">
    <a <?php if($_GET['status']=='3'){ echo "class='active'";} ?> href="/Orders/my_orders/status/3">已完成</a>
    <a <?php if($_GET['status']=='0'){ echo "class='active'";} ?> href="/Orders/my_orders/status/0">待付款</a>
    <a <?php if($_GET['status']=='1'){ echo "class='active'";} ?> href="/Orders/my_orders/status/1">待发货</a>
    <a <?php if($_GET['status']=='2'){ echo "class='active'";} ?> href="/Orders/my_orders/status/2">待收货</a>
    <a <?php if($_GET['status']=='4'){ echo "class='active'";} ?> href="/Orders/my_orders/status/4">售后中</a>
</header>
<div class="ord-content">
    <ul class="orders">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lists): $mod = ($i % 2 );++$i;?><li class="ord-li">
            <div class="ord-num">
                <span>订单号:<?php echo ($lists["order_no"]); ?></span>
            </div>
            <div class="ord-num">
                <span>创建时间:<?php echo (date('Y:m:d H:i:s',$lists["ctime"])); ?></span>
            </div>
            <div class="goods-detail">
                <?php if(is_array($lists["goods"])): foreach($lists["goods"] as $key=>$goods): ?><div class="flex">
                    <div class="left">
                        <img style="height:7rem" src="<?php echo ($goods["title_img"]); ?>">
                    </div>
                    <div class="right flex-auto">
                        <h3 class="flex">
                            <span class="flex-auto text-1"><?php echo ($goods["name"]); ?>[<?php echo ($goods["sname"]); ?>]</span>
                            <span class="price">&yen;<?php echo ($goods["price"]); ?></span>
                        </h3>
                        <p>&nbsp;</p>
                        <p class="text-right">×<?php echo ($goods["num"]); ?></p>
                    </div>
                </div><?php endforeach; endif; ?>
            </div>
            <div class="ord-sum">
                <div>
                    <!--<span>代金券：{$vo.voucher}</span>-->
                    <span>运费：包邮</span>
                </div>
                <span class="red-color">总计：¥<?php echo ($lists["money"]); ?></span>
            </div>
            <?php if($_GET['status']=='4'){ ?>
            <div class="ord-sum">
                <div>
                    <!--<span>代金券：{$vo.voucher}</span>-->
                    <span>退款申请时间：</span>
                </div>
                <span class="red-color"><?php echo (date('Y-m-d H:i:s',$lists["rtime"])); ?></span>
            </div>
            <div class="ord-sum">
                <div>
                    <!--<span>代金券：{$vo.voucher}</span>-->
                    <span>审核结果：</span>
                </div>
                <span class="red-color"><?php if($lists['check_opinion'] != ''): echo ($lists["check_opinion"]); else: ?>待审核...<?php endif; ?></span>
            </div>
            <div class="ord-sum">
                <div>
                    <!--<span>代金券：{$vo.voucher}</span>-->
                    <span>审核时间：</span>
                </div>
                <span class="red-color"><?php if($lists['check_time'] != ''): echo (date('Y-m-d H:i:s',$lists["check_time"])); endif; ?></span>
            </div>
            <?php } ?>
            <div class="ord-action">
                <a href="/Goods/goods_show"><button class="red-color btn-red">再来一单</button></a>
                <!--<a href="./index.php?g=App&m=Newuser&a=kuaidi&sn={$vo.order_num}&uid={$Think.get.uid}"><button class="green-color btn-green">查看快递</button></a>-->

                <?php if($_GET['status']=='0'){ ?>
                <a href="/Orders/to_pay/order_no/<?php echo ($lists["order_no"]); ?>" ><button class="orange-color btn-orange">去支付</button></a>
                <?php } ?>
                <?php if($_GET['status']=='2'){ ?>
                <a href="javascript:void(0)" onclick="sure_accept('<?php echo ($lists["order_no"]); ?>')"><button class="orange-color btn-orange">确认收货</button></a>
                <?php } ?>
                <?php if($_GET['status']=='3'){ ?>
                <a href="/Orders/goods_refund/order_no/<?php echo ($lists["order_no"]); ?>"><button class="orange-color btn-orange">申请售后</button></a>
                <?php } ?>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
        <p  class="get_height" id="hh"></p>
    </ul>
</div>
<script src="/Public/wx/apla/js/jquery.min.js"></script>
<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>
<script>
//    var mySwiper = new Swiper('.swiper-container', {
//        autoplay: 5000,//可选选项，自动滑动
//        pagination: '.swiper-pagination'
//    });

    jQuery(document).ready(function(){
        status=1;//防止重复触发
        p='1';
//        document_height>=window_height+scroll  滚动条在底部时相等
        $(document).scroll( function() {
            var document_height=parseInt($(document).height());
            var window_height=parseInt($(window).height());
            var scroll=parseInt($(document).scrollTop());
            if(scroll>document_height-window_height-50){
                if(status==1){
                    status=2;
                    load_more();
                }
            }
        });

        function load_more(){
            var ostatus='<?php echo ($_GET['status']); ?>';
            $.post('/Orders/load_more',{p:p,status:ostatus},function(data){
//                console.log(p);
//                console.log(data);
                if(data.code=='1001'){
                    var html='';
                    $(data.result).each(function(k,v){
                        html+='<li class="ord-li">';
                        html+='<div class="ord-num">';
                        html+='<span>订单号:'+ v.order_no+'</span>';
                        html+='</div>';
                        html+='<div class="ord-num">';
                        html+='<span>创建时间:'+ v.ctime+'</span>';
                        html+='</div>';
                        html+='<div class="goods-detail">';
                        $(v.goods).each(function(kk,vv){
                            html+='<div class="flex">';
                            html+='<div class="left">';
                            html+='<img style="height:7rem" src="'+vv.title_img+'">';
                            html+='</div>';
                            html+='<div class="right flex-auto">';
                            html+='<h3 class="flex">';
                            html+='<span class="flex-auto text-1">'+ vv.name+'['+ vv.sname+']</span>';
                            html+='<span class="price">&yen;'+vv.price+'</span>';
                            html+='</h3>';
                            html+='<p>&nbsp;</p>';
                            html+='<p class="text-right">×'+vv.num+'</p>';
                            html+='</div>';
                            html+='</div>';
                        });
                        html+='</div>';
                        html+='<div class="ord-sum">';
                        html+='<div>';
                        html+='<span>运费：包邮</span>';
                        html+='</div>';
                        html+='<span class="red-color">总计：¥'+ v.money+'</span>';
                        html+='</div>';
                        if(ostatus=='4'){
                            html+='<div class="ord-sum">';
                            html+='<div>';
                            html+='<span>退款申请时间：</span>';
                            html+='</div>';
                            html+='<span class="red-color">'+ v.rtime+'</span>';
                            html+='</div>';
                            html+='<div class="ord-sum">';
                            html+='<div>';
                            html+='<span>审核结果：</span>';
                            html+='</div>';
                            html+='<span class="red-color">'+ v.check_opinion+'</span>';
                            html+='</div>';
                            html+='<div class="ord-sum">';
                            html+='<div>';
                            html+='<span>审核时间：</span>';
                            html+='</div>';
                            html+='<span class="red-color">'+ v.check_time+'</span></div>';
                        }
                        html+='<div class="ord-action">';
                        html+='<a href="/Goods/goods_show"><button class="red-color btn-red">再来一单</button></a>';
                        if(ostatus=='3'){
                            html+=' <a href="/Orders/goods_refund/order_no/'+ v.order_no+'"><button class="orange-color btn-orange">申请售后</button></a>';
                        }
                        if(ostatus=='0'){
                            html+='<a href="/Orders/to_pay/order_no/'+ v.order_no+'" ><button class="orange-color btn-orange">去支付</button></a>';
                        }
                        if(ostatus=='2'){
                            html+=' <a href="javascript:void(0)" onclick="sure_accept(\''+ v.order_no+'\')"><button class="orange-color btn-orange">确认收货</button></a>';
                        }
                        html+='</div>';
                        html+='</li>';
                    });

                    $('#hh').before(html);
                    p=p*1+1;
                    status=1;
                }else{
                    layer.msg(data.result,{time:2000})
                }
            });
        }

    });
function sure_accept(order_no){
    var sure=confirm('是否确定收货');
    if(!sure){
        return false;
    }
    $.post('/Orders/sure_accept',{order_no:order_no},function(data){
        if(data.code=='1001'){
            window.location.href='/Orders/my_orders/status/3';
        }else{
            layer.msg(data.result,{time:2000});
        }
    });
}
</script>
<footer class="bot-tab">
	<ul class="bot-tab-list">
		<li class="active bot-tab-new"><a href="/Shop/shop"><img src="/Public/wx/apla/img/1_03.png"></a></li>
		<li class="active bot-tab-new "><a href="/Goods/goods_show"><img src="/Public/wx/apla/img/1_05.png"></a></li>
		<li class="active bot-tab-new "><a href="/Cart/mycart"><img src="/Public/wx/apla/img/1_07.png"></a></li>
		<li class="active bot-tab-new "><a href="/Myinfo/myinfo"><img src="/Public/wx/apla/img/1_09.png"></a></li>
	</ul>
</footer>
</body>
</html>