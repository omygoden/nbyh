<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>角色列表</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/Public/admin/favicon.ico">
    <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
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
                    <br>
                    <h3><a href="/indexadm.php/User/admin_list" style="color:black">管理员列表-</a><a href="javascript:void(0)" style="color:#999999">角色列表</a><a style="float:right" class="btn btn-primary" href="/indexadm.php/User/addrole">添加角色</a></h3>

                    <!--<div class="input-group">-->
                        <!--<input type="text" placeholder="请输入手机号" class="input form-control">-->
                        <!--<div class="input-group-btn">-->
                            <!--<button type="button" class="btn btn btn-primary"><i class="fa fa-search"></i> 搜索</button>-->
                        <!--</div>-->
                    <!--</div>-->
                    <br>
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr>
                            <th class="">编号</th>

                            <th class="">职位名称</th>


                            <th>创建时间</th>
                            <th class="client-status">操作</th>
                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                            <td class=""><?php echo ($list["id"]); ?></td>

                            <td><?php echo ($list["rolename"]); ?></td>

                            <td class=""><?php echo ($list["ctime"]); ?></td>
                            <td class="client-status">
                                <a class="btn btn-success btn-rounded" rid="<?php echo ($list["id"]); ?>" href="/indexadm.php/User/modify_power/rid/<?php echo ($list["id"]); ?>">修改权限</a>
                                <a class="btn btn-danger btn-rounded" rid="<?php echo ($list["id"]); ?>" href="javascript:void(0);" onclick="del_role('<?php echo ($list["id"]); ?>');" >删除</a>

                            </td>
                        </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <!--<ul class="pagination">-->
                        <!--<li>-->
                            <!--<a href="#">Prev</a>-->
                        <!--</li>-->
                        <!--<li>-->
                            <!--<a href="#">1</a>-->
                        <!--</li>-->
                        <!--<li>-->
                            <!--<a href="#">2</a>-->
                        <!--</li>-->
                        <!--<li>-->
                            <!--<a href="#">3</a>-->
                        <!--</li>-->
                        <!--<li>-->
                            <!--<a href="#">4</a>-->
                        <!--</li>-->
                        <!--<li>-->
                            <!--<a href="#">5</a>-->
                        <!--</li>-->
                        <!--<li>-->
                            <!--<a href="#">Next</a>-->
                        <!--</li>-->
                    <!--</ul>-->
                </div>
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

    function del_role(rid){
        var check=confirm('是否确定删除该角色？');
        if(!check){
            return false;
        }
        $.post('/indexadm.php/User/del_role',{rid:rid},function(data){
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
</script>


</body>

</html>