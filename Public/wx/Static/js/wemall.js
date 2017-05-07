var tms = []; 
var day = []; 
var hour = []; 
var minute = []; 
var second = []; 
function takeCount() { 
setTimeout("takeCount()", 1000); 
for (var i = 0, j = tms.length; i < j; i++) { 
//计数减一 
tms[i] -= 1000; 
//计算时分秒 
var days = Math.floor(tms[i] / (1000 * 60 * 60 * 24)); 
var hours = Math.floor(tms[i] / (1000 * 60 * 60)) % 24; 
var minutes = Math.floor(tms[i] / (1000 * 60)) % 60; 
var seconds = Math.floor(tms[i] / 1000) % 60; 
if (days < 0) 
days = 0; 
if (hours < 0) 
hours = 0; 
if (minutes < 0) 
minutes = 0; 
if (seconds < 0) 
seconds = 0; 
//将时分秒插入到html中 
/*if(minutes==0 && seconds==0)
{
    $('#user').click();	
}*/
document.getElementById(minute[i]).innerHTML = minutes; 
document.getElementById(second[i]).innerHTML = seconds;

} 
} 

setTimeout("takeCount()", 1000); 


$(document).ready(function () {
	/*document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
		WeixinJSBridge.call('hideOptionMenu');
	});*/
//	推荐商品
//	$('.menu .ccbg dd').each(function(){
//		if( $(this).attr("menu") == '1' ){
//			$(this).show();
//		}else{
//			$(this).hide();
//		}
//	});
	
	$('#cart').on('click' , function (){
		$('#menu-container').hide();
		$('#cart-container').show();
		$('#user-container').hide();
		
		$(".footermenu ul li a").each(function(){
			$(this).attr("class","");
		});
		$(this).children("a").attr("class","active");
	});
	$('#home').on('click' , function (){
		$('#menu-container').show();
		$('#cart-container').hide();
		$('#user-container').hide();
		
		$(".footermenu ul li a").each(function(){
			$(this).attr("class","");
		});
		$(this).children("a").attr("class","active");
	});
	
	/*
	$('#ticket').on('click' , function (){
		$('#tx-container').hide();
		$('#member-container').hide();
		$('#user-container').hide();
		$('#ticket-container').show();
		$(".footermenu ul li a").each(function(){
			$(this).attr("class","");
		});
		$(this).children("a").attr("class","active");
	})
	*/
	$('#member').on('click' , function (){
		$('#tx-container').hide();
		$('#ticket-container').hide();
		$('#user-container').hide();
		$('#member-container').show();
		
		$(".footermenu ul li a").each(function(){
			$(this).attr("class","");
		});
		$(this).children("a").attr("class","active");
	})
	/*
	$('#tx').on('click' , function (){
		$('#ticket-container').hide();
		$('#member-container').hide();
		$('#user-container').hide();
		$('#tx-container').show();
		
		$(".footermenu ul li a").each(function(){
			$(this).attr("class","");
		});
		$(this).children("a").attr("class","active");
	})
	*/
	$('#user').on('click' , function (){
		$('#menu-container').hide();
		$('#cart-container').hide();
		$('#ticket-container').hide();
		$('#member-container').hide();
		$('#tx-container').hide();
		$('#user-container').show();

		$(".footermenu ul li a").each(function(){
			$(this).attr("class","");
		});
		$(this).children("a").attr("class","active");
 
		$.ajax({
			type : 'POST',
			//url : appurl+'/App/Index/getorders',
			url : appurl+'?g=App&m=Index&a=getorders',
			data : {
				uid : $_GET['uid'],
				state:$('#state').val()
			},
			success : function (response , status , xhr){
				$('#orderlistinsert').empty();
				if(response){
					var json = eval(response); 
					var html = '';
					var order_status = '';
					
					$.each(json, function (index, value) {
						var pay = '';
						var order = '';
						if (value.order_status == '0'){
							order_status = 'no';
							order = '未发货';
						}else if ( value.order_status == '1'){
							order_status = 'no'; 
							var confirm_url = appurl+'?g=App&m=Index&a=confirm_order&id='+value.orderid+'&uid='+$_GET['uid'];							 
							order = '<a href="'+confirm_url+'" style="color:red" onclick="return confirm(\'确认收货？\');">点击确认收货</a>';
						}else if ( value.order_status == '4' && value.pay_status == '0'){
								order_status = 'no';
								order = '已取消';
							}else if ( value.order_status == '4' && value.pay_status == '1'){
								order_status = 'no';
								order = '已退货';
							}else{
							order_status = 'ok';
							order = '已完成';
						}
						
						if (value.pay_status == '0'){
							pay_status = 'no';
							pay = '<a href="'+value.pay_url+'">去支付</a>';
						}else if ( value.pay_status == '1'){
							pay_status = 'ok';
							pay = '已支付';
						} 
					
						html += '<li style="border: 1px solid #d0d0d0;border-radius: 10px;margin-bottom:10px;background-color:#FFF;"><table><tr><td style="border-bottom:0px">订单编号:'+value.orderid+'</td></tr>';
						html += '<tr><td style="border-bottom:0px">订单金额:'+value.totalprice+'元</td></tr>';
						html += '<tr><td style="border-bottom:0px">订单时间:'+value.time+'</td></tr>';
						 
						html += '<tr><td style="border-bottom:0px">支付状态:<em class="'+pay_status+'">'+pay+'</em>';
						if (value.pay_status == '0')
						{
							html += '<a href="'+value.pay_url+'">(已经支付?)</a>';
						}
						html += '</td></tr>';
						if(value.order_status == '1')
						{
							html += '<tr><td style="border-bottom:0px">订单状态:<em class="ok">已发货</em>  <em class="'+order_status+'" style="background-color:#FFFF00;">'+order+'</em></td></tr>';
						}
						else
						{
							html += '<tr><td style="border-bottom:0px">订单状态:<em class="'+order_status+'">'+order+'</em></td></tr>';
						}
						
						html += '<tr><td style="border-bottom:0px">商品名称:'+value.goodname+'</td></tr>';
						html += '<tr><td style="border-bottom:0px">快递公司:'+value.order_info_name+'</td></tr>';
						html += '<tr><td style="border-bottom:0px">快递单号:'+value.order_info_num+'  <input type="button" value="快递追踪" onclick="zhuzong(\''+value.order_info_num+'\')" /></td></tr>';
						
						
						tms[tms.length] = eval(value.endtime); 
						minute[minute.length] = "m"+value.orderid; 
						second[second.length] = "s"+value.orderid;
						if (value.pay_status == '0' && value.order_status == '0')
						{
							
						    html += '<tr><td style="border-bottom:0px">剩余支付时间:<strong id="m'+value.orderid+'">0</strong>分<strong id="s'+value.orderid+'">0</strong>秒</li></td></tr>';
						 
						
						    html += '<tr><td height="35"><a style="border-radius:5px; background-color:#FF6600; padding:3px;color:#fff;" href="'+appurl+'?g=App&m=Index&a=quxiao_order&id='+value.orderid+'">取消订单</a></td></tr>';	
						}
						
						//if(value.order_info_name && value.order_info_num)
						//{
						
						//}
						html += '</table></li>';
					});
					
					 
					$('#orderlistinsert').append( html );					
				}

			},
			beforeSend : function(){
    			$('#page_tag_load').show();
	    	},
	    	complete : function(){
	    		$('#page_tag_load').hide();
	    	}
		});
	});
});

function user(state) {
	$('#state').val(state);
	$('#user').click();
}
function home() {
	$('#home').click();
}
function clearCache(){
	$('#ullist').find('li').remove();

	$('#home').click();

	$('.reduce').each(function () {
		$(this).children().css('background','');
	});
	$('#totalNum').html(0);
	$('#cartN2').html(0);
	$('#totalPrice').html(0);
}
function addProductN (wemallId){
 
	var jqueryid = wemallId.split('_')[0]+'_'+wemallId.split('_')[1];
	var price = parseFloat( wemallId.split('_')[2] );
	var productN = parseFloat( $('#'+jqueryid).find('.count').html() );
	$('#'+jqueryid).find('.count').html( productN + 1);

	var cartMenuN = parseFloat($('#cartN2').html())+1;
	$('#totalNum').html( cartMenuN );
	$('#cartN2').html( cartMenuN );
	 
	var totalPrice = parseFloat($('#totalPrice').html())+ parseFloat(price);
	 
	$('#totalPrice').html( totalPrice.toFixed(2) );
	
	var totalLirun = parseFloat($('#totalLirun').html())+ parseFloat(price);
	$('#totalLirun').html( totalLirun.toFixed(2) );
 
	var mnum = eval($('#'+jqueryid).find('.count').html());
	var num2 = eval($('#kucun').html());
	 
	if(mnum > num2)
	{
	    alert('库存不足');
		$('#'+jqueryid).find('.count').html(0);
		return false;	
	}
}
function reduceProductN ( wemallId ){
	var price = parseFloat( wemallId.split('_')[2] );
	var jqueryid = wemallId.split('_')[0]+'_'+wemallId.split('_')[1];
	var reduceProductN = parseFloat( $('#'+jqueryid).find('.count').html() );
	if ( reduceProductN == 1) {
		return false;
	}
	
	$('#'+jqueryid).find('.count').html( reduceProductN - 1);
	
	var cartMenuN = parseFloat($('#cartN2').html())-1;
	$('#totalNum').html( cartMenuN );
	$('#cartN2').html( cartMenuN );
 
	var totalPrice = parseFloat($('#totalPrice').html())- parseFloat(price);
	$('#totalPrice').html( totalPrice.toFixed(2) );
	var totalLirun = parseFloat($('#totalLirun').html())- parseFloat(price);
	$('#totalLirun').html( totalLirun.toFixed(2) );
 
	var mnum = eval($('#'+jqueryid).find('.count').html());
	var num2 = eval($('#kucun').html());
	if(mnum > num2)
	{
	    alert('库存不足');
		$('#'+jqueryid).find('.count').html(0);
		return false;	
	}
}
function doProduct (id , name , price,lirun) {
	var bgcolor = $('#'+id).children().css('background-color').colorHex().toUpperCase();
	$('#'+id).children().css('background-color','#D00A0A');

		var cartMenuN = 1;
		$('#totalNum').html( cartMenuN );
		$('#cartN2').html( cartMenuN );

		var totalPrice = parseFloat(price);
		$('#totalPrice').html( totalPrice.toFixed(2) );
		var totalLirun = parseFloat(lirun);
		$('#totalLirun').html( totalLirun.toFixed(2) );

		var wemallId = 'pili_'+id;
		/*var html = '<li class="ccbg2" id="'+wemallId+'"><div class="orderdish"><span name="id" style="display:none">'+id+'</span><span name="title">'+name+'</span><span class="price" id="v_0" style="display:none;">'+price+'</span><span style="display:none; class="price">元</span></div><div class="orderchange"><a href=javascript:addProductN("'+wemallId+'_'+price+'") class="increase"><b class="ico_increase">加一份</b></a><span class="count" id="num_1_499">1</span><a href=javascript:reduceProductN("'+wemallId+'_'+price+'") class="reduce"><b class="ico_reduce">减一份</b></a></div></li>';*/
		var html = '<li class="ccbg2" id="'+wemallId+'"><div class="orderdish"><span name="id" style="display:none">'+id+'</span><span name="title">'+name+'</span><span class="price" id="v_0" style="display:none;">'+price+'</span><span style="display:none; class="price">元</span></div><div class="orderchange"><a href=javascript:reduceProductN("'+wemallId+'_'+price+'") class="reduce"><b class="ico_reduce">减一份</b></a><span class="count" id="num_1_499">1</span><a href=javascript:addProductN("'+wemallId+'_'+price+'") class="increase"><b class="ico_increase">加一份</b></a></div></li>';
	
		$('#ullist').html(html);
}

function submitTxOrder () {

	if(!confirm("您确认需要提现吗？"))
	{
		return false;
	}

	$.ajax({
		type : 'POST',
		//url : appurl+'/App/Index/addtxorder',
		url : appurl+'?g=App&m=Index&a=addtxorder',
		data : {
			uid : $_GET['uid'],
			userData : $('form').serializeArray()
		},
		success : function (response , status , xhr) {
			if(response.error==true || response.error==false)
			{
				alert(response.msg);
				if(response.error==false){
					//window.location.reload();
				}
				return false;
			}
			else
			{
				alert("系统繁忙，请稍候再试");
				return false;
			}
		},
		beforeSend : function(){
			$('#tx-menu-shadow').show();
			$('#txshowcard').hide();
		},
		complete : function(){
			$('#tx-menu-shadow').hide();
			$('#txshowcard').show();
		}
	});
}

function zhuzong(id)
{
	if(!id)
	{
	    alert('不存在快递单号');	
	}
	else
	{
		$.ajax({
			type:'GET',
			url:appurl+'?g=App&m=Index&a=zhuizong&sn='+id,
			cache:false, 
			success:function(response) { 
				$('#kd').html(response); 
			},
			beforeSend : function(){
			$('#kd').html('数据查询中请稍候......'); 	
			$('#zzz').slideToggle();
			},
			complete : function(){
				//$('#zzz').slideToggle();		
			}
		});	
	     
	}
}

function submitOrder () {
	//获取订单信息
	var json = '';
	$('#ullist li').each(function () {
		var name = $(this).find('span[name=title]').html();
		var num = $(this).find('span[class=count]').html();
		var price = $(this).find('span[class=price]').html();
		var id = $(this).find('span[name=id]').html();
		 
		json += '{"name":"'+name+'","num":"'+num+'","price":"'+price+'","chima":"'+$('#chima').val()+'","yanse":"'+$('#yanse').val()+'","id":"'+id+'"},';
	
	});
	json = json.substring(0 , json.length-1);
	json = '['+json+']';
	
	if($('#totalPrice').html()<=0)
	{
		alert('请选择商品，商品总金额需大于0');
		return false;
	}
	
	var name = $('#name').val();
	var phone = $('#phone').val();
	var weixin = $('#weixin').val();
	var address = $('#address').val();
	var s_province = $('#s_province').val();
	var s_city = $('#s_city').val();
	var s_county = $('#s_county').val();
	var note = $('#note').val();
	
	//s_province s_city s_county
	
	if(name.length<=0)
	{
		alert('请输入名称');
		return false;
	}
	
	if(phone.length<=0)
	{
		alert('请输入电话');
		return false;
	}
	
	if(address.length<=0)
	{
		alert('请输入地址');
		return false;
	}
	
	if(s_province=='省份' || s_province=='')
	{
		alert('请选择省份');
		return false;
	}
	
	if(s_city=='城市' || s_city=='')
	{
		alert('请选择城市');
		return false;
	}
	
	if(s_county=='区域' || s_county=='')
	{
		alert('请选择区域');
		return false;
	}
	
	var syjf = $('#syjf').prop('checked'); 
	var sy;
	if(syjf == true)
	{
		sy = 1;
		if($('#jifen').val()<=0 || isNaN($('#jifen').val()))
		{
		    alert('您勾选了使用积分，积分请填大于0的整数');  $('#jifen').val(0);return false;	
		}
		if($('#jifen').val() > $('#dangqianjifen').val())
		{
	        alert('积分余额不足');  $('#jifen').val($('#dangqianjifen').val());return false;
		}
		if($('#jifen').val() > $('#dangqianjifen').val())
		{
	        alert('此商品最多可使用'+$('#maxjifen').val()+'积分'); $('#jifen').val($('#maxjifen').val()); return false;
		}
	}
	else
	{
		sy=0;
	}
		
	/*
	if(weixin=='')
	{
		alert('请填写微信号');
		return false;
	}
	*/
 
	$.ajax({
		type : 'POST',
		//url : appurl+'/App/Index/addorder',
		url : appurl+'?g=App&m=Index&a=addorder',
		data : {
			uid : $_GET['uid'],
			cartData : json,
			userData : $('form').serializeArray(),
			totalPrice : $('#totalPrice').html(),
			totalLirun : $('#totalLirun').html(),
			weixin:weixin,
			user_address:s_province+','+s_city+','+s_county+','+address,
			address:address,
			note:note,
			sy:sy,
			jifen:$('#jifen').val()
			
		},
		success : function (response , status , xhr) {
			
			if (response.msg) {
				alert(response.msg);return false;
			}
			
			$('#user').click();
			$('#ullist').find('li').remove();
			$('.reduce').each(function () {
				$(this).children().css('background','');
			});
			$('#totalNum').html(0);
			$('#cartN2').html( 0 );
			$('#totalPrice').html(0);
			$('#totalLirun').html(0);
			
			if (response) {
				window.location.href=response;return false;
			}
			
			$.ajax({
				type : 'POST',
				//url : appurl+'/App/Index/getorders',
				url : appurl+'?g=App&m=Index&a=getorders',
				data : {
					uid : $_GET['uid'],
					state:$('#state').val()
				},
				success : function (response , status , xhr){
					
					if(response){
						var json = eval(response); 
						var html = '';
						var order_status = '';
						
						$.each(json, function (index, value) {
							var pay = '';
							var order = '';
							if (value.order_status == '0'){
								order_status = 'no';
								order = '未发货';
							}else if ( value.order_status == '1'){
								order_status = 'no';
								//var confirm_url = appurl+'/App/Index/confirm_order?id='+value.orderid+'&uid='+$_GET['uid'];
								var confirm_url = appurl+'?g=App&m=Index&a=confirm_order&id='+value.orderid+'&uid='+$_GET['uid'];
								 
								order = '<span >已发货</span>  <a href="'+confirm_url+'" style="color:red" onclick="return confirm(\'确认收货？\');">点击确认收货</a>';
							}else if ( value.order_status == '4' && value.pay_status == '0'){
								order_status = 'no';
								order = '已取消';
							}else if ( value.order_status == '4' && value.pay_status == '1'){
								order_status = 'no';
								order = '已退货';
							}else{
								order_status = 'ok';
								order = '已完成';
							}
							
							if (value.pay_status == '0'){
								pay_status = 'no';
								pay = '<a href="'+value.pay_url+'">去支付</a>';
							}else if ( value.pay_status == '1'){
								pay_status = 'ok';
								pay = '已支付';
							}
							//html += '<tr><td>'+value.orderid+'</td><td class="cc">'+value.totalprice+'元</td><td class="cc"><em class="'+pay_status+'">'+pay+'</em></td><td class="cc"><em class="'+order_status+'">'+order+'</em></td></tr>';
						
							html += '<li style="border: 1px solid #d0d0d0;border-radius: 10px;margin-bottom:10px;background-color:#FFF;"><table><tr><td style="border-bottom:0px">订单编号:'+value.orderid+'</td></tr>';
							html += '<tr><td style="border-bottom:0px">订单金额:'+value.totalprice+'元</td></tr>';
							html += '<tr><td style="border-bottom:0px">订单时间:'+value.time+'</td></tr>';
							 
							html += '<tr><td style="border-bottom:0px">支付状态:<em class="'+pay_status+'">'+pay+'</em>';
							if (value.pay_status == '0')
							{
								html += '<a href="'+value.pay_url+'">(已经支付?)</a>';
							}
							html += '</td></tr>';
							if(value.order_status == '1')
							{
								html += '<tr><td style="border-bottom:0px">订单状态: <em class="ok">已发货</em> <em class="'+order_status+'">'+order+'</em></td></tr>';
							}
							else
							{
								html += '<tr><td style="border-bottom:0px">订单状态:<em class="'+order_status+'">'+order+'</em></td></tr>';
							}
							
						html += '<tr><td style="border-bottom:0px">商品名称:'+value.goodname+'</td></tr>';
						html += '<tr><td style="border-bottom:0px">快递公司:'+value.order_info_name+'</td></tr>';
						html += '<tr><td style="border-bottom:0px">快递单号:'+value.order_info_num+'  <input type="button" value="快递追踪" onclick="zhuzong(\''+value.order_info_num+'\')" /></td></tr>';
						
						tms[tms.length] = eval(value.endtime); 
						minute[minute.length] = "m"+value.orderid; 
						second[second.length] = "s"+value.orderid;
						if (value.pay_status == '0' && value.order_status == '0')
						{
							
						    html += '<tr><td style="border-bottom:0px">剩余支付时间:<strong id="m'+value.orderid+'">0</strong>分<strong id="s'+value.orderid+'">0</strong>秒</li></td></tr>';
						 
						
						    html += '<tr><td height="35"><a style="border-radius:5px; background-color:#FF6600; padding:3px;color:#fff;" href="'+appurl+'?g=App&m=Index&a=quxiao_order&id='+value.orderid+'">取消订单</a></td></tr>';	
						}
						
						//if(value.order_info_name && value.order_info_num)
						//{
						 
						//}
							html += '</table></li>';
						
						});
						$('#orderlistinsert').empty();
						$('#orderlistinsert').append( html );
					}
				},
				beforeSend : function(){
	    			$('#page_tag_load').show();
		    	},
		    	complete : function(){
		    		$('#page_tag_load').hide();
		    	}
			});
		},
		beforeSend : function(){
			$('#menu-shadow').show();
			$('#showcard').hide();
		},
		complete : function(){
			$('#menu-shadow').hide();
			$('#showcard').show();
		}
	});
	

}
var $_GET = (function(){
	var url = window.document.location.href.toString();
	var u = url.split("?");
	if(typeof(u[1]) == "string"){
		u = u[1].split("&");
		var get = {};
		for(var i in u){
			var j = u[i].split("=");
			get[j[0]] = j[1];
		}
		return get;
	} else {
		return {};
	}
})();
String.prototype.colorHex = function(){
	var that = this;
	if(/^(rgb|RGB)/.test(that)){
		var aColor = that.replace(/(?:\(|\)|rgb|RGB)*/g,"").split(",");
		var strHex = "#";
		for(var i=0; i<aColor.length; i++){
			var hex = Number(aColor[i]).toString(16);
			if(hex === "0"){
				hex += hex;	
			}
			strHex += hex;
		}
		if(strHex.length !== 7){
			strHex = that;	
		}
		return strHex;
	}else if(reg.test(that)){
		var aNum = that.replace(/#/,"").split("");
		if(aNum.length === 6){
			return that;	
		}else if(aNum.length === 3){
			var numHex = "#";
			for(var i=0; i<aNum.length; i+=1){
				numHex += (aNum[i]+aNum[i]);
			}
			return numHex;
		}
	}else{
		return that;	
	}
};
function showDetail(id){
	$.ajax({
		type : 'post',
		//url : appurl+'/App/Index/fetchgooddetail',
		url : appurl+'?g=App&m=Index&a=fetchgooddetail',
		data : {
			id : id,
		},
		success : function(response , status , xhr){
			$('#mcover').show();
			var json = eval(response);
			$('#detailpic').attr('src',rooturl+'/Public/Uploads/'+json.image);
			$('#detailtitle').html(json.title);
			$('#detailinfo').html(json.detail);
			
			$('#detailinfo img').click(function(){
				$('#cart').click();
			});		
		}
	});
}
function showMenu(){
	$("#menu").find("ul").toggle();
}
function toggleBar(){
	$(".footermenu ul li a").each(function(){
		$(this).attr("class","");
	});
	$(this).children("a").attr("class","active");
}
function showProducts(id) {
	$('.menu .ccbg dd').each(function(){
		if( $(this).attr("menu") == id ){
			$(this).show();
		}else{
			$(this).hide();
		}
	});
	$('#menu ul').hide();
}
function showAll() {
	$('.menu .ccbg dd').each(function(){
		$(this).show();
	});
	$('#menu ul').hide();
}