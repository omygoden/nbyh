<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> - 基本表单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="../favicon.ico">
    <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link rel="stylesheet" href="/Public/admin/css/liandong.css"/>
    <link rel="stylesheet" href="/Public/admin/css/ace.min.css" />
    <link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="/Public/admin/css/plugins/colorbox.css" />
    <link href="/Public/admin/css/animate.css" rel="stylesheet">
    <link href="/Public/admin/css/style.css?v=4.1.0" rel="stylesheet">
    <link href="/Public/admin/js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">

    <style>
        #allmap {width: 100%;height: 200px;overflow: hidden;margin:0;font-family:"微软雅黑";}
        .Info_style .product_infoc {
            float: left;
            line-height: 26px;
            margin: 10px 5px;
        }

        #cboxClose {
            background-color: #000;
            border: 2px solid #fff;
            border-radius: 32px;
            color: #fff;
            font-size: 21px;
            height: 28px;
            margin-left: 0;
            padding-bottom: 2px;
            right: -2px;
            top: -2px;
            width: 28px;
        }
    </style>
</head>
<body class="gray-bg">
<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <!--<div class="m-b-md">-->
                                <!--<h2><?php echo ($detail["name"]); ?></h2>-->
                            <!--</div>-->
                            <dl class="dl-horizontal">

                                <dt>状态：</dt>
                                <dd><span class="label label-primary">
                                    <?php switch($detail["status"]): case "0": ?>待支付<?php break;?>
                                        <?php case "1": ?>待发货<?php break;?>
                                        <?php case "2": ?>待收货<?php break;?>
                                        <?php case "3": ?>已完成<?php break;?>
                                        <?php case "4": ?>申请退款中<?php break;?>
                                        <?php case "5": ?>退货成功<?php break;?>
                                        <?php case "6": ?>已删除<?php break;?>
                                        <!--状态（0待支付，1待发货，2待收货，3订单完成，4申请退货，5退货成功,6删除订单）--><?php endswitch;?>
                                </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <dl class="dl-horizontal">
                                <dt>头像：</dt>
                                <dd class="project-people">
                                    <img  class="img-circle" src="<?php echo ($detail["headimg"]); ?>" style="width:50px;height:50px"/>
                                </dd>
                                <dt>购买者：</dt>
                                <dd><?php echo ($detail["nickname"]); ?></dd><br>
                                <dt>订单号：</dt>
                                <dd><?php echo ($detail["order_no"]); ?></dd><br>
                                <dt>订单总额：</dt>
                                <dd><?php echo ($detail["money"]); ?></dd><br>
                                <dt>收货人：</dt>
                                <dd><?php echo ($detail["name"]); ?></dd><br>
                                <dt>联系电话：</dt>
                                <dd><?php echo ($detail["mobile"]); ?></dd><br>
                                <dt>收货地址：</dt>
                                <dd><?php echo ($detail["address"]); ?></dd><br>
                                <dt>订单创建于：</dt>
                                <dd> <?php echo (date('Y-m-d H:i:s',$detail["ctime"])); ?></dd><br>
                                <dt>订单备注：</dt>
                                <dd><?php echo ($detail["remark"]); ?></dd><br>
                            </dl>
                        </div>
                        <div class="col-sm-7" id="cluster_info">
                            <dl class="dl-horizontal">
                                <dt>快递公司：</dt>
                                <dd><?php echo ($detail["express_name"]); ?></dd><br>
                                <dt>快递单号：</dt>
                                <dd><?php echo ($detail["express_no"]); ?></dd><br>
                                <dt>发货时间：</dt>
                                <dd><?php if($detail['start_time'] != ''): echo (date('Y-m-d H:i:s',$detail["start_time"])); endif; ?></dd><br>
                                <dt>收货时间：</dt>
                                <dd><?php if($detail['end_time'] != ''): echo (date('Y-m-d H:i:s',$detail["end_time"])); endif; ?></dd>
                            </dl>
                        </div>
                    </div>

                    <div class="row m-t-sm">
                        <div class="col-sm-12">
                            <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <!--<li class="active"><a href="#tab-1" data-toggle="tab">商品折扣表</a>-->
                                            <!--</li>-->
                                            <li class="active"><a href="#tab-2" data-toggle="tab">商品规格表</a>
                                            <?php if($refund != ''): ?><li class=""><a href="#tab-3" data-toggle="tab">退货申请</a>
                                            </li>
                                            </li>

                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab-2">
                                            <div class="feed-activity-list">
                                                <div class="Info_style clearfix" >
                                                    <dl class="permission-list">
                                                        <ul>
                                                            <li><div id="createTable" style="width: 100%">
                                                                <table id="process" border="1" cellpadding="1" cellspacing="0" style="width:100%;padding:5px;">
                                                                    <thead>
                                                                    <tr>
                                                                        <!--<th>主属性</th>-->
                                                                        <th>商品图片</th>
                                                                        <th>商品名称</th>
                                                                        <th>规格</th>
                                                                        <th>单价</th>
                                                                        <th>数量</th>

                                                                    </tr>

                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if(is_array($detail["goods"])): $i = 0; $__LIST__ = $detail["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><tr>
                                                                            <td><a href="<?php echo ($s["title_img"]); ?>" class="fancybox"><img style="width:100px;height:100px" src="<?php echo ($s["title_img"]); ?>" /></a></td>
                                                                            <td><?php echo ($s["name"]); ?></td>
                                                                            <td><?php echo ($s["sname"]); ?></td>
                                                                            <td><?php echo ($s["price"]); ?></td>
                                                                            <td><?php echo ($s["num"]); ?></td>
                                                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div></li>
                                                        </ul>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab-3">
                                            <div class="feed-activity-list">
                                                <div class="Info_style clearfix" >
                                                    <dl class="permission-list">
                                                        <ul>
                                                            <li><div id="createTable" style="width: 100%">
                                                                <table id="process" border="1" cellpadding="1" cellspacing="0" style="width:100%;padding:5px;">
                                                                    <thead>
                                                                    <tr>
                                                                        <!--<th>主属性</th>-->
                                                                        <th>退款原因</th>
                                                                        <th>申请时间</th>
                                                                        <th>审核意见</th>
                                                                        <th>审核时间</th>
                                                                        <th>退款金额</th>
                                                                        <th>退款时间</th>
                                                                        <th>状态</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><?php echo ($refund["reason"]); ?></td>
                                                                            <td><?php echo (date('Y-m-d H:i:s',$refund["ctime"])); ?></td>
                                                                            <td><?php echo ($refund["check_opinion"]); ?></td>
                                                                            <td><if condition="$refund['check_time'] neq ''"><?php echo (date('Y-m-d H:i:s',$refund["check_time"])); endif; ?></td>
                                                                            <td><?php echo ($refund["return_money"]); ?></td>
                                                                            <td><?php if($refund['refund_time'] != ''): echo (date('Y-m-d H:i:s',$refund["refund_time"])); endif; ?></td>
                                                                            <td>
                                                                                <?php switch($refund["status"]): case "0": ?>待审核<?php break;?>
                                                                                    <?php case "1": ?>审核通过<?php break;?>
                                                                                    <?php case "2": ?>驳回<?php break;?>
                                                                                    <?php case "3": ?>退款成功<?php break; endswitch;?>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div></li>
                                                        </ul>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- 全局js -->
<script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/Public/admin/js/content.js?v=1.0.0"></script>
<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<script src="/Public/admin/js/plugins/jquery.colorbox-min.js"></script>
<script src="/Public/admin/js/plugins/fancybox/jquery.fancybox.js"></script>



<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script>
    $(document).ready(function () {
        $('.fancybox').fancybox({
            openEffect: 'none',
            closeEffect: 'none'
        });
    });
    var public='/Public/admin';
    jQuery(function($) {
        colorbox_params = {
            reposition:true,
            scalePhotos:true,
            scrolling:false,
            previous:'<i class="fa fa-chevron-left"></i>',
            next:'<i class="fa fa-chevron-right"></i>',
            close:'&times;',
            current:'{current} of {total}',
            maxWidth:'100%',
            maxHeight:'100%',
            onOpen:function(){
                document.body.style.overflow = 'hidden';
            },
            onClosed:function(){
                document.body.style.overflow = 'auto';
            },
            onComplete:function(){
                $.colorbox.resize();
            }
        };

        $('.Info_style [data-rel="colorbox"]').colorbox(colorbox_params);
        $('.project-people [data-rel="colorbox"]').colorbox(colorbox_params);
        $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");

    });


</script>
</body>
</html>