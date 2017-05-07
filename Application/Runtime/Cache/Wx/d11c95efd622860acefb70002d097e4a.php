<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>添加地址</title>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/add_address.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/dialog.css">
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/mobile-select-area.css">
    <script src="/Public/wx/apla/js/jquery-1.min.js"></script>
    <script src="/Public/wx/apla/js/dialog.js"></script>
    <script src="/Public/wx/apla/js/mobile-select-area.js"></script>
</head>
<style>
    select{
        width:30%;
        margin:0 3px
    }
</style>
<header class="flex header">
    <i class="back" onclick="javascript:history.go(-1);"></i>
    <h1 class="flex-auto">
        <?php if(!empty($_GET['aid'])){echo '修改地址';}else{echo '添加地址';}?>
    </h1>
    <i></i>
</header>
<body>

<div class="main" style="margin-top:5rem">
    <ul>
        <li class="flex list">
            <div class="left">收货人姓名：</div>
            <div class="right flex-auto"><input style="line-height: 2rem" type="text" id="name" value="<?php echo ($address["name"]); ?>"></div>
        </li>
        <li class="flex list">
            <div class="left">手机号码：</div>
            <div class="right flex-auto"><input style="line-height: 2rem" type="tel" id="mobile" value="<?php echo ($address["mobile"]); ?>"></div>
        </li>
        <li class="flex list more">
            <div class="left">省市区：</div>
            <div class="right  flex-auto flex">
                <select id="top" onchange="get_next_area(this.value,'top')">
                    <?php if(is_array($address["top_area"])): foreach($address["top_area"] as $key=>$top): ?><option value="<?php echo ($top["id"]); ?>" <?php if($top['id'] == $area['0']): ?>selected<?php endif; ?> ><?php echo ($top["name"]); ?></option><?php endforeach; endif; ?>
                </select>
                <select id="next" onchange="get_next_area(this.value,'next')">
                    <?php if(is_array($address["next_area"])): foreach($address["next_area"] as $key=>$next): ?><option value="<?php echo ($next["id"]); ?>" <?php if($next['id'] == $area['1']): ?>selected<?php endif; ?> ><?php echo ($next["name"]); ?></option><?php endforeach; endif; ?>
                </select>
                <select id="last" style="line-height: 2rem">
                    <?php if(is_array($address["last_area"])): foreach($address["last_area"] as $key=>$last): ?><option value="<?php echo ($last["id"]); ?>" <?php if($last['id'] == $area['2']): ?>selected<?php endif; ?> ><?php echo ($last["name"]); ?></option><?php endforeach; endif; ?>
                </select>
                
                <!--<input type="text" id="txt_area" class="flex-auto "/>-->
            </div>
        </li>
        <li class="flex list">
            <div class="left">详细地址：</div>
            <div class="right flex-auto">
                <textarea id="detail"><?php echo ($address["detail"]); ?></textarea>
            </div>
        </li>
    </ul>
</div>
<div class="button" onclick="sub()">保存</div>
<script>
//    var selectArea = new MobileSelectArea();
//    selectArea.init({trigger: '#txt_area', value: $('#hd_area').val(), data: '/Public/wx/apla/js/data.json'});
//    $('.ui-dialog-mask').click(function () {
//        console.log(1)
//    })
    function get_next_area(pid,position){
        $.post('/Mypublic/get_next_area',{pid:pid},function(data){
            if(data.code=='1001'){
                var html='<option ></option>';
                $(data.result).each(function(k,v){
                    html+='<option value="'+ v.id+'" >'+ v.name+'</option>';
                });
                if(position=='top'){
                    $('#next').html(html);
                }else{
                    $('#last').html(html);
                }

            }else{
                alert(data.result);
            }
        })
    }

    function sub(){
        var to='<?php echo ($_GET['to']); ?>';
        var to_url='/Myinfo/address_list';
        var aid="<?php echo $_GET['aid'];?>";
        var name=$('#name').val();
        var mobile=$('#mobile').val();
        var top=$('#top').val();
        var next=$('#next').val();
        var last=$('#last').val();
        var detail=$('#detail').val();
        var reg = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|17[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
        var r = mobile.match(reg);
//        if (r == null) {
//            alert('请输入正确的手机号！');
//            return false;
//        }
//        if(name=='' || mobile==''){
//            alert('姓名或者手机号不能为空');
//            return false;
//        }
//        if(top=='' || next=='' || last=='' || detail==''){
//            alert('请将地址补充完整');
//            return false;
//        }
        var area=top+','+next+','+last;
        var ourl='<?php echo ($ourl); ?>';
        $.post('/Myinfo/sub_add_address',{to:to,aid:aid,name:name,mobile:mobile,area:area,detail:detail,ourl:ourl},function(data){
            console.log(data);
            if(data.code=='1001'){
//                window.location.href=to_url;
                if(ourl!=''){
                    window.location.href=ourl;
                }else{
                    window.location.replace(document.referrer);
                }
            }else{
                alert(data.result);
            }
        });
    }
</script>
</body>
</html>