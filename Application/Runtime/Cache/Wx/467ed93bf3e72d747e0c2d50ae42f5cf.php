<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>购物车</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/shoppingCart.css">
       <script type="text/javascript" src="/Public/wx/Static/js/jquery.min.js"></script>
    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>
</head>
<body>

    <div class="main goods">
        <!--<p class="shop-name"><a href="#" class="btn-black">继续购物</a></p>-->
        <p class="shop-name flex">
            <span class="left shop-title flex-auto">APLA</span>
            <span class="right"><label class="input-checkbox flex">
            <input type="checkbox" name="goods_all" id="goods_all"><i class="icon icon-checkbox"></i>全选
        </label></span>
        </p>
        <ul class="goods-detail">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                    <div class="flex">
                        <?php if($list['status'] == '1'): ?><label class="input-checkbox flex">
                            <label class="input-checkbox flex">
                                <input type="checkbox" name="goods" value="<?php echo ($list["goods_id"]); ?>" cid="<?php echo ($list["id"]); ?>" id="goods<?php echo ($list["id"]); ?>" data-zhi="{$ct.id}"  data-yanse="{$ct.yanse}"  data-chima="{$ct.chima}"   data-price="<?php echo ($list["price"]); ?>"><i class="icon icon-checkbox"></i>
                                <input type="hidden" value="<?php echo ($list["price"]); ?>">
                                <input type="hidden" value="<?php echo ($list["gs_id"]); ?>">
                                <input type="hidden" id="nums<?php echo ($list["id"]); ?>" value="<?php echo ($list["num"]); ?>">
                            </label>
                        </label>
                        <?php else: ?>
                            <label class="input-checkbox flex" style="padding-top: 2.5rem;vertical-align: middle; color: red;">已下架</label><?php endif; ?>
                        <label class="left" for="goods_1">
                            <img style="height:7rem;width:10rem" src="<?php echo ($list["title_img"]); ?>">
                        </label>
                        <div class="right flex-auto">
                            <label for="goods_1" class="h3 text-1">
                                <?php echo ($list["name"]); ?>
                            </label>
                            <label class="goods-more" for="goods_1">规格：<?php echo ($list["sname"]); ?></label>
                            <p class="flex">
                                <span class="price flex-auto" id='allprice<?php echo ($list["id"]); ?>'>¥<?php echo ($list["price"]); ?></span>
                                <span class="choose-number">

                            <i class="icon icon-reduce" onclick="reduce('<?php echo ($list["id"]); ?>','<?php echo ($list["price"]); ?>')"></i>
                            <input type="number" disabled name="number" class="number" value="<?php echo ($list["num"]); ?>" id="num<?php echo ($list["id"]); ?>">
                            <i class="icon icon-add" onclick="add('<?php echo ($list["id"]); ?>','<?php echo ($list["price"]); ?>')"></i>
                        </span>
                            </p>
                        </div>
                    </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>

        </ul>
    </div>
    <p style="height:2rem"></p>
    <footer style="background:#fff" class="flex footer-count">总计：￥<span id="allprice">0</span>
        <span class="flex-auto text-right"><a href="javascript:;" onclick="settle()"><button class="btn-black">结算</button></a></span>
    </footer>

<footer class="bot-tab">
	<ul class="bot-tab-list">
		<li class="active bot-tab-new"><a href="/Shop/shop"><img src="/Public/wx/apla/img/1_03.png"></a></li>
		<li class="active bot-tab-new "><a href="/Goods/goods_show"><img src="/Public/wx/apla/img/1_05.png"></a></li>
		<li class="active bot-tab-new "><a href="/Cart/mycart"><img src="/Public/wx/apla/img/1_07.png"></a></li>
		<li class="active bot-tab-new "><a href="/Myinfo/myinfo"><img src="/Public/wx/apla/img/1_09.png"></a></li>
	</ul>
</footer>
<script src="/Public/wx/apla/js/zepto.js"></script>
<script>
	function reduce(id,price){
		var num=parseInt($('#num'+id).val());
		var num=num-1;
        if(num<1){
            var check=confirm('是否确定删除');
            if(!check){
                return false;
            }
        }
        $.post('/Cart/reduce_cart_num',{id:id},function(data){
            if(data.code=='1001'){
                layer.msg('-1',{time:1000});
                $('#num'+id).prop('value',num);
                $('#nums'+id).prop('value',num);
                if($('#goods'+id).prop('checked')){
                    var allprices=parseInt($('#allprice').html());
                    $('#allprice').html(allprices-price+'.00');
                }
                if(num<1){
                   window.location.reload();
                }
            }else{
                layer.msg(data.result,{time:2000});
            }
        });


	}
	function add(id,price){
		var num=parseInt($('#num'+id).val());
		var num=num+1;
        $.post('/Cart/add_cart_num',{id:id},function(data){
            if(data.code=='1001'){
                layer.msg('+1',{time:1000});
                $('#num'+id).prop('value',num);
                $('#nums'+id).prop('value',num);
                if($('#goods'+id).prop('checked')){
                    var allprices=parseInt($('#allprice').html());
                    $('#allprice').html(allprices+parseInt(price)+'.00');
                }
            }else{
                layer.msg(data.result,{time:2000});
            }
        });
	}

    function settle(){
        var goods_id='',price='',gs_id='',num='',cid;
        $('input[name=goods]:checked').each(function(k,v){
            if(k=='0'){
                cid=$(this).attr('cid');
                goods_id=$(this).val();
                price=$(this).next().next().val();
                gs_id=$(this).next().next().next().val();
                num=$(this).next().next().next().next().val();
            }else{
                cid=cid+'|'+$(this).attr('cid');
                goods_id=goods_id+'|'+$(this).val();
                price=price+'|'+$(this).next().next().val();
                gs_id=gs_id+'|'+$(this).next().next().next().val();
                num=num+'|'+$(this).next().next().next().next().val();
            }
        });
        if(cid==''){
            layer.msg('请先选中商品',{time:1000});
            return false;
        }
        $.post('/Cart/clear_cart',{cid:cid,goods_id:goods_id,price:price,gs_id:gs_id,num:num},function(data){
            if(data.code=='1001'){
                window.location.href='/Orders/confirm_order?goods_id='+goods_id+'&gs_id='+gs_id+'&price='+price+'&num='+num;
            }else{
                layer.msg(data.result,{time:2000});
            }
        });
    }

    //付款总额
    function allprice() {
        var pay_list = document.getElementsByName('goods');
        var allprice = 0;
        var is_all_choose=0;
        for (var i = 0, leg = pay_list.length; i < leg; i++) {
            if (!!pay_list[i].checked) {
                var num = document.getElementsByName('number')[i].value;
                var price =pay_list[i].dataset.price;
                allprice = Number(allprice) + Number(num) * Number(price)
            }else{
                is_all_choose=1;
            }
        }
        document.getElementById('allprice').innerText = allprice+'.00';
        if(is_all_choose==0){
            document.getElementById('goods_all').checked = true;
        }
    }
    $('.goods-detail input[type=checkbox]').on('change',function () {
        allprice();
    })

    //    全选
    $('#goods_all').on('change', function () {
        var list = document.getElementsByName('goods');
        //   var list=document.getElementById('form').goods;
        if (this.checked) {
            for(var i=0;i<list.length;i++){
                list[i].checked=true;
            }
        }else {
            for(var i=0;i<list.length;i++){
                list[i].checked=false;
            }
        }
        allprice();
    })
    //    取消全选
    $('.goods-detail .input-checkbox input').on('change',function () {
        if (!this.checked) {
            var googsAll=document.getElementById('goods_all');
            if (googsAll.checked==true){
                googsAll.checked=false;
            }
        }
    })
</script>
</body>
</html>