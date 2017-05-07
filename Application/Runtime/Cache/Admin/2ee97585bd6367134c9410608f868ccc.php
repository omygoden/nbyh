<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 团队详情</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="/Public/admin/favicon.ico"> <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
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
<body class="gray-bg">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    <div class="ibox-content">

                        <h2>团队详情</h2>

                        <div class="clients-list">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab-3"><i class="fa fa-briefcase"></i>我的团队</a>
                                </li>

                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-3" class="tab-pane active">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>用户名</th>
                                                    <th>会员ID</th>
                                                    <th>加入时间</th>
                                                    <th>团队人数</th>
                                                    <th>团队积分</th>
                                                </tr>
                                                <?php if(is_array($user["all_user"])): foreach($user["all_user"] as $key=>$myteam): ?><tr>
                                                    <td>
                                                        <?php echo ($myteam["nickname"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($myteam["memberid"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo (date('Y-m-d H:i:s',$myteam["ctime"])); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($myteam["count"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($myteam["score"]); ?>
                                                    </td>
                                                </tr><?php endforeach; endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!--简介部分-->
            <div class="col-sm-4">
                <div class="ibox ">

                    <div class="ibox-content">
                        <div class="tab-content">
                            <div id="contact-1" class="tab-pane active">
                                <div class="row m-b-lg">
                                    <div class="col text-center">
                                        <h2><?php echo ($user["nickname"]); ?>(ID:<?php echo ($user["memberid"]); ?>)</h2>

                                        <div class="m-b-sm">
                                            <img alt="image" class="img-circle" src="<?php echo ($user["headimg"]); ?>" style="width: 62px">
                                        </div>
                                    </div>
                                    <div class="col">

                                        <br>
                                        <!--<button data-toggle="modal" data-target="#myModal7" type="button" class="btn btn-primary btn-sm btn-block" ><i class="fa fa-envelope"></i> 发送消息-->
                                        <!--</button>-->
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">

                                        <strong>相关信息</strong>
                                        <div class="row m-t-lg">
                                            <div class="col-sm-4">
                                                <h5><strong><?php if($user['score'] != ''): echo ($user["score"]); else: ?>0<?php endif; ?></strong>积分</h5>
                                            </div>
                                        </div>
                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php echo ($user["r_nickname"]); ?></span> 推荐人：
                                            </li>
                                            <!--<li class="list-group-item fist-item">-->
                                                <!--<span class="pull-right"><?php echo ($user["is_start"]); ?></span> 是否发起者：-->
                                            <!--</li>-->
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php echo ($user["direct_num"]); ?></span> 直推人数：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php echo ($user["all_score"]); ?></span> 团队积分：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php echo ($user["all_user_num"]); ?></span> 团队会员：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php switch($user["status"]): case "1": ?>已关注<?php break;?>
                                                    <?php case "2": ?>已取消关注<?php break; endswitch;?></span> 关注状态：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php switch($user["sex"]): case "0": ?>性别保密<?php break;?>
                                                    <?php case "1": ?>男<?php break;?>
                                                    <?php case "2": ?>女<?php break; endswitch;?></span> 性别：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php switch($user["is_distribution"]): case "1": ?>已是分销商<?php break;?>
                                                    <?php case "2": ?>未申请<?php break;?>
                                                    <?php case "3": ?>审核中<?php break;?>
                                                    <?php case "4": ?>驳回<?php break; endswitch;?></span> 分销商：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php switch($user["is_cert"]): case "1": ?>已认证<?php break;?>
                                                    <?php case "2": ?>未认证<?php break;?>
                                                    <?php case "3": ?>审核中<?php break;?>
                                                    <?php case "4": ?>驳回<?php break; endswitch;?></span> 实名认证：
                                            </li>
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"><?php switch($user["is_star"]): case "1": ?>是<?php break;?>
                                                    <?php case "2": ?>不是<?php break; endswitch;?></span> 公星：
                                            </li>

                                            <li class="list-group-item ">
                                                <span class="pull-right"><?php if($user['login_time'] != ''): echo (date('Y-m-d H:i:s',$user["login_time"])); endif; ?></span> 最近登录时间：
                                            </li>
                                        </ul>

                                    </div>

                                </div>
                            </div>
                        </div>
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
                    <h5 class="modal-title">信息内容</h5>
                </div>
                <div class="modal-body" id="lists">
                    <form class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <!--<label class="col-sm-3 control-label">回复内容：</label>-->
                            <div class="col-sm-8" style="width:100%">
                                <input type="hidden" value="<?php echo ($user["vid"]); ?>" id="fid">
                                <textarea style="height:15rem;" type="text" id="content" placeholder="" class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input id="vid" value="" type="hidden">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <span id="add_or_upd"><a href="javascript:void(0);" onclick="send_msg()" type="button" class="btn btn-primary" >发送</a></span>
                </div>
            </div>
        </div>
    </div>
    <!-- 全局js -->
    <script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>

    <script src="/Public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
    <!-- 自定义js -->
    <script src="/Public/admin/js/content.js?v=1.0.0"></script>

    <script>
        $(function () {
            $('.full-height-scroll').slimScroll({
                height: '100%'
            });
        });


        function send_msg(){
            var vid=$('#fid').val();
            var content=$('#content').val();
            $.post('/indexadm.php/Account/send_msg',{vid:vid,content:content},function(data){
//                console.log(data);
                if(data.code=='1001'){
                    window.location.reload();
                }else{
                    if(data.status=='0'){
                        layer.alert(data.info)
                    }else{
                        layer.alert(data.result)
                    }
                }
            })
        }
    </script>

    
    

</body>

</html>