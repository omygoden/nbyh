<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>商品列表</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/shopping.css">
</head>
<body>
<!--同选购商品-->
<div class="content" >
    <div class="flex-item" id="list">
        <?php if(is_array($list)): foreach($list as $key=>$list): ?><a class="shop-box item-2" href="/Goods/goods_detail/gid/<?php echo ($list["id"]); ?>">
            <div class="top">
                <img src="<?php echo ($list["title_img"]); ?>">
            </div>
            <div class="bottom">
                <p class="title">
                    <?php echo ($list["name"]); ?>
                </p>
                <p class="other">
                    <span class="left">&yen;<?php echo ($list["price"]); ?></span>
                    <span class="right shop" data-state="noShop"></span>
                </p>
            </div>
        </a><?php endforeach; endif; ?>
    </div>
</div>
<footer class="bot-tab">
	<ul class="bot-tab-list">
		<li class="active bot-tab-new"><a href="/Shop/shop"><img src="/Public/wx/apla/img/1_03.png"></a></li>
		<li class="active bot-tab-new "><a href="/Goods/goods_show"><img src="/Public/wx/apla/img/1_05.png"></a></li>
		<li class="active bot-tab-new "><a href="/Cart/mycart"><img src="/Public/wx/apla/img/1_07.png"></a></li>
		<li class="active bot-tab-new "><a href="/Myinfo/myinfo"><img src="/Public/wx/apla/img/1_09.png"></a></li>
	</ul>
</footer>
<script src="/Public/wx/apla/js/zepto.js"></script>
<script src="/Public/wx/apla/js/jquery.min.js"></script>
<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>
<script>
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
            $.post('/Goods/load_more',{p:p},function(data){
//                console.log(data);
                if(data.code=='1001'){
                    var html='';
                    $(data.result).each(function(k,v){
                        html+='<a class="shop-box item-2" href="/Goods/goods_detail/gid/'+ v.id+'">';
                        html+='<div class="top">';
                        html+='<img src="'+ v.title_img+'">';
                        html+='</div>';
                        html+='<div class="bottom">';
                        html+='<p class="title">'+ v.name+'</p>';
                        html+='<p class="other">';
                        html+='<span class="left">&yen;'+ v.price+'</span>';
                        html+='<span class="right shop" data-state="noShop"></span>';
                        html+='</p>';
                        html+='</div>';
                        html+='</a>';
                    });

                    $('#list').append(html);
                    p=p*1+1;
                    status=1;
                }else{
                    layer.msg(data.result,{time:2000})
                }
            });
        }



    });
</script>
</body>
</html>