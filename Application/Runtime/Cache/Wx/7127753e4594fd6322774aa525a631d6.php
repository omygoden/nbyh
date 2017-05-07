<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>身份认证</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/add_address.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/dialog.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/mobile-select-area.css">
    <script src="/Public/wx/apla/js/jquery-1.min.js"></script>
    <script src="/Public/wx/apla/js/dialog.js"></script>
    <script src="/Public/wx/apla/js/mobile-select-area.js"></script>
    <script type="text/javascript" src="/Public/wx/js/ajaxfileupload.js"></script>
</head>
<body>

<header class="flex header">
    <i class="back" onclick="javascript:history.go(-1);"></i>
    <h1 class="flex-auto">我的认证</h1>
    <i></i>
</header>
<div class="main" style="margin-top:5rem">
    <form method="post" enctype="multipart/form-data">
    <ul>
        <li class="flex list">
            <div class="left">姓名：</div>
            <div class="right flex-auto"><input style="line-height: 2rem" value="<?php echo ($msg["name"]); ?>" type="text" id="name"></div>
        </li>
        <li class="flex list">
            <div class="left">手机号码：</div>
            <div class="right flex-auto"><input style="line-height: 2rem" type="tel" id="mobile" value="<?php echo ($msg["mobile"]); ?>"></div>
        </li>
        <li class="flex list more">
            <input type="file" style="display:none" name="uploadimg" onchange="uploadimgs()" id="uploadimg">
            <input type="hidden" id="img" value="<?php echo ($msg["img"]); ?>">
            <div ><label for="uploadimg"><div class="button" style="margin:3px 0;width:100%;border-radius:0.5rem">上传身份证照</div></label></div>
            <!--<div class="right icon-more flex-auto flex">-->
                <!--<input type="text" id="txt_area" class="flex-auto "/>-->
                <!--<input type="hidden" id="hd_area"/>-->
            <!--</div>-->
        </li>
        <li class="flex list" id="newimg">
            <!--<div class="left">详细地址：</div>-->
            <!--<div class="right flex-auto">-->
                <!--<textarea></textarea>-->
            <!--</div>-->
            <?php if($msg['img'] != ''): ?><img  style="width:15rem" src="<?php echo ($msg["img"]); ?>" id="imgs"><?php endif; ?>
        </li>
    </ul>
        </form>
</div>
<div class="button" onclick="sub()">保存</div>

<script>
//    var selectArea = new MobileSelectArea();
//    selectArea.init({trigger: '#txt_area', value: $('#hd_area').val(), data: '/Public/wx/apla/js/data.json'});
//    $('.ui-dialog-mask').click(function () {
//        console.log(1)
//    })
    function sub(){
        var to_url='/Myinfo/myinfo';
        var name=$('#name').val();
        var mobile=$('#mobile').val();
        var img=$('#img').val();
        var reg = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|17[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
        var r = mobile.match(reg);
        if (r == null) {
            alert('请输入正确的手机号！');
            return false;
        }
        if(name=='' || mobile==''){
            alert('姓名或者手机号不能为空');
            return false;
        }
        if(img==''){
            alert('身份证照片不能为空');
            return false;
        }
        $.post('/Myinfo/sub_cert',{name:name,mobile:mobile,img:img},function(data){
            if(data.code=='1001'){
                window.location.href=to_url;
            }else{
                alert(data.result);
            }
        });
    }

    function uploadimgs(){
        var tourl='/Mypublic/uploadimg';
        $.ajaxFileUpload({
            url:tourl,//需要链接到服务器地址
            secureuri:false,
            fileElementId:'uploadimg',//文件选择框的id属性file的id
            dataType:'json',
            success:function(data){
    //                alert(321);
                    console.log(data);
                if(data.code=='1001'){
                    var html='<img  style="width:15rem" src="'+data.result+'" id="imgs">';
                    $('#newimg').html(html);
                    $('#img').val(data.result);
                }else{
                    if(data.status=='0'){
                        layer.alert(data.info);
                    }else{
                        layer.alert(data.result);
                    }
                }
            }
        });
    }

</script>
</body>
</html>