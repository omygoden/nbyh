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
                    <h2>自定义订单</h2>
                    <h2><a style="float:right;margin:0 1.3rem 2px 0" class="btn btn-info" href="javascript:void(0);" data-toggle="modal" data-target="#myModal6" >添加自定义订单</a></h2>
                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/orders/custom_order" method="get">
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
                            <th class="">头像</th>
                            <th>昵称</th>
                            <th>会员ID</th>
                            <th class="">订单号</th>
                            <th class="">商品名称</th>
                            <th class="">商品规格</th>
                            <th>订单总额</th>
                            <th>操作管理员</th>
                            <th>创建时间</th>
                            <th class="client-status">操作</th>
                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                                <td class="client-avatar"><img alt="image" src="<?php echo ($list["headimg"]); ?>"></td>
                                <td><?php echo ($list["nickname"]); ?></td>
                                <td><?php echo ($list["memberid"]); ?></td>
                                <td><?php echo ($list["order_no"]); ?></td>
                                <td><?php echo ($list["gname"]); ?></td>
                                <td><?php echo ($list["sname"]); ?></td>
                                <td><?php echo ($list["money"]); ?></td>
                                <td>
                                    <?php echo ($list["name"]); ?>
                                </td>
                                <td>
                                    <?php echo (date('Y-m-d H:i:s',$list["ctime"])); ?>
                                </td>
                                <td class="client-status">
                                    <a class="btn btn-danger btn-rounded" onclick="del_order('<?php echo ($list["order_no"]); ?>')" href="javescript:void(0);" >删除</a>

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
                <h5 class="modal-title">添加自定义订单</h5>
            </div>
            <div class="modal-body" id="list">
                <div class="form-group">
                    <label class="control-label" >用户会员ID：</label>

                    <div class="">
                        <input type="text" id="memberid"  class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="ccontrol-label">选择商品：</label>
                    <div class="">
                        <select class="form-control m-b" name="User" id="goods_id" onchange="put_gs(this.value)" style="height:3rem">
                            <?php if(is_array($goods)): foreach($goods as $key=>$goods): ?><option value="<?php echo ($goods["id"]); ?>"><?php echo ($goods["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="ccontrol-label">商品规格：</label>
                    <div class="">
                        <select class="form-control m-b" name="User" id="gs_id" style="height:3rem">
                            <?php if(is_array($goods_size)): foreach($goods_size as $key=>$goods_size): ?><option value="<?php echo ($goods_size["id"]); ?>"><?php echo ($goods_size["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" >总金额：</label>

                    <div class="">
                        <input type="text" id="money"  class="form-control">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input id="myopenid" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" id="sub" type="button" onclick="add_order();" class="btn btn-primary" >保存</a>
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
    function put_gs(gid){
        $('#memberid').val('');
        $('#money').val('');
        $.post('/indexadm.php/Public/get_goods_size',{gid:gid},function(data){
            var html='';
            if(data.code=='1001'){
                $(data.data).each(function(k,v){
                    html+='<option value="'+ v.id+'">'+ v.name+'</option>';
                });
                $('#gs_id').html(html);
            }else{
                layer.alert(data.result);
            }
        });
    }

    function add_order(){
        var memberid=$('#memberid').val();
        var goods_id=$('#goods_id').val();
        var gs_id=$('#gs_id').val();
        var money=$('#money').val();
        if(memberid==''){layer.alert('会员ID不能为空');return false;}
        if(goods_id==''){layer.alert('请选择商品');return false;}
        if(gs_id==''){layer.alert('请选择商品规格');return false;}
        if(money==''){layer.alert('总额不能为空');return false;}
        $.post('/indexadm.php/Orders/add_custom_order',{memberid:memberid,goods_id:goods_id,gs_id:gs_id,money:money},function(data){
            if(data.code=='1001'){
                layer.alert(data.result,function(){
                    window.location.reload();
                });
            }else{
                layer.alert(data.result);
            }
        });
    }

    function del_order(order_no){
        layer.confirm('是否确定删除',function(){
            $.post('/indexadm.php/Orders/del_custom_order',{order_no:order_no},function(data){
                if(data.code=='1001'){
                    layer.alert(data.result,function(){
                        window.location.reload();
                    });
                }else{
                    layer.alert(data.result);
                }
            });
        });
    }
</script>

</body>

</html>