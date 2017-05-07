<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>商品详情</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/goods_index.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/animate.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/swiper.min.css">
    <script src="/Public/admin/upload_imgs/jquery.js"></script>
    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>


    <script src="/Public/wx/apla/js/swiper.min.js"></script>
</head>
<link rel="stylesheet" type="text/css" href="/Public/wx/shangcheng/css/style.css"/>
<header class="top-header fixed-header" style="position:fixed;padding-top:0">
    <a class="icona" href="javascript:history.go(-1)">
        <img src="/Public/wx/shangcheng/images/left.png"/>
    </a>
    <h3>商品详情</h3>

    <a class="text-top">
    </a>
</header>

<body>
<div class="wrapper" style="padding-top:5rem">
    <div class="content header-con">
        <div class="goods-detail">
            <!--<img style="height:200px" src="<?php echo ($goods["title_img"]); ?>">-->

            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if(is_array($goods["detail_imgs"])): foreach($goods["detail_imgs"] as $key=>$imgs): ?><div class="swiper-slide">  <img style="height:200px;width:100%" src="<?php echo ($imgs["img"]); ?>"></div><?php endforeach; endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>



            <div class=" flex header-detail">
                <div class="left flex-auto title">
                    <p class="con-title"><span class="text-1"><?php echo ($goods["name"]); ?></span></p>
                    <p class="hint"><?php echo ($goods["description"]); ?></p>
                    <p class="price">&yen;<?php echo ($goods["price"]); ?></p>
                </div>
                <div class="right">
                    <div class="button buy" style="background: #c8b48f">加入购物车</div>
                    <div class="button buy" style="background:#202227;!important">购买</div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="goods-detail">
            <?php echo ($goods["detail"]); ?>
        </div>
    </div>
</div>
<!--底部导航-->
<!--<footer class="flex footer-button">-->
<!--<span class="flex-auto shop" id="shop">加入购物车</span>-->
<!--<a href="#" class="flex-auto buy">立即购买</a>-->
<!--</footer>-->
<div class="alert-choose" id="alert" >
    <div class="alert-main flex">
        <div class="alert-top">
            <div class="left">
                <img src="<?php echo ($goods["title_img"]); ?>">
            </div>
            <div class="right">
                <input type="hidden" value="{$data.id}" id="goods_id">
                <p><?php echo ($goods["name"]); ?></p>
                <p class="price-detail flex">
                    <span class="flex-auto ">
                        库存：<strong class="price" id="stock"><?php echo $goods['goods_size'][0]['num'] ?></strong>
                    </span>

                </p>
                <p class="price-detail flex">
                    <span class="flex-auto ">
                        价格：<strong class="price" id="price"><?php echo $goods['goods_size'][0]['price'] ?></strong>
                    </span>
                    <span>
                        <i class="icon icon-reduce" onclick="reduce_num()"></i>
                        <strong id="number">1</strong>
                        <i class="icon icon-add" onclick="add_num()"></i>
                    </span>
                </p>
            </div>
        </div>
        <?php if($goods['goods_size'] != ''): ?><div class="alert-content flex-auto" style="margin-bottom:5rem">
            规格：<br>
            <!-- {$data.ggname1} -->
            <div class="radio-choose">
                    <?php if(is_array($goods["goods_size"])): foreach($goods["goods_size"] as $k=>$goods_size): ?><label><input class="radio-type" type="radio" onclick="choose_size('<?php echo ($goods_size["price"]); ?>','<?php echo ($goods_size["num"]); ?>')" name="radio_color" value="<?php echo ($goods_size["id"]); ?>" <?php if($k == '0'): ?>checked<?php endif; ?>><span
                            class="radio-name"><?php echo ($goods_size["name"]); ?></span></label><?php endforeach; endif; ?>
                <!--<label><input class="radio-type" type="radio" name="radio_color" value="2" checked><span-->
                        <!--class="radio-name">蓝色</span></label>-->
            </div>
        </div><?php endif; ?>
        <div class="alert-footer footer-button">
            <div class="flex">
                <span class="flex-auto shop" id="cart" onclick="add_cart()">加入购物车</span>
                <a  class="flex-auto buy" id="shop" onclick="to_buy()">立即购买</a>
            </div>
        </div>
    </div>
</div>

<script src="/Public/wx/js/common.js"></script>
<script src="/Public/wx/js/zepto.js"></script>


<script>
    (function () {
        var mySwiper = new Swiper('.swiper-container', {
            autoplay: 5000,//可选选项，自动滑动
            pagination: '.swiper-pagination'
        });

        $('.buy').on('click',function(){
//            $('#alert').prop('class','alert-choose show animation');
            layer.msg('目前暂不支持购买。');
        });
        $('#alert').on('click',function(){
            if (event.target.id == 'alert') {
                $('#alert').prop('class','alert-choose animation animationed');
                setTimeout(function () {
                    $('#alert').prop('class','alert-choose');
                }, 300)
            }
        });


//        var number = document.getElementById("number");
//        $('.icon-reduce').on('click', function () {
//            number.innerText = number.innerText > 1 ? Number(number.innerText) - 1 : 1;
//        });
//        $('.icon-add').on('click', function () {
//            number.innerText = number.innerText ? Number(number.innerText) + 1 : 1;
//        });
    })();

    function add_cart(){
        var goods_id='<?php echo ($goods["id"]); ?>';
        var gs_id=$('input[type=radio]:checked').val();
        var price=$('#price').html();
        var num=$('#number').html();

        $.post('/Goods/add_cart',{gs_id:gs_id,price:price,num:num,goods_id:goods_id},function(data){
            if(data.code=='1001'){
                layer.msg(data.result,{time:2000});
            }else{
                layer.msg(data.result,{time:2000});
            }
        });
//        layer.alert('测试');
    }

    function to_buy(){
        var goods_id='<?php echo ($goods["id"]); ?>';
        var gs_id=$('input[type=radio]:checked').val();
        var price=$('#price').html();
        var num=$('#number').html();
        var stock=$('#stock').html();
        if(stock<=0){
            layer.msg('库存不足');
            return false;
        }
        window.location.href='/Orders/confirm_order?goods_id='+goods_id+'&gs_id='+gs_id+'&price='+price+'&num='+num;

//        layer.msg('测试',{
//            time:1000
//        });
    }
    function add_num(){
        var stock=parseInt($('#stock').html());
        var price=parseInt($('#price').html());
        var num=parseInt($('#number').html());
//        var price=price/num;
        var num=num+1;
        if(num>stock){
            layer.msg('库存不足',{time:2000});
            return false;
        }else{
//            $('#price').html(num*price);
            $('#number').html(num);
        }
    }
    function reduce_num(){
        var stock=parseInt($('#stock').html());
        var price=parseInt($('#price').html());
        var num=parseInt($('#number').html());
//        var price=price/num;
        var num=num-1;
        if(num<1){
            $('#number').html('1');
            $('#price').html(price);
        }else{
//            $('#price').html(num*price);
            $('#number').html(num);
        }
    }
    function choose_size(price,num){
        $('#stock').html(num);
        $('#price').html(price);
        $('#number').html('1');
    }

</script>

</body>
</html>