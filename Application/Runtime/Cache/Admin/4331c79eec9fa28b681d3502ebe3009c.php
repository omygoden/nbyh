<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> - 联系人</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/Public/admin/favicon.ico">
    <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
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
<body class="gray-bg contacts">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h2>管理员列表<a style="float:right" class="btn btn-primary" href="/indexadm.php/User/role_list">职位列表</a><a style="float:right;margin:0 1rem" class="btn btn-info" href="javascript:void(0);" data-toggle="modal" data-target="#myModal7" >添加管理员</a></h2>

                    <!--<div class="input-group">-->
                        <!--<form type="get" enctype="multipart/form-data" action="/indexadm.php/user/admin_list">-->
                        <!--<input type="text" placeholder="请输入手机号" name="content" id="content" class="input form-control">-->
                        <!--<div class="input-group-btn">-->
                            <!--<bottom style="height: 2.8rem;"  id="search" type="submit" class="btn btn btn-primary"><i class="fa fa-search"></i> 搜索</bottom>-->
                        <!--</div>-->
                        <!--</form>-->
                    <!--</div>-->
                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/user/admin_list" method="get">
                            <div class="form-group">
                                <!--<label>商品code</label>-->
                                <input type="text" id="content" placeholder="请输入手机号" name="content" class="form-control">
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" id="search"><strong>搜索</strong>
                                </button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr>
                            <th class="">编号</th>
                            <th>用户名</th>
                            <th class="">职位</th>
                            <th> 手机号</th>
                            <th class="">状态</th>
                            <th>创建时间</th>

                            <th class="client-status">操作</th>
                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                            <td class=""><?php echo ($list["id"]); ?></td>
                            <td><?php echo ($list["name"]); ?></td>
                            <td><?php echo ($list["rolename"]); ?></td>
                            <td><?php echo ($list["phone"]); ?></td>
                            <td>
                                <?php switch($list["status"]): case "1": ?>正常<?php break;?>
                                    <?php case "2": ?>已锁定<?php break; endswitch;?>
                            </td>
                            <td class=""><?php echo ($list["ctime"]); ?></td>
                            <td class="client-status">

                                <a class="btn btn-success btn-rounded modify" aid="<?php echo ($list["id"]); ?>" rid="<?php echo ($list["rid"]); ?>" href="javascript:void(0);" data-toggle="modal" data-target="#myModal6">修改职位</a>
                                <a class="btn btn-info btn-rounded"  href="javascript:void(0);" onclick="reset_pwd('<?php echo ($list["id"]); ?>')">密码重置</a>
                                <?php if($list['status'] == '1'): ?><a class="btn btn-danger btn-rounded" onclick="lock_admin('<?php echo ($list["id"]); ?>','2')" href="javascript:void(0);">锁定</a>
                                    <?php else: ?>
                                    <a class="btn btn-warning btn-rounded" onclick="lock_admin('<?php echo ($list["id"]); ?>','1')" href="javascript:void(0);">解锁</a><?php endif; ?>


                            </td>
                        </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <ul class="pagination">
                        <?php if($tol > 10): echo ($fpage); endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="padding:1rem 0;!important;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">修改管理员角色</h5>
            </div>
            <div class="modal-body" id="list">
                <!--<p><strong>H+</strong> 是一个完全响应式，基于Bootstrap3.3.6最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术，她提供了诸多的强大的可以重新组合的UI组件，并集成了最新的jQuery版本(v2.1.1)，当然，也集成了很多功能强大，用途广泛的jQuery插件，她可以用于所有的Web应用程序，如网站管理后台，网站会员中心，CMS，CRM，OA等等，当然，您也可以对她进行深度定制，以做出更强系统。</p>-->
            </div>
            <div class="modal-footer">
                <input id="role" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" id="sub" type="button" class="btn btn-primary" >保存</a>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="myModal7" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:80%">
            <div class="modal-header" style="padding:1rem 0;!important;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">添加管理员</h5>
            </div>
            <div class="modal-body" id="lists">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">选择职位</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="User" id="roles" >
                                <?php if(is_array($roles)): foreach($roles as $key=>$roles): ?><option value="<?php echo ($roles["id"]); ?>"><?php echo ($roles["rolename"]); ?></option><?php endforeach; endif; ?>>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" >管理员姓名：</label>

                        <div class="col-sm-8">
                            <input type="text" id="admin_name" placeholder="管理员姓名" class="form-control">
                            <!--<span class="help-block m-b-none">请输入您注册时所填的E-mail</span>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">管理员手机号：</label>
                        <div class="col-sm-8">
                            <input type="text" id="mobile" placeholder="输入手机号(登录使用)" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">登录密码：</label>
                        <div class="col-sm-8">
                            <input type="text" id="pwd" placeholder="默认123456" class="form-control">
                        </div>
                    </div>

                    <!--<div class="form-group">-->
                        <!--<div class="col-sm-offset-3 col-sm-8">-->
                            <!--<button class="btn btn-sm btn-info" type="submit" id="add">添 加</button>-->
                        <!--</div>-->
                    <!--</div>-->
                </form>
            </div>
            <div class="modal-footer">
                <!--<input id="role" value="" type="hidden">-->
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" onclick="add_admin();" type="button" class="btn btn-primary" >添加</a>
            </div>
        </div>
    </div>
</div>
<!-- 全局js -->
<script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>

<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<!-- 自定义js -->
<script src="/Public/admin/js/content.js?v=1.0.0"></script>


<script>
    $(document).ready(function () {
        $('.contact-box').each(function () {
            animationHover(this, 'pulse');
        });
    });
    $('.modify').click(function(){
        var aid=$(this).attr('aid');
        var rid=$(this).attr('rid');
        $('#role').val(aid);
        $.post('/indexadm.php/Public/get_role',{aid:aid},function(data){
            if(data.code=='1001'){
                var html='';
                $(data.result).each(function(k,v){
                    if(v.id==rid){
                        var ck="checked='checked'";
                    }else{
                        var ck='';
                    }
                    html+='<p style="text-align:left;font-size:1.8rem"><input '+ck+' aid="'+aid+'" type="radio" value="'+ v.id+'" name="role">'+ v.rolename+'</p>';
                });
             $('#list').html(html);
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
//        alert(aid);
        //示范一个公告层

    });

    $('#sub').click(function(){
        var aaid='<?php echo $_SESSION["id"];?>';
        var aid=$('#role').val();
        var rid=$('input[type=radio]:checked').val();
        $.post('/indexadm.php/User/modify_role',{aid:aid,rid:rid},function(data){
            if(data.code=='1001'){
                if(aaid==aid){
                    window.location.href='/indexadm.php/Login/logout';
                }else{
                    window.location.reload();
                }
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
    });


    function lock_admin(aid,status){
        $.post('/indexadm.php/User/lock_admin',{aid:aid,status:status},function(data){
            console.log(data);
            if(data.code=='1001'){
//                layer.alert(data.result);
               window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
    }

    function reset_pwd(aid){
        $.post('/indexadm.php/User/reset_pwd',{aid:aid},function(data){
            if(data.code=='1001'){
                layer.alert(data.result);
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
    }

    function add_admin(){
        var reg = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|17[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
        var rid=$('#roles').val();
        var admin_name=$('#admin_name').val();
        var mobile=$('#mobile').val();
        var pwd=$('#pwd').val();
        var check=mobile.match(reg);
        if(admin_name==''){
            layer.alert('管理员姓名不能为空');
            return false;
        }
        if(mobile==''){
            layer.alert('手机号不能为空');
            return false;
        }
        if(check==null){
            layer.alert('请输入正确的手机号');
            return false;
        }

        $.post('/indexadm.php/User/add_admin',{rid:rid,admin_name:admin_name,mobile:mobile,pwd:pwd},function(data){
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

    }


//    $('#search').on('click',function(){
//        var content=$('#content').val();
//        if(content==''){
//            layer.alert('搜索内容不能为空');
//            return false;
//        }
//    });



</script>


</body>

</html>