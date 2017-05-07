<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>确认订单</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/goods_confirm.css">
    <link href="/Public/wx/Static/css/foods.css?t=333" rel="stylesheet"
          type="text/css">
    <script type="text/javascript" src="/Public/wx/Static/js/jquery.min.js"></script>

    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>
</head>
<body>
<header class="flex header">
    <i class="back" onclick="javascript:history.go(-1);"></i>
    <h1 class="flex-auto">确认订单</h1>
    <i></i>
</header>


<form method="post" action="/Orders/confirm_order?goods_id=7&amp;gs_id=19&amp;price=100.00&amp;num=1&amp;code=031FQVe218UqtL1vpMd21YGSe21FQVek&amp;state=123&amp;code=021y5RER0SB3Xb2pPSGR09s4FR0y5REW&amp;state=123&amp;code=041pKfXb2wUVER0upeVb212ZWb2pKfX0&amp;state=123">
<div class="main" style="margin-top: 57px;">
    <?php if($myaddress != ''): ?><a href="/Myinfo/address_list?to=1&ourl=<?php echo ($ourl); ?>" id="vv" class="address" >


            <p >
                <input type="hidden" value="<?php echo ($myaddress["id"]); ?>" id="address">
                收货人：<span class="user-name" id="name"><?php echo ($myaddress["name"]); ?></span>&nbsp&nbsp&nbsp
                联系电话：<span class="user-phone" id="mobile"><?php echo ($myaddress["mobile"]); ?></span>
            </p>
            <p class="address-detail text-1" id="area"><?php echo ($myaddress["all_area"]); ?>
            </p>
    </a>
        <?php else: ?>
            <a href="/Myinfo/add_address?to=1&ourl=<?php echo ($ourl); ?>"  id="vv" class="address"><p >您还未设置默认地址,请先进行设置</p></a><?php endif; ?>
</div>



<div class="main goods">
    <p class="shop-name icon-b">APLANOS</p>
    <div class="goods-detail">
            <?php if(is_array($all_goods)): foreach($all_goods as $key=>$goods): ?><a href="javascript:;" class="flex">
                <div class="left">
                    <img style="height:7rem;width:10rem" src="<?php echo ($goods["title_img"]); ?>">
                </div>
                <div class="right flex-auto">
                    <h3 class="flex">
                        <span class="flex-auto text-1"><?php echo ($goods["name"]); ?></span>
                        <span class="price" id="all_price">&yen;<?php echo ($goods["price"]); ?></span>
                    </h3>
                    <h3 class="flex">
                        <span class="flex-auto text-1">规格：<?php echo ($goods["sname"]); ?></span>
                    </h3>
                    <p>&nbsp;</p>
                    <p class="text-right" id="num">×<?php echo ($goods["num"]); ?></p>
                </div>
            </a><?php endforeach; endif; ?>
    </div>
</div>

<!--<div style="background-color:#CCC; height:10px; clear:both"></div>-->
<div style="background-color:#FFF">

    <div style="padding:10px; border-bottom:#CCC solid 1px;">买家留言：<input type="text" placeholder="选填" name="note2" id="remark" style="height:30px; border:none;"></div>

    <div style="padding:10px; border-bottom:#CCC solid 1px;">配送方式：<span style="float:right">快递 免邮</span></div>
<p style="height: 4rem"></p>



</div>

<footer class=" text-right footer">
    <!--<div class="wrapper">-->
    共<?php echo ($tol_num); ?>件商品 合计：<span class="price">&yen;<?php echo ($tol_price); ?></span><a onClick="commit_order()" class="btn-black">确认订单</a>
    <!--</div>-->
</footer>
</form>
<script>
    function commit_order(){
        var tol_price='<?php echo ($tol_price); ?>';
        var goods_id='<?php echo ($_GET['goods_id']); ?>';
        var gs_id='<?php echo ($_GET['gs_id']); ?>';
        var num='<?php echo ($_GET['num']); ?>';
        var name=$('#name').html();
        var mobile=$('#mobile').html();
        var area=$('#area').html();
//        var hash=$('input[name=__hash__]').val();
        if(name=='' || mobile=='' || area==''){
            layer.msg('请先补全地址',{time:2000});
            return false;
        }
        var remark=$('#remark').val();
        var address_id=$('#address').val();
//        alert(hash);
        $.post('/Orders/commit_order',{tol_price:tol_price,goods_id:goods_id,gs_id:gs_id,num:num,address_id:address_id,remark:remark},function(data){
            if(data.code=='1001'){
                window.location.href='/Orders/to_pay/order_no/'+data.result;
            }else{
                layer.msg(data.result,{time:1000});
            }
        });

    }
</script>
</div>

</body>
</html>