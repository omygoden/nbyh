<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<title> - 登录</title>
	<meta name="keywords" content="">
	<meta name="description" content="">

	<link rel="shortcut icon" href="/Public/admin/favicon.ico"> <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
	<link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">

	<link href="/Public/admin/css/animate.css" rel="stylesheet">
	<link href="/Public/admin/css/style.css?v=4.1.0" rel="stylesheet">
	<!--[if lt IE 9]>
	<meta http-equiv="refresh" content="0;ie.html" />
	<![endif]-->
	<script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>
<!-- <style>
    #loading{
        /*height: 80px;*/
        width: 100%;
        position: absolute;
        /*top: 40%;*/
        margin-top: -35px;
        text-align: center;
    }
</style>
<div class="ibox-content" id="loading" style="background:none;z-index:-1">
    <div class="spiner-example">
        <div class="sk-spinner sk-spinner-three-bounce">
            <div class="sk-bounce1"></div>
            <div class="sk-bounce2"></div>
            <div class="sk-bounce3"></div>
        </div>
    </div>
</div> -->
<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
	<div>
		<div>

			<h1 class="logo-name" style="font-size:100px">NBYH</h1>

		</div>
		<h3>欢迎使用 诺布一号后台管理</h3>

		<form class="m-t" role="form" action="">
			<div class="form-group">
				<input type="text" id="phone" class="form-control" placeholder="手机号" required="">
			</div>
			<div class="form-group">
				<input type="password"  id="pwd"  class="form-control" placeholder="密码" required="">
			</div>
			<button type="button" id="login_btn" class="btn btn-primary block full-width m-b">登 录</button>


			<!--<p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a>
			</p>-->

		</form>
	</div>
</div>

<!-- 全局js -->
<script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>

<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<script>
//	var keyCode = event.keyCode?event.keyCode:event.which?event.which:event.charCode;
//	if(keyCodee==13){
//		var phone=$('#phone').val();
//		var pwd=$('#pwd').val();
//		var tourl="/indexadm.php/Index/index";
//		$.post('/indexadm.php/Login/get_login',{phone:phone,pwd:pwd},function(data){
//			if(data.code=='1001'){
////				layer.alert('登录成功',{title:'提示框',icon:1});
//				window.location.href=tourl;
//			}else{
//				layer.alert(data.result,{title:'提示框',icon:1});
//			}
//		});
//	}
	$('#login_btn').on('click', function(){
		var phone=$('#phone').val();
		var pwd=$('#pwd').val();
		var tourl="/indexadm.php/Index/index";
		$.post('/indexadm.php/Login/get_login',{phone:phone,pwd:pwd},function(data){
			if(data.code=='1001'){
//				layer.alert('登录成功',{title:'提示框',icon:1});
				window.location.href=tourl;
			}else{
				layer.alert(data.result,{title:'提示框',icon:1});
			}
		});
	})


</script>


</body>

</html>