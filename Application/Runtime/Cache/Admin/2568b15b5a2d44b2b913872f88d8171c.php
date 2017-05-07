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
                    <h2>我的团队</h2>

                    <!--<div class="input-group">-->
                    <!--<form type="get" enctype="multipart/form-data" action="/indexadm.php/team/user_team">-->
                    <!--<input type="text" placeholder="请输入手机号" name="content" id="content" class="input form-control">-->
                    <!--<div class="input-group-btn">-->
                    <!--<bottom style="height: 2.8rem;"  id="search" type="submit" class="btn btn btn-primary"><i class="fa fa-search"></i> 搜索</bottom>-->
                    <!--</div>-->
                    <!--</form>-->
                    <!--</div>-->
                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/team/user_team" method="get">
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
                            <th>拥有积分</th>
                            <th>推荐人</th>
                            <th>团队积分</th>
                            <th>团队人数</th>
                            <th>直推人数</th>
                            <th>是否为公星</th>
                            <th>创建时间</th>
                            <th class="client-status">操作</th>
                        </tr>
                        <?php if(is_array($user)): foreach($user as $key=>$list): ?><tr>
                                <td class="client-avatar"><img alt="image" src="<?php echo ($list["headimg"]); ?>"></td>
                                <td><?php echo ($list["nickname"]); ?></td>
                                <td>
                                    <?php switch($list["sex"]): case "0": ?>保密<?php break;?>
                                        <?php case "1": ?>男<?php break;?>
                                        <?php case "2": ?>女<?php break; endswitch;?>
                                    <!--<?php echo ($list["sex"]); ?>-->
                                </td>
                                <td><?php echo ($list["memberid"]); ?></td>
                                <td>
                                    <?php echo ($list["score"]); ?>
                                </td>
                                <td>
                                    <?php echo ($list["r_nickname"]); ?>
                                </td>
                                <td>
                                    <?php echo ($list["allscore"]); ?>
                                </td>
                                <td>
                                    <?php echo ($list["count"]); ?>
                                </td>
                                <td>
                                    <?php echo ($list["direct_num"]); ?>
                                </td>
                                <td>
                                   <?php switch($list["is_star"]): case "1": ?>是<?php break;?>
                                       <?php case "2": ?>否<?php break; endswitch;?>
                                </td>
                                <td class=""><?php echo (date('Y-m-d H:i:s',$list["ctime"])); ?></td>
                                <td class="client-status">
                                    <a class="btn btn-success btn-rounded modify" aid="<?php echo ($list["id"]); ?>" rid="<?php echo ($list["rid"]); ?>" href="/indexadm.php/Team/team_detail/openid/<?php echo ($list["openid"]); ?>" >详情</a>
                                    <a class="btn btn-warning btn-rounded" href="javascript:void(0);" data-toggle="modal" data-target="#myModal6" onclick="play('<?php echo ($list["openid"]); ?>');">打款</a>
                                    <a class="btn btn-info btn-rounded modify" href="javascript:void(0);" onclick="read_record('<?php echo ($list["openid"]); ?>')" >打款记录</a>

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
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:80%">
            <div class="modal-header" style="padding:1rem 0;!important;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">企业打款</h5>
            </div>
            <div class="modal-body" id="list">
                <form class="form-horizontal">

                <div class="form-group">
                    <label class="col-sm-3 control-label" >所属银行：</label>
                    <div class="col-sm-8">
                        <input type="text" id="bank" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >银行账号：</label>
                    <div class="col-sm-8">
                        <input type="number" id="account" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >真实姓名：</label>
                    <div class="col-sm-8">
                        <input type="text" id="truename" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >打款金额：</label>
                    <div class="col-sm-8">
                        <input type="number" id="money" class="form-control">
                    </div>
                </div>
                    </form>
            </div>
            <div class="modal-footer">
                <input id="openid" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <a href="javascript:void(0);" id="sub" type="button" onclick="play_money();" class="btn btn-primary" >提交</a>
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

    function read_record(openid){
        $.post('/indexadm.php/Team/play_money_record',{openid:openid},function(data){
//            console.log(data);
            if(data.code=='1001'){
                var data=data.result,html='';
                if(data!=''){
                    html+='<div style="padding: 10px 20px;display:flex"><span style="width:15%">管理员</span><span style="width:15%">金额</span><span style="width:20%">打款时间</span><span style="width:10%">真实姓名</span><span style="width:15%">银行名称</span><span style="width:25%">银行账号</span></div>';
                    $(data).each(function(k,v){
                        html+='<div style="padding: 10px 20px;display:flex"><span style="width:15%">'+ v.name+'</span><span style="width:15%">打款：'+ v.money+'</span><span style="width:20%">'+ v.ctime+'</span><span style="width:10%">'+ v.truename+'</span><span style="width:15%">'+ v.bank+'</span><span style="width:25%">'+ v.account+'</span></div>';
                    });
//                    html+='</div>';
                }else{
                    html+='<div style="padding: 10px 50px;font-size:2rem">暂无记录</div>';
                }
                layer.open({
                    type: 1 //Page层类型
                    ,area: ['750px', '500px']
                    ,title: '企业打款记录'
                    ,shade: 0.6 //遮罩透明度
                    ,maxmin: true //允许全屏最小化
                    ,anim: 1 //0-6的动画形式，-1不开启
                    ,content:html
                });
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        })
    }
    function play(openid){
        $('#openid').val(openid);
        $('#money').val('');
        $('#account').val('');
        $('#truename').val('');
        $('#bank').val('');
    }
    function play_money(){
        var openid=$('#openid').val();
        var money=$('#money').val();
        var account=$('#account').val();
        var truename=$('#truename').val();
        var bank=$('#bank').val();
        layer.confirm('是否确定打款？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post('/indexadm.php/Team/play_money',{openid:openid,money:money,account:account,truename:truename,bank:bank},function(data){
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
            })
        }, function(){

        });
    }






</script>


</body>

</html>