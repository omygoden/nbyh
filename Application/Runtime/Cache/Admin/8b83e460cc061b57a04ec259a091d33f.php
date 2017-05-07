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
                            <div class="m-b-md">
                                <h2><?php echo ($detail["name"]); ?></h2>
                            </div>
                            <dl class="dl-horizontal">
                                <dt>置顶状态：</dt>
                                <dd><span class="label label-success">
                                    <?php switch($detail["is_top"]): case "1": ?>置顶<?php break;?>
                                        <?php case "2": ?>未置顶<?php break; endswitch;?>
                                       </span>
                                </dd><br>
                                <dt>状态：</dt>
                                <dd><span class="label label-primary">
                                    <?php switch($detail["status"]): case "1": ?>上架<?php break;?>
                                        <?php case "2": ?>下架<?php break; endswitch;?>
                                </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <dl class="dl-horizontal">
                                <dt>价格：</dt>
                                <dd><?php echo ($detail["price"]); ?></dd><br>
                                <dt>库存：</dt>
                                <dd><?php echo ($detail["stock"]); ?></dd><br>
                                <dt>销量：</dt>
                                <dd><?php echo ($detail["sale"]); ?></dd><br>
                                <dt>创建于：</dt>
                                <dd> <?php echo (date('Y-m-d H:i:s',$detail["ctime"])); ?></dd><br>
                                <dt>描述：</dt>
                                <dd><?php echo ($detail["description"]); ?></dd><br>
                            </dl>
                        </div>
                        <div class="col-sm-7" id="cluster_info">
                            <dl class="dl-horizontal">
                                <!--<dt>运费模板：</dt>-->
                                <!--<dd><?php echo ($gooddata["freight"]); ?></dd><br>-->
                                <dt>商品展示图片：</dt>

                                <dd class="project-people">

                                    <a href="<?php echo ($detail["title_img"]); ?>" class="img_link"  data-rel="colorbox"><img  class="img-circle"src="<?php echo ($detail["title_img"]); ?>" style="width:50px;height:50px"/></a>

                                </dd>

                                <dt>商品轮播图片：</dt>
                                <dd class="project-people">
                                    <?php if(is_array($detail["goods_imgs"])): foreach($detail["goods_imgs"] as $key=>$imgs): ?><a href="<?php echo ($imgs["img"]); ?>" class="img_link"  data-rel="colorbox"><img  class="img-circle" src="<?php echo ($imgs["img"]); ?>" style="width:50px;height:50px" /></a><?php endforeach; endif; ?>
                                </dd>
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
                                            </li>
                                            <!--<li class="active"><a href="#tab-3" data-toggle="tab">商品详情</a>-->
                                            <!--</li>-->
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                    <div class="tab-content">

                                        <!--<div class="tab-pane active" id="tab-3">-->
                                            <!--<ul>-->

                                                    <!--<?php echo ($detail["detail"]); ?>-->

                                            <!--</ul>-->
                                        <!--</div>-->

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
                                                                        <th>副属性</th>
                                                                        <th>价格</th>
                                                                        <th>库存</th>
                                                                    </tr>

                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if(is_array($detail["goods_size"])): $i = 0; $__LIST__ = $detail["goods_size"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><tr>
                                                                            <!--<td><?php echo ($s["cid"]); ?></td>-->
                                                                            <td><?php echo ($s["name"]); ?></td>
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


                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div  style="margin-left:8rem;font-weight:700">
                    <p>商品详情：</p><?php echo ($detail["detail"]); ?>
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




<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script>
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