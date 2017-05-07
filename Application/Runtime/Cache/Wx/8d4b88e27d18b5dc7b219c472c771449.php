<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>首页</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/index.css">


</head>
<style>
    .title-imgs {
        margin-left: 20%;
        margin-top: 14px;
        margin-bottom: 4px;
        font-size: 14px;
        color: #919191;
        width:100%;
    }
    .m-botAlert .m-botAlert-con {
        display: inline-block;
        max-width: 70%;
        padding: 0.2rem .3rem;
        border-radius: 4px;
        margin: 0 auto;
        background: #333;
        color: #fff;
        line-height:.4rem;
    }
    .get_height:last-child{
        height:5rem
    }
</style>
<body>
<div class="content" id="list">
    <!--轮播图-->
    <div class="swiper-container">
        <div class="swiper-wrapper">
                <?php if(is_array($top)): foreach($top as $key=>$top): ?><div class="swiper-slide">  <a href="/Goods/goods_detail/gid/<?php echo ($top["id"]); ?>"><img src="<?php echo ($top["title_img"]); ?>"></a></div><?php endforeach; endif; ?>
                <!--<div class="swiper-slide"> <img src="/Public/wx/apla/img/other/banner.png"></div>-->
                <!--<div class="swiper-slide"> <img src="/Public/wx/apla/img/other/banner.png"></div>-->
                <!--<div class="swiper-slide"> <img src="/Public/wx/apla/img/other/banner.png"></div>-->


        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!---->
    <div class="title-imgs" style="width:100%;margin:14px 0 4px 0"><img style="width:60%;margin-left:20%" src="/Public/wx/apla/img/bg_home&jieshao.png"></div>

    <div class="classify-wrapper">
        <?php if(!empty($goods[0]['title_img'])){ ?>
        <div class="left">
            <a href="/Goods/goods_detail/gid/<?php echo $goods[0]['id'];?>" class="classify">
                <img src="<?php echo $goods[0]['title_img'];?>">
            </a>
        </div>
    <?php } ?>
        <div class="right">
            <div class="right-wrapper">
                <?php if(!empty($goods[1]['title_img'])){ ?>
                <a href="/Goods/goods_detail/gid/<?php echo $goods[1]['id'];?>" class="classify" id="ff">
                    <img src="<?php echo $goods[1]['title_img'];?>">
                </a>
                <?php } ?>
                <?php if(!empty($goods[2]['title_img'])){ ?>
                <a href="/Goods/goods_detail/gid/<?php echo $goods[2]['id'];?>" class="classify">
                    <img src="<?php echo $goods[2]['title_img'];?>">
                </a>
                <?php } ?>
                <?php if(!empty($goods[3]['title_img'])){ ?>
                <a href="/Goods/goods_detail/gid/<?php echo $goods[3]['id'];?>" class="classify" >
                    <img src="<?php echo $goods[3]['title_img'];?>">
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <p  class="get_height" id="hh"></p>


</div>
<!--<span>加载完毕</span>-->
<!--浮动-->
<!--<div class="right-float">-->
    <!--<div class="fl-shoping new-shop"></div>-->
    <!--<a href="tel:1836778888" class="fl-tel"></a>-->
<!--</div>-->
<span id="test"></span>
<!--底部导航-->
<footer class="bot-tab">
	<ul class="bot-tab-list">
		<li class="active bot-tab-new"><a href="/Shop/shop"><img src="/Public/wx/apla/img/1_03.png"></a></li>
		<li class="active bot-tab-new "><a href="/Goods/goods_show"><img src="/Public/wx/apla/img/1_05.png"></a></li>
		<li class="active bot-tab-new "><a href="/Cart/mycart"><img src="/Public/wx/apla/img/1_07.png"></a></li>
		<li class="active bot-tab-new "><a href="/Myinfo/myinfo"><img src="/Public/wx/apla/img/1_09.png"></a></li>
	</ul>
</footer>
<!---->
<script src="/Public/wx/apla/js/jquery.min.js"></script>
<script src="/Public/wx/apla/js/swiper.min.js"></script>
<script src="/Public/wx/apla/js/common.js"></script>

<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>
<script>
    var mySwiper = new Swiper('.swiper-container', {
        autoplay: 5000,//可选选项，自动滑动
        pagination: '.swiper-pagination'
    });

    jQuery(document).ready(function(){
        status=1;//防止重复触发
        p='1';
//        document_height>=window_height+scroll  滚动条在底部时相等
        $(document).scroll( function() {
            var document_height=parseInt($(document).height());
            var window_height=parseInt($(window).height());
            var scroll=parseInt($(document).scrollTop());


//            document_height=document_height+(p-1)*(get_heigth1-get_height2);
//            console.log(document_height);
//            console.log($('.get_height:last').height());
//            console.log(window_height);
//            console.log(scroll);
//            layer.msg(get_heigth1-get_height2);
//            layer.msg(scroll);
            if(scroll>document_height-window_height-50){
                if(status==1){
                    status=2;
                    load_more();
                }
            }
        });

        function load_more(){
            $.post('/Shop/load_more',{p:p},function(data){
//                console.log(p);
                console.log(data);
                if(data.code=='1001'){
                    var data=data.result;
                    data0=data[0] || '';
                    data1=data[1] || '';
                    data2=data[2] || '';
                    data3=data[3] || '';
//                    console.log(data0.title_img);
                    var html='';
                    html+='<div class="classify-wrapper">';
                    html+='<div class="left">';
                    html+='<a href="/Goods/goods_detail/gid/'+data0.id+'" class="classify">';
                    html+='<img src="'+data0.title_img+'">';
                    html+='</a>';
                    html+='</div>';
                    html+='<div class="right">';
                    html+='<div class="right-wrapper">';
                    if(data1.id!=undefined){
                        html+='<a href="/Goods/goods_detail/gid/'+data1.id+'" class="classify">';
                        html+='<img src="'+data1.title_img+'">';
                        html+='</a>';
                    }
                    if(data2.id!=undefined){
                        html+='<a href="/Goods/goods_detail/gid/'+data2.id+'" class="classify">';
                        html+='<img src="'+data2.title_img+'">';
                        html+='</a>';
                    }
                    if(data3.id!=undefined){
                        html+='<a href="/Goods/goods_detail/gid/'+data3.id+'" class="classify" >';
                        html+='<img src="'+data3.title_img+'">';
                        html+='</a>';
                    }
                    html+='</div>';
                    html+='</div>';
                    html+='</div> <p  class="get_height" id="hh"></p>';
                    $('#list').append(html);
                    p=p*1+1;
                    status=1;
                }else{
                    layer.msg(data.result,{time:2000})
                }
            });
        }



    });
//    $(document).scroll( function() {
//        console.log($('.get_height:last').offset());
//    });
//    function test(){
//
//    }




</script>
</body>
</html>