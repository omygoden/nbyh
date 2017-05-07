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
                    <h2>商品列表</h2>

                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/goods/goods_list" method="get">
                            <div class="form-group">
                                <!--<label>商品code</label>-->
                                <input type="text" id="content" placeholder="" name="content" class="form-control">
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
                            <th class="">商品名称</th>
                            <th>商品标题图片</th>
                            <th class="">商品价钱</th>
                            <th>商品库存</th>
                            <th>商品销量</th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >置顶状态<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/Goods/goods_list">全部</a></li>
                                        <li><a href="/indexadm.php/Goods/goods_list/is_top/1">置顶</a></li>
                                        <li><a href="/indexadm.php/Goods/goods_list/is_top/2">未置顶</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >状态<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/Goods/goods_list">全部</a></li>
                                        <li><a href="/indexadm.php/Goods/goods_list/status/1">上架</a></li>
                                        <li><a href="/indexadm.php/Goods/goods_list/status/2">下架</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >仓库状态<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/Goods/goods_list">全部</a></li>
                                        <li><a href="/indexadm.php/Goods/goods_list/is_add_depot/1">仓库充足</a></li>
                                        <li><a href="/indexadm.php/Goods/goods_list/is_add_depot/2">需要补仓</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>创建时间</th>
                            <th class="client-status">操作</th>

                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                                <td><?php echo ($list["name"]); ?></td>
                                <td>
                                    <img style="width:100px;height:100px" src="<?php echo ($list["title_img"]); ?>">
                                </td>
                                <td><?php echo ($list["price"]); ?></td>
                                <td>
                                    <?php echo ($list["stock"]); ?>
                                </td>
                                <td>
                                    <?php echo ($list["sale"]); ?>
                                </td>
                                <td>
                                    <?php switch($list["is_top"]): case "1": ?>置顶<?php break;?>
                                        <?php case "2": ?>未置顶<?php break; endswitch;?>
                                </td>
                                <td>
                                    <?php switch($list["status"]): case "1": ?>上架<?php break;?>
                                        <?php case "2": ?>下架<?php break; endswitch;?>
                                </td>
                                <td class="">
                                    <?php switch($list["is_add_depot"]): case "1": ?>仓库充足<?php break;?>
                                        <?php case "2": ?>部分商品规格需要补仓<?php break; endswitch;?>
                                </td>
                                <td class=""><?php echo (date('Y-m-d H:i:s',$list["ctime"])); ?></td>
                                <td class="client-status">
                                    <?php if($list['is_top'] == '1'): ?><a class="btn btn-warning btn-rounded" onclick="to_top('<?php echo ($list["id"]); ?>','2')" href="javascript:void(0);" >取消置顶</a>
                                        <?php else: ?>
                                        <a class="btn btn-warning btn-rounded" onclick="to_top('<?php echo ($list["id"]); ?>','1')" href="javascript:void(0);" >置顶</a><?php endif; ?>

                                    <?php if($list['status'] == '1'): ?><a class="btn btn-primary btn-rounded" onclick="up_or_down('<?php echo ($list["id"]); ?>','2')" href="javascript:void(0);" >下架</a>
                                        <?php else: ?>
                                        <a class="btn btn-primary btn-rounded" onclick="up_or_down('<?php echo ($list["id"]); ?>','1')" href="javascript:void(0);" >上架</a><?php endif; ?>

                                    <a class="btn btn-info btn-rounded"  href="/indexadm.php/Goods/upd_goods/gid/<?php echo ($list["id"]); ?>" >修改</a>
                                    <a class="btn btn-success btn-rounded"  href="/indexadm.php/Goods/goods_detail/gid/<?php echo ($list["id"]); ?>" >详情</a>
                                    <a class="btn btn-danger btn-rounded" onclick="del_goods('<?php echo ($list["id"]); ?>')" href="javascript:void(0);" >删除</a>
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


    function lock_user(uid,status){
        $.post('/indexadm.php/User/lock_user',{uid:uid,status:status},function(data){
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

    function to_top(id,is_top){
        $.post('/indexadm.php/Goods/to_top',{gid:id,is_top:is_top},function(data){
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

    function up_or_down(id,status){
        $.post('/indexadm.php/Goods/up_or_down',{gid:id,status:status},function(data){
            if(data.code=='1001'){
                layer.alert(data.result,function(){
                    window.location.reload();
                });
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
    }

    function del_goods(id){
        layer.confirm('是否确定删除',{
            btn:['是','否']
        },function(){
            $.post('/indexadm.php/Goods/del_goods',{gid:id},function(data){
                if(data.code=='1001'){
                    layer.alert(data.result,function(){
                        window.location.reload();
                    });
                }else{
                    if(data.status=='0'){
                        layer.alert(data.info);
                    }else{
                        layer.alert(data.result);
                    }
                }
            });
        });

    }

</script>


</body>

</html>