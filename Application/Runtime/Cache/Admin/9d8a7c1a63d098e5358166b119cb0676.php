<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 用户详情</title>
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
                        <!--<span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>-->
                        <h2>用户详情</h2>
                        <!--<p>-->
                            <!--所有客户必须通过邮件验证-->
                        <!--</p>-->
                        <!--<div class="input-group">-->
                            <!--<input type="text" placeholder="查找客户" class="input form-control">-->
                            <!--<span class="input-group-btn">-->
                                        <!--<button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> 搜索</button>-->
                                <!--</span>-->
                        <!--</div>-->
                        <div class="clients-list">
                            <ul class="nav nav-tabs">
                                <!--<span class="pull-right small text-muted">1406 个客户</span>-->
                                <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i>我的收益</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-briefcase"></i>积分兑换</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-7"><i class="fa fa-briefcase"></i>打款记录</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-9"><i class="fa fa-briefcase"></i>退款记录</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-3"><i class="fa fa-briefcase"></i>我的团队</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-4"><i class="fa fa-briefcase"></i>收货地址</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-8"><i class="fa fa-briefcase"></i>二维码海报</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-5"><i class="fa fa-briefcase"></i>用户认证</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-6"><i class="fa fa-briefcase"></i>分销商申请</a>
                                </li>


                                <!--<li class=""><a data-toggle="tab" href="#tab-6"><i class="fa fa-briefcase"></i>用户收藏</a>-->
                                <!--<li class=""><a data-toggle="tab" href="#tab-7"><i class="fa fa-briefcase"></i>用户登报</a>-->
                                <!--<li class=""><a data-toggle="tab" href="#tab-8"><i class="fa fa-briefcase"></i>房屋出租出售</a>-->
                                <!--<li class=""><a data-toggle="tab" href="#tab-9"><i class="fa fa-briefcase"></i>房屋求租求购</a>-->
                                </li>
                            </ul>
                            <div class="tab-content">

                                <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover one">
                                                <tbody>
                                                    <tr>
                                                        <th>积分</th>
                                                        <th>详情</th>
                                                        <th>时间</th>
                                                    </tr>
                                                    <?php if(is_array($user["income"])): foreach($user["income"] as $key=>$income): ?><tr>
                                                        <td>
                                                            +<?php echo ($income["score"]); ?>
                                                        </td>
                                                        <td class="">
                                                            <?php echo ($income["remark"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo (date('Y-m-d',$income["ctime"])); ?>
                                                        </td>
                                                    </tr><?php endforeach; endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-2" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>收款人</th>
                                                    <th>手机号</th>
                                                    <th>开户行</th>
                                                    <th>银行账号</th>
                                                    <th>提现金额</th>
                                                    <th>时间</th>
                                                    <th>状态</th>
                                                </tr>
                                                <?php if(is_array($user["exchange"])): foreach($user["exchange"] as $key=>$exchange): ?><tr>
                                                        <td>
                                                           <?php echo ($exchange["name"]); ?>
                                                        </td>
                                                        <td>
                                                           <?php echo ($exchange["mobile"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($exchange["bank"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($exchange["account"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($exchange["score"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo (date('Y-m-d',$exchange["ctime"])); ?>
                                                        </td>
                                                        <td>
                                                           <?php switch($exchange["status"]): case "0": ?>待审核<?php break;?>
                                                               <?php case "1": ?>提现成功,待打款<?php break;?>
                                                               <?php case "2": ?>驳回<?php break;?>
                                                               <?php case "3": ?>打款成功<?php break; endswitch;?>
                                                        </td>
                                                    </tr><?php endforeach; endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-8" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <img style="width:30%" src="/Public/poster/<?php echo ($user["openid"]); ?>.jpg">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-3" class="tab-pane">
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

                                <div id="tab-4" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>收件人</th>
                                                    <th>联系电话</th>
                                                    <th>具体地址</th>
                                                    <th>类型</th>
                                                    <th>状态</th>
                                                    <th>创建时间</th>
                                                </tr>
                                                <?php if(is_array($user["address"])): foreach($user["address"] as $key=>$address): ?><tr>
                                                    <td>
                                                        <?php echo ($address["name"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($address["mobile"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($address["area"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php switch($address["type"]): case "1": ?>普通地址<?php break;?>
                                                            <?php case "2": ?>默认地址<?php break; endswitch;?>
                                                    </td>
                                                    <td>
                                                        <?php switch($address["status"]): case "1": ?>正常<?php break;?>
                                                            <?php case "2": ?>删除<?php break; endswitch;?>
                                                    </td>
                                                    <td>
                                                        <?php echo (date('Y-m-d',$address["ctime"])); ?>
                                                    </td>
                                                </tr><?php endforeach; endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-5" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>真实姓名</th>
                                                    <th>联系电话</th>
                                                    <th>认证照</th>
                                                    <th>认证时间</th>
                                                    <th>审核状态</th>
                                                    <th>审核人</th>
                                                    <th>审核意见</th>
                                                    <th>审核时间</th>
                                                </tr>
                                                    <tr>
                                                        <td>
                                                            <?php echo ($user_cert["name"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($user_cert["mobile"]); ?>
                                                        </td>
                                                        <td>
                                                            <img src="<?php echo ($user_cert["img"]); ?>" style="width:100px" />
                                                        </td>
                                                        <td>
                                                            <?php if($user_cert['ctime'] != ''): echo (date('Y-m-d',$user_cert["ctime"])); endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php switch($user_cert["status"]): case "0": ?>待审核<?php break;?>
                                                                <?php case "1": ?>已通过<?php break;?>
                                                                <?php case "2": ?>已驳回<?php break; endswitch;?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($user_cert["check_name"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($user_cert["check_opinion"]); ?>
                                                        </td>
                                                        <td>
                                                            <?php if($user_cert['check_time'] != ''): echo (date('Y-m-d',$user_cert["check_time"])); endif; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-6" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>真实姓名</th>
                                                    <th>联系电话</th>
                                                    <th>申请备注</th>
                                                    <th>认证时间</th>
                                                    <th>审核状态</th>
                                                    <th>审核人</th>
                                                    <th>审核意见</th>
                                                    <th>审核时间</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php echo ($user_distribution["name"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($user_distribution["mobile"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($user_distribution["remark"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php if($user_distribution['ctime'] != ''): echo (date('Y-m-d',$user_distribution["ctime"])); endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php switch($user_distribution["status"]): case "0": ?>待审核<?php break;?>
                                                            <?php case "1": ?>已通过<?php break;?>
                                                            <?php case "2": ?>已驳回<?php break; endswitch;?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($user_distribution["check_name"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($user_distribution["check_opinion"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php if($user_distribution['check_time'] != ''): echo (date('Y-m-d',$user_distribution["check_time"])); endif; ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-7" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>管理员</th>

                                                    <th>真实姓名</th>
                                                    <th>所属银行</th>
                                                    <th>银行账号</th>
                                                    <th>金额</th>
                                                    <th>打款时间</th>

                                                </tr>
                                                <?php if(is_array($user["play_money_record"])): foreach($user["play_money_record"] as $key=>$play_money): ?><tr>
                                                    <td>
                                                        <?php echo ($play_money["name"]); ?>
                                                    </td>
                                                    <!--<td>-->
                                                        <!--<?php echo ($play_money["nickname"]); ?>-->
                                                    <!--</td>-->

                                                    <td>
                                                        <?php echo ($play_money["truename"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($play_money["bank"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($play_money["account"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($play_money["money"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo (date('Y-m-d H:i:s',$play_money["ctime"])); ?>
                                                    </td>

                                                </tr><?php endforeach; endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-9" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>管理员</th>
                                                    <th>用户昵称</th>
                                                    <th>退款金额</th>
                                                    <th>退款时间</th>
                                                </tr>
                                                <?php if(is_array($user["refund_record"])): foreach($user["refund_record"] as $key=>$refund_record): ?><tr>
                                                    <td>
                                                        <?php echo ($refund_record["name"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($refund_record["nickname"]); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($refund_record["money"]); ?>
                                                    </td>
                                                    <td>
                                                       <?php echo (date('Y-m-d H:i:s',$refund_record["ctime"])); ?>
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
                                            <!--<div class="col-sm-4">-->
                                                <!--&lt;!&ndash;<span class="bar">5,3,9,6,5,9,7,3,5,2</span>&ndash;&gt;-->
                                                <!--<h5><strong><?php if($user['cradit'] != ''): echo ($user["credit"]); else: ?>0<?php endif; ?></strong>余额</h5>-->
                                            <!--</div>-->
                                            <!--<div class="col-sm-4">-->
                                                <!--&lt;!&ndash;<span class="line">5,3,9,6,5,9,7,3,5,2</span>&ndash;&gt;-->
                                                <!--<h5><strong><?php if($user['gold'] != ''): echo ($user["gold"]); else: ?>0<?php endif; ?></strong>赏银</h5>-->
                                            <!--</div>-->
                                            <div class="col-sm-4">
                                                <!--<span class="bar">5,3,2,-1,-3,-2,2,3,5,2</span>-->
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