<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 嵌套列表</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin/css/animate.css" rel="stylesheet">
    <link href="/Public/admin/css/style.css?v=4.1.0" rel="stylesheet">
    <!-- <script src="/Public/admin/js/jquery.js"></script> -->

</head>
<style>
    .labels{
        width:50%;
        text-decoration: none;
        box-sizing: border-box;
        margin: 5px 5px;
        padding: 5px 10px;
        border-width: 1px;
        border-style: solid;
        border-color: rgb(231, 234, 236);
        border-radius: 3px;
        background-color: rgb(231, 234, 236);
        cursor:pointer
    }
    .power{
        height:1.5rem;
        width:1.5rem;
        margin: 5px 0;
        vertical-align: middle
    }
    
</style>
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
    <div class="wrapper wrapper-content  animated fadeInRight">
        <!--<div class="row">-->
            <!--<div class="col-sm-4">-->
                <!--<div id="nestable-menu">-->
                    <!--<button type="button" data-action="expand-all" class="btn btn-white btn-sm">展开所有</button>-->
                    <!--<button type="button" data-action="collapse-all" class="btn btn-white btn-sm">收起所有</button>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
        <div class="row">
            <div class="col-sm" >
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><a href="/indexadm.php/User/admin_list" style="color:black">管理员列表-</a><a href="/indexadm.php/User/role_list" style="color:black">角色列表-</a><a href="javascript:void(0)" style="color:#999999">修改角色权限</a></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="dd" id="nestable">

                            <ol class="dd-list" style="width:50%;left:25%">
                                <div class="" style="width:50%">
                                    <input type="" placeholder="请输入职位名称" id="rolename" class="form-control" value="<?php echo ($role["rolename"]); ?>">
                                </div>
                                <p></p>
                                <?php if(is_array($list)): foreach($list as $key=>$list): switch($list["level"]): case "1": echo str_repeat('--',($list['level']-1)*6);?><input  class="power" type="checkbox" <?php if(in_array($list['id'],$top)){echo "checked='checked'";}?> name="top" id="power<?php echo ($list["id"]); ?>" value="<?php echo ($list["id"]); ?>" level="<?php echo ($list["level"]); ?>"><label   class="labels" for="power<?php echo ($list["id"]); ?>"><?php echo ($list["name"]); ?></label><?php break;?>
                                        <?php case "2": echo str_repeat('--',($list['level']-1)*6);?><input  class="power" type="checkbox" <?php if(in_array($list['id'],$next)){echo "checked='checked'";}?> name="next" id="power<?php echo ($list["id"]); ?>" value="<?php echo ($list["id"]); ?>" level="<?php echo ($list["level"]); ?>"><label style="width:41%" class="labels" for="power<?php echo ($list["id"]); ?>"><?php echo ($list["name"]); ?></label><?php break;?>
                                        <?php case "3": echo str_repeat('--',($list['level']-1)*6);?><input  class="power" type="checkbox" <?php if(in_array($list['id'],$last)){echo "checked='checked'";}?> name="last" id="power<?php echo ($list["id"]); ?>" value="<?php echo ($list["id"]); ?>" level="<?php echo ($list["level"]); ?>"><label style="width:32%"  class="labels" for="power<?php echo ($list["id"]); ?>"><?php echo ($list["name"]); ?></label><?php break; endswitch;?>


                              <br><?php endforeach; endif; ?>
                            </ol>
                            <p></p>
                            <a href="javascript:void(0);" id="sub" class="btn btn-success" style="margin-left:25%">修 改</a>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 全局js -->

    <script src="/Public/admin/js/jquery.min.js"></script>
    <script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>

    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>

    <!-- 自定义js -->
    <script src="/Public/admin/js/content.js?v=1.0.0"></script>


    <script type="text/javascript">

        $(document).ready(function () {
            //点击子选项，父选项自动选择
            $(":checkbox").click(function () {
                console.log(123);
                var level_b = $(this).attr("level");

                if ($(this).prop('checked')) {
                    console.log(level_b);
                    var allprev = $(this).prevAll(":checkbox");

                    $(allprev).each(function (k, v) {
                        if ($(v).attr("level") < level_b) {
                            level_b--;
                            $(v).prop("checked",true);

                        }
                    });

                } else {
                    //取消父选项，将会取消所有子选项
                    var allnext = $(this).nextAll(":checkbox");
                    $(allnext).each(function (k, v) {
                        if ($(v).attr("level") > level_b) {
                            $(v).prop("checked", false);

                        } else {

                            return false;
                        }
                    });
                }
            });
        });
        $(document).ready(function () {
            //选择父选项，子选项全部选择
            $(":checkbox").click(function () {
                var level_a = $(this).attr("level");

                if ($(this).prop('checked')) {

                    var allnext = $(this).nextAll(":checkbox");
                    $(allnext).each(function (k, v) {
//                        console.log(level_a);
                        console.log($(v).attr("level"));
                        if ($(v).attr("level") > level_a) {
                            $(v).prop("checked", true);

                        } else {
                            return false;
                        }
                    });
                }
            });
        })
    $('#sub').click(function(){
        var top='';
        var next='';
        var last='';
        var rolename=$('#rolename').val();
        if(rolename==''){
            layer.alert('职位名称不能为空');
            return false;
        }
        $("input:checkbox[name=top]:checked").each(function(i){
            if(0==i){
                top+= $(this).val();
            }else{
                top+= (","+$(this).val());
            }
        });
//        return false;
        $("input:checkbox[name=next]:checked").each(function(i){
            if(0==i){
                next+= $(this).val();
            }else{
            next+= (","+$(this).val());
            }
        });
        $("input:checkbox[name=last]:checked").each(function(i){
            if(0==i){
                last+= $(this).val();
            }else{
            last+= (","+$(this).val());
            }
        });
        if(top=='' && next=='' && last==''){
            layer.alert('请选择权限');
            return false;
        }
        var tourl="/indexadm.php/User/role_list";
        var rid='<?php echo ($_GET['rid']); ?>';
        $.post('/indexadm.php/User/modify_role_power',{rolename:rolename,top:top,next:next,last:last,rid:rid},function(data){
            console.log(data);
            if(data.code=='1001'){
//                window.location.reload();
                window.location.href=tourl;
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