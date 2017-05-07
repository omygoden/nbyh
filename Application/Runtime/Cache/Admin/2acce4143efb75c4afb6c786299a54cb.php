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
                    <h2>商品规格管理<a style="float:right;margin:0 1rem" class="btn btn-info" href="javascript:void(0);" data-toggle="modal" data-target="#myModal6" >添加规格</a></h2>

                    <table class="table table-striped table-hover">
                        <tbody id="list">

                        <?php if(is_array($list)): foreach($list as $key=>$lists): ?><div class="btn-group" id="sid<?php echo ($lists["id"]); ?>" style="margin: 1rem 1rem">
                                <label title="<?php echo ($lists["name"]); ?>" class="btn btn-primary"><?php echo ($lists["name"]); ?></label>
                                <label title="删除"  onclick="del_size('<?php echo ($lists["id"]); ?>')" class="btn btn-primary"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></label>
                            </div><?php endforeach; endif; ?>
                        </tbody>
                    </table>

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
                <h5 class="modal-title">添加规格</h5>
            </div>
            <div class="modal-body" >
                <div class="form-group">
                    <!--<label class="col-sm-3 control-label">规格：</label>-->
                    <div class="col">
                        <input type="text" id="name" placeholder="请输入规格" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<input id="role" value="" type="hidden">-->
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" onclick="add_size()" type="button" class="btn btn-primary"  >添加</a>
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

    function add_size(){
        var name=$('#name').val();
        if(name==''){
            layer.alert('规格不能为空');
            return false;
        }
        $.post('/indexadm.php/Goods/add_size',{name:name},function(data){
            if(data.code=='1001'){
                window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        })
    }

    function del_size(sid){
        var check=confirm('是否确定删除');
        if(!check){
            return false;
        }
        $.post('/indexadm.php/Goods/del_size',{sid:sid},function(data){
            if(data.code=='1001'){
                $('#sid'+sid).remove();
//                window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        })
    }




</script>


</body>

</html>