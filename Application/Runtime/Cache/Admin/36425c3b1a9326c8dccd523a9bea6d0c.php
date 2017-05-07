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
                    <h2>代理用户列表</h2>

                    <!--<div class="input-group">-->
                    <!--<form type="get" enctype="multipart/form-data" action="/indexadm.php/agent/agent_list">-->
                    <!--<input type="text" placeholder="请输入手机号" name="content" id="content" class="input form-control">-->
                    <!--<div class="input-group-btn">-->
                    <!--<bottom style="height: 2.8rem;"  id="search" type="submit" class="btn btn btn-primary"><i class="fa fa-search"></i> 搜索</bottom>-->
                    <!--</div>-->
                    <!--</form>-->
                    <!--</div>-->
                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/agent/agent_list" method="get">
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
                            <th class="">性别</th>
                            <th>会员ID</th>
                            <th>积分</th>
                            <th>推荐人</th>
                            <th>我的二维码</th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >分销商申请<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/User/user_list">全部</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_distribution/2">未申请</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_distribution/1">申请已通过</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_distribution/3">审核中</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_distribution/4">已驳回</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >是否为公星<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/User/user_list">全部</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_star/2">不是</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_star/1">是</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >认证状态<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/User/user_list">全部</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_cert/2">未认证</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_cert/1">已认证</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_cert/3">审核中</a></li>
                                        <li><a href="/indexadm.php/User/user_list/is_cert/4">已驳回</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>关注状态</th>
                            <th>创建时间</th>
                            <th class="client-status">操作</th>

                        </tr>
                        <?php if(is_array($user)): foreach($user as $key=>$list): ?><tr>
                                <td class="client-avatar"><img alt="image" src="<?php echo ($list["headimg"]); ?>"></td>
                                <td><?php echo ($list["nickname"]); ?></td>
                                <td><?php echo ($list["sex"]); ?></td>
                                <td><?php echo ($list["memberid"]); ?></td>
                                <td>
                                    <?php echo ($list["score"]); ?>
                                </td>
                                <td>
                                    <?php echo ($list["r_nickname"]); ?>
                                </td>
                                <td>
                                    <img style="width:100px" src="<?php echo ($list["my_qrcode"]); ?>">
                                </td>
                                <td>
                                    <?php switch($list["is_distribution"]): case "1": ?>申请已通过<?php break;?>
                                        <?php case "2": ?>未申请<?php break;?>
                                        <?php case "3": ?>审核中<?php break;?>
                                        <?php case "4": ?>已驳回<?php break; endswitch;?>
                                </td>
                                <td>
                                    <?php switch($list["is_star"]): case "1": ?>是<?php break;?>
                                        <?php case "2": ?>不是<?php break; endswitch;?>
                                </td>
                                <td>
                                    <?php switch($list["is_cert"]): case "1": ?>已认证<?php break;?>
                                        <?php case "2": ?>未认证<?php break;?>
                                        <?php case "3": ?>审核中<?php break;?>
                                        <?php case "4": ?>已驳回<?php break; endswitch;?>
                                </td>
                                <td>
                                    <?php switch($list["status"]): case "1": ?>已关注<?php break;?>
                                        <?php case "2": ?>已取消关注<?php break; endswitch;?>
                                </td>
                                <td class=""><?php echo ($list["ctime"]); ?></td>
                                <td class="client-status">

                                    <a class="btn btn-success btn-rounded modify" aid="<?php echo ($list["id"]); ?>" rid="<?php echo ($list["rid"]); ?>" href="/indexadm.php/User/user_detail/openid/<?php echo ($list["openid"]); ?>" >详情</a>
                                    <a class="btn btn-danger btn-rounded review" pid="<?php echo ($list["openid"]); ?>" href="javascript:void(0);" data-toggle="modal" data-target="#myModal8" >重审</a>
                                    <!--<a class="btn btn-info btn-rounded modify" aid="<?php echo ($list["id"]); ?>" rid="<?php echo ($list["rid"]); ?>" href="/indexadm.php/User/user_resume/vid/<?php echo ($list["vid"]); ?>" >简历</a>-->
                                    <!--<?php if($list['flag'] == '0'): ?>-->
                                        <!--<a class="btn btn-danger btn-rounded" onclick="lock_user('<?php echo ($list["id"]); ?>','1')" href="javascript:void(0);">禁用</a>-->
                                        <!--<?php else: ?>-->
                                        <!--<a class="btn btn-warning btn-rounded" onclick="lock_user('<?php echo ($list["id"]); ?>','0')" href="javascript:void(0);">解禁</a>-->
                                    <!--<?php endif; ?>-->


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
    $(document).ready(function () {
        $('.contact-box').each(function () {
            animationHover(this, 'pulse');
        });
    });

    $('.review').click(function(){
        var openid=$(this).attr('pid');
        $('#openid').val(openid);
        $('#opinion').val('');
    });

    function review(){
        var openid=$('#openid').val();
        var opinion=$('#opinion').val();
        if(opinion==''){
            layer.alert('重审理由不能为空');
            return false;
        }
        var check=confirm('重审将会把用户认证和分销商认证都初始化，是否确定继续？');
        if(!check){
            return false;
        }
        $.post('/indexadm.php/User/review_user',{openid:openid,opinion:opinion},function(data){
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