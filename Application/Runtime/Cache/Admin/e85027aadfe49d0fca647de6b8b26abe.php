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
                    <h2>申请售后订单</h2>

                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/orders/apply_return_order" method="get">
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
                            <th>订单创建时间</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                            <th>收货地址</th>
                            <th>退货原因</th>
                            <th>申请时间</th>
                            <th>审核意见</th>
                            <th>审核时间</th>
                            <th>退款金额</th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >状态<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/Orders/apply_return_order">全部</a></li>
                                        <li><a href="/indexadm.php/Orders/apply_return_order/status/0">待审核</a></li>
                                        <li><a href="/indexadm.php/Orders/apply_return_order/status/1">待退款</a></li>
                                        <li><a href="/indexadm.php/Orders/apply_return_order/status/2">已驳回</a></li>
                                        <li><a href="/indexadm.php/Orders/apply_return_order/status/3">退款成功</a></li>
                                    </ul>
                                </div>
                            </th>

                            <th class="client-status">操作</th>
                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                                <td class="client-avatar"><img alt="image" src="<?php echo ($list["headimg"]); ?>"></td>
                                <td><?php echo ($list["nickname"]); ?></td>
                                <td><?php echo ($list["order_no"]); ?></td>
                                <td><?php echo ($list["money"]); ?></td>
                                <td>
                                    <?php echo (date('Y-m-d H:i:s',$list["ctime"])); ?>
                                </td>

                                <td class=""><?php echo ($list["name"]); ?></td>
                                <td class=""><?php echo ($list["mobile"]); ?></td>
                                <td class=""><?php echo ($list["address"]); ?></td>
                                <td class=""><?php echo ($list["reason"]); ?></td>
                                <td class=""><?php echo (date('Y-m-d H:i:s',$list["rtime"])); ?></td>
                                <td class=""><?php echo ($list["check_opinion"]); ?></td>
                                <td class=""><?php if($list['check_time'] != ''): echo ($list["check_time"]); endif; ?></td>
                                <td class=""><?php echo ($list["return_money"]); ?></if></td>
                                <td class="">
                                    <?php switch($list["rstatus"]): case "0": ?>待审核<?php break;?>
                                        <?php case "1": ?>待退款<?php break;?>
                                        <?php case "2": ?>已驳回<?php break;?>
                                        <?php case "3": ?>退款成功<?php break; endswitch;?>
                                </td>
                                <td class="client-status">

                                    <a class="btn btn-success btn-rounded"  href="/indexadm.php/Orders/order_detail/order_no/<?php echo ($list["order_no"]); ?>" >详情</a>
                                    <?php switch($list["rstatus"]): case "0": ?><a class="btn btn-info btn-rounded" data-toggle="modal" data-target="#myModal7" href="javascript:void(0);" onclick="check('<?php echo ($list["order_no"]); ?>')" >审核</a><?php break;?>
                                        <?php case "1": ?><a class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#myModal6" href="javascript:void(0);" onclick="refund('<?php echo ($list["order_no"]); ?>')" >退款</a><?php break; endswitch;?>


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
<div class="modal inmodal fade" id="myModal7" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:65%;margin:0 18%">
            <div class="modal-header" style="padding:1rem 0;!important;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">审核意见</h5>
            </div>
            <div class="modal-body" id="lists">
                <form class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <!--<label class="col-sm-3 control-label">回复内容：</label>-->
                        <div class="col-sm-8" style="width:100%">
                            <input type="hidden" value="" id="fid">
                            <textarea style="height:15rem;" type="text" id="check_opinion" placeholder="" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <p style="width:50%;float:left"><input type="radio" value="1" name="status" id="adopt" checked="checked"><label for="adopt"  class="col-sm-5 control-label">通过</label></p>
                        <p style="width:50%;float:right"><input type="radio" value="2" name="status" id="reject"><label for="reject" class="col-sm-5 control-label">驳回</label></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input id="order_no" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <span id="add_or_upd"><a href="javascript:void(0);" onclick="sub_check()" type="button" class="btn btn-primary" >提交</a></span>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="padding:1rem 0;!important;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">退款操作</h5>
            </div>
            <div class="modal-body" id="list">
                <div class="form-group">
                    <label class="control-label" >退款金额：</label>
                    <div class="">
                        <input type="number" id="money" class="form-control">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input id="orderno" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" id="sub" type="button" onclick="sub_refund();" class="btn btn-primary" >提交</a>
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
    function check(order_no){
        $('#order_no').val(order_no);
    }

    function sub_check(){
        layer.load(2);
        var check_opinion=$('#check_opinion').val();
        var status=$('input[type=radio]:checked').val();
        var order_no=$('#order_no').val();
        $.post('/indexadm.php/Orders/refund_check',{check_opinion:check_opinion,status:status,order_no:order_no},function(data){
            if(data.code=='1001'){
                layer.closeAll('loading');
                window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info)
                }else{
                    layer.alert(data.result)
                }
            }
        });
    }

    function refund(order_no){
        $('#orderno').val(order_no);
    }

    function sub_refund(){

        var order_no=$('#orderno').val();
        var money=$('#money').val();
        var sure=confirm('是否确定退款？');
        if(!sure){
            return false;
        }
        layer.load(2);
        $.post('/indexadm.php/Orders/refund_handle',{money:money,order_no:order_no},function(data){
            console.log(data);
            if(data.code=='1001'){
                layer.closeAll('loading');
                window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info)
                }else{
                    layer.alert(data.result)
                }
            }
        });
    }

</script>
</body>

</html>