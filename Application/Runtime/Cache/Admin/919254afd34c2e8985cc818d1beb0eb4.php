<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 基本表单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="/Public/admin/favicon.ico"> <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/admin/css/animate.css" rel="stylesheet">
    <link href="/Public/admin/css/style.css?v=4.1.0" rel="stylesheet">

</head>
<style>
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
</div>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-sm-6" style="width:50%;left:25%">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加权限</h5>
                    </div>
                    <div class="ibox-content">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">顶级权限</label>
                                <div class="col-sm-8">
                                    <select class="form-control m-b" name="account" id="pid" >
                                        <option></option>
                                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><option value="<?php echo ($list["id"]); ?>"><?php echo str_repeat('--',($list['level']-1)*2); if($list['level'] != '1'): ?>→<?php endif; echo ($list["name"]); ?></option><?php endforeach; endif; ?>>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" >权限名称：</label>

                                <div class="col-sm-8">
                                    <input type="text" id="power_name" placeholder="权限名称" class="form-control">
                                    <!--<span class="help-block m-b-none">请输入您注册时所填的E-mail</span>-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">控制器或方法名：</label>
                                <div class="col-sm-8">
                                    <input type="text" id="actionname" placeholder="控制器或方法名" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <button class="btn btn-sm btn-info" type="submit" id="sub">添 加</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>



    <!-- 全局js -->
    <script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>

    <!-- 自定义js -->
    <script src="/Public/admin/js/content.js?v=1.0.0"></script>
    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
    <!-- iCheck -->
    <script src="/Public/admin/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
         $('#sub').on('click',function(){
             var pid=$('#pid').val();
             var name=$('#power_name').val();
             var actionname=$('#actionname').val();
             if(name==''){
                 layer.alert('权限名不能为空');
                 return false;
             }
             if(actionname==''){
                 layer.alert('方法名不能为空');
                 return false;
             }
             $.post('/indexadm.php/Power/add_power',{pid:pid,name:name,actionname:actionname},function(data){
                 if(data.code=='1001'){
                     window.location.reload();
                 }else{
                     if(data.status=='0'){
                         layer.alert(data.info);
                     }else{
                         layer.alert(data.result);
                     }
                 }
             });
         });
    </script>

    
    

</body>

</html>