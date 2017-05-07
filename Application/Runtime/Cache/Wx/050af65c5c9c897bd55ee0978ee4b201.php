<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="/Public/wx/Static/css/foods.css?t=333" rel="stylesheet"
	type="text/css">
	<link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
	<!--<link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/user.css">-->
<script type="text/javascript" src="/Public/wx/Static/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/wx/Static/js/wemall.js?14115"></script>
<script type="text/javascript" src="/Public/wx/Static/js/alert.js"></script>

</head>
<body class="sanckbg mode_webapp">
<header class="flex header">
	<i class="back" onclick="javascript:history.go(-1);"></i>
	<h1 class="flex-auto">我的团队</h1>
	<i></i>
</header>
	<div id="member-container" style="display: block;margin-top:5rem">

		<div class="div_header" style="padding-bottom:1.5rem">
			<span style='float:left;margin-left:10px;margin-right:10px;'>
			<img src='<?php echo ($user["headimg"]); ?>' width='70px;' height='70px;'>
			</span>
			<span class="header_right">
				<div class="header_l_di" style="display:flex">
					<span style="width:50%">昵称：<?php echo ($user["nickname"]); ?></span>&nbsp;&nbsp;<span>族长：<?php echo ($user["is_start"]); ?></span>
				</div>
				<div style="display:flex"><span style="width:50%">会员ID：<?php echo ($user["memberid"]); ?></span>&nbsp;&nbsp;<span>直推会员：<?php echo ($user["direct_user"]); ?></span></div>
				<div style="display:flex"><span style="width:50%">团队积分：<?php echo ($user["all_score"]); ?></span>&nbsp;&nbsp;<span>会员：<?php echo ($user["all_user_num"]); ?></span></div>

			</span>
		</div>
		
		<div class="div_table" style="margin-bottom: 1rem;">
		<table  style='height:35px;text-align:center;border:0px' border=0>
			<tr style="border:0px"  border=0>
				<td style="background-color:#e66c19;cursor:pointer" class="choice" choice="1">今日新增：<?php if($user['today_user_num'] != ''): echo ($user["today_user_num"]); else: ?>0<?php endif; ?></td>
				<td style="border-left:1px solid #777;background-color:#777;cursor:pointer" class="choice" choice="2">历史：<?php if($user['all_user_num'] != ''): echo ($user["all_user_num"]); else: ?>0<?php endif; ?></td>
			</tr>
		</table>
		</div>

		<div id="today" style="display:inline">
		<?php if(is_array($user["today_user"])): foreach($user["today_user"] as $key=>$today_user): ?><div class="" style="border: 1px solid #cccccc;">
			<span >
			<div class="header_l_di" style="display:flex">
				<span style="width:20%"><?php echo ($today_user["nickname"]); ?></span>&nbsp;&nbsp;<span style="width:35%">ID:<?php echo ($today_user["memberid"]); ?></span><span style="width:45%">加入时间：<?php echo (date('Y-m-d',$today_user["ctime"])); ?></span>
			</div>
			<div style="display:flex"><span style="width:50%">团队：<?php echo ($today_user["count"]); ?>人</span>&nbsp;&nbsp;<span>团队积分：<?php echo ($today_user["score"]); ?></span></div>
			</span>
		</div><?php endforeach; endif; ?>
		</div>

		<div id="all" style="display:none">
			<?php if(is_array($user["all_user"])): foreach($user["all_user"] as $key=>$all_user): ?><div class="" style="border: 1px solid #cccccc;">
			<span >
			<div class="header_l_di" style="display:flex">
				<span style="width:20%"><?php echo ($all_user["nickname"]); ?></span>&nbsp;&nbsp;<span style="width:35%">ID:<?php echo ($all_user["memberid"]); ?></span><span style="width:45%">加入时间：<?php echo (date('Y-m-d',$all_user["ctime"])); ?></span>
			</div>
			<div style="display:flex"><span style="width:50%">团队：<?php echo ($all_user["count"]); ?>人</span>&nbsp;&nbsp;<span>团队积分：<?php echo ($all_user["score"]); ?></span></div>
			</span>
				</div><?php endforeach; endif; ?>
		</div>
		
		<!--<div class="" style="border: 1px solid #cccccc;">-->
			<!--<span >-->
			<!--<div class="header_l_di" style="display:flex">-->
				<!--<span style="width:20%">昵称：<?php echo ($user["nickname"]); ?></span>&nbsp;&nbsp;<span style="width:30%">族长：<?php echo ($user["is_start"]); ?></span><span style="width:50%">会员ID：<?php echo ($user["memberid"]); ?></span>-->
			<!--</div>-->
			<!--<div style="display:flex"><span style="width:50%">会员ID：<?php echo ($user["memberid"]); ?></span>&nbsp;&nbsp;<span>直推会员：<?php echo ($user["direct_user"]); ?></span></div>-->
			<!--<div style="display:flex"><span style="width:50%">团队积分：<?php echo ($user["all_score"]); ?></span>&nbsp;&nbsp;<span>会员：<?php echo ($user["all_user_num"]); ?></span></div>-->
			<!--</span>-->
		<!--</div>-->
</div>
	<footer class="bot-tab">
	<ul class="bot-tab-list">
		<li class="active bot-tab-new"><a href="/Shop/shop"><img src="/Public/wx/apla/img/1_03.png"></a></li>
		<li class="active bot-tab-new "><a href="/Goods/goods_show"><img src="/Public/wx/apla/img/1_05.png"></a></li>
		<li class="active bot-tab-new "><a href="/Cart/mycart"><img src="/Public/wx/apla/img/1_07.png"></a></li>
		<li class="active bot-tab-new "><a href="/Myinfo/myinfo"><img src="/Public/wx/apla/img/1_09.png"></a></li>
	</ul>
</footer>
	<script>
	window.onload=function(){
		if($_GET['page_type']=='order')
		{
			user();
		}
	}
	$('.choice').on('click',function(){
		var type=$(this).attr('choice');
		if(type=='1'){
			$('#today').css('display','inline');
			$('#all').css('display','none');
		}else{
			$('#today').css('display','none');
			$('#all').css('display','inline');
		}
	});
	</script>
</body>
</html>