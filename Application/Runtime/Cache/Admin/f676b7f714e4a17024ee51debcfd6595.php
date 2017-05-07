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
                    <h2>待发货订单</h2>

                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/orders/nodeliver_order" method="get">
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
                            <th class="">订单号</th>
                            <th>订单总额</th>
                            <th>订单备注</th>
                            <th>创建时间</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                            <th>收货地址</th>
                            <th class="client-status">操作</th>
                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                                <td class="client-avatar"><img alt="image" src="<?php echo ($list["headimg"]); ?>"></td>
                                <td><?php echo ($list["nickname"]); ?></td>
                                <td><?php echo ($list["order_no"]); ?></td>
                                <td><?php echo ($list["money"]); ?></td>
                                <td>
                                    <?php echo ($list["remark"]); ?>
                                </td>
                                <td>
                                    <?php echo (date('Y-m-d H:i:s',$list["ctime"])); ?>
                                </td>

                                <td class=""><?php echo ($list["name"]); ?></td>
                                <td class=""><?php echo ($list["mobile"]); ?></td>
                                <td class=""><?php echo ($list["address"]); ?></td>
                                <td class="client-status">

                                    <a class="btn btn-success btn-rounded"  href="/indexadm.php/Orders/order_detail/order_no/<?php echo ($list["order_no"]); ?>" >详情</a>
                                    <a class="btn btn-info btn-rounded" onclick="deliver('<?php echo ($list["order_no"]); ?>')" data-toggle="modal" data-target="#myModal6" href="javascript:void(0)" >发货</a>
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
                <h5 class="modal-title">发货信息</h5>
            </div>
            <div class="modal-body" id="list">
                <div class="form-group">
                    <label class="control-label" >快递公司：</label>
                    <div class="">
                        <input type="text" id="express_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" >快递单号：</label>

                    <div class="">
                        <input type="text" id="express_no" class="form-control">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input id="order_no" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" id="sub" type="button" onclick="commit_express_msg();" class="btn btn-primary" >提交</a>
            </div>
        </div>
    </div>
</div>


<div class="modal inmodal fade" id="myModal8" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:65%;margin:0 18%">
            <div class="modal-header" style="padding:1rem 0;!important;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">重审理由</h5>
            </div>
            <div class="modal-body" >
                <form class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <!--<label class="col-sm-3 control-label">回复内容：</label>-->
                        <div class="col-sm-8" style="width:100%">
                            <input type="hidden" value="" id="fid">
                            <textarea style="height:15rem;" type="text" id="opinion" placeholder="注意：重审将会把用户认证和分销商认证都初始化" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input id="openid" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <span id="add_or_upd"><a href="javascript:void(0);" onclick="review()" type="button" class="btn btn-primary" >提交</a></span>
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
    function deliver(order_no){
        $('#order_no').val(order_no);
    }
    function commit_express_msg(){
        layer.load(2);
        var express_name=$('#express_name').val();
        var express_no=$('#express_no').val();
        var order_no=$('#order_no').val();
        $.post('/indexadm.php/Orders/deliver_goods',{express_name:express_name,express_no:express_no,order_no:order_no},function(data){
            if(data.code=='1001'){
                layer.closeAll('loading');
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