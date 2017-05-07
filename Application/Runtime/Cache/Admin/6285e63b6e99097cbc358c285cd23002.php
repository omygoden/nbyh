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
    <link href="/Public/admin/css/plugins/footable/footable.core.css" rel="stylesheet">
    <link href="/Public/admin/js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
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
                    <h2>提现申请列表</h2>

                    <div class="col-sm-12" style="margin-bottom: 1rem">
                        <form role="form" action="/indexadm.php/finance/exchange_check" method="get">
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
                            <th>真实姓名</th>
                            <th>联系电话</th>
                            <th>开户银行</th>
                            <th>账号</th>
                            <th>提现金额</th>
                            <th>提现时间</th>
                            <th>审核意见</th>
                            <th>审核人</th>
                            <th>审核时间</th>
                            <th class="">
                                <div class="btn-group">
                                    <div data-toggle="dropdown" >状态<button type="button" style="background-color: #f9f9f9;border: none;"><span class="caret"></span></button></div>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/indexadm.php/Finance/exchange_check">全部</a></li>
                                        <li><a href="/indexadm.php/Finance/exchange_check/status/0">待审核</a></li>
                                        <li><a href="/indexadm.php/Finance/exchange_check/status/1">审核通过,待打款</a></li>
                                        <li><a href="/indexadm.php/Finance/exchange_check/status/2">已驳回</a></li>
                                        <li><a href="/indexadm.php/Finance/exchange_check/status/3">打款成功</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th>打款时间</th>
                            <th class="client-status">操作</th>

                        </tr>
                        <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr>
                                <td class="client-avatar"><img alt="image" src="<?php echo ($list["headimg"]); ?>"></td>
                                <td><?php echo ($list["nickname"]); ?></td>
                                <td><?php echo ($list["name"]); ?></td>
                                <td><?php echo ($list["mobile"]); ?></td>
                                <td>
                                    <?php echo ($list["bank"]); ?>
                                </td>
                                <td><?php echo ($list["account"]); ?></td>
                                <td><?php echo ($list["score"]); ?></td>
                                <td><?php if($list['ctime'] != ''): echo (date('Y-m-d',$list["ctime"])); endif; ?></td>
                                <td><?php echo ($list["check_opinion"]); ?></td>
                                <td><?php echo ($list["check_name"]); ?></td>
                                <td class=""><?php if($list['check_time'] != ''): echo (date('Y-m-d',$list["check_time"])); endif; ?></td>
                                <td>
                                    <?php switch($list["status"]): case "0": ?>待审核<?php break;?>
                                        <?php case "1": ?>审核通过,待打款<?php break;?>
                                        <?php case "2": ?>已驳回<?php break;?>
                                        <?php case "3": ?>打款成功<?php break; endswitch;?>
                                </td>
                                <td class=""><?php if($list['play_time'] != ''): echo (date('Y-m-d',$list["play_time"])); endif; ?></td>
                                <td class="client-status">
                                    <!--<a class="btn btn-success btn-rounded " href="/Account/cert_detail/id/<?php echo ($list["id"]); ?>" >详情</a>-->
                                    <!--<?php if($list['status'] != 1): ?>-->
                                <!--<a class="btn btn-info btn-rounded "  data-toggle="modal" data-target="#myModal7" href="javascript:void(0);"  onclick="check('<?php echo ($list["id"]); ?>','<?php echo ($list["check_opinion"]); ?>');">审核</a>-->

                                    <!--<?php endif; ?>-->
                                    <?php switch($list["status"]): case "0": ?><a class="btn btn-info btn-rounded "  data-toggle="modal" data-target="#myModal7" href="javascript:void(0);"  onclick="check('<?php echo ($list["id"]); ?>','<?php echo ($list["check_opinion"]); ?>');">审核</a><?php break;?>
                                        <?php case "1": if($list['wstatus'] == ''): ?><a class="btn btn-warning btn-rounded" href="javascript:void(0);"  onclick="play('<?php echo ($list["id"]); ?>');">打款成功</a><?php endif; break; endswitch;?>
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

                        <p style="width:50%;float:left"><input type="radio" value="1" name="status" id="adopt" checked="checked"><label for="adopt"  class="col-sm-3 control-label">通过</label></p>
                        <p style="width:50%;float:right"><input type="radio" value="2" name="status" id="reject"><label for="reject" class="col-sm-3 control-label">驳回</label></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input id="eid" value="" type="hidden">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <span id="add_or_upd"><a href="javascript:void(0);" onclick="sub_check()" type="button" class="btn btn-primary" >提交</a></span>
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
                        <label class="col-sm-3 control-label">选择银行</label>
                        <div class="col-sm-8">
                            <select class="form-control" style="height:3rem" name="bankclass" id="bankclass" >
                                <?php if(is_array($bank)): foreach($bank as $k=>$bank): ?><option value="<?php echo ($k); ?>"><?php echo ($bank); ?></option><?php endforeach; endif; ?>>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" >银行账号：</label>
                        <div class="col-sm-8">
                            <input type="number" id="bankaccount" class="form-control">
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
                <input id="ae_id" value="" type="hidden">
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
<script src="/Public/admin/js/plugins/fancybox/jquery.fancybox.js"></script>

<script>
    $(document).ready(function () {
        $('.contact-box').each(function () {
            animationHover(this, 'pulse');
        });
    });

    function check(id,content){
        $('#eid').val(id);
        $('#check_opinion').val(content);
    }


    function sub_check(){
        var opinion=$('#check_opinion').val();
        var eid=$('#eid').val();
        var status=$("input[type=radio]:checked").val();
        if(opinion==''){
            layer.alert('审核意见不能为空');
            return false;
        }
        $.post('/indexadm.php/Finance/sub_exchange_check',{opinion:opinion,eid:eid,status:status},function(data){
//            console.log(data);
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

    function play(id){
        layer.confirm('是否确定已打款？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            layer.load(2);
            $.post('/indexadm.php/Finance/cash',{eid:id},function(data){
                if(data.code=='1001'){
                    layer.closeAll('loading');
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
//        var json='<?php echo ($json); ?>';
//        var json=eval('('+json+')');
//        $('#openid').val(openid);
//        $('#truename').val(name);
//        $('#bankaccount').val(account);
//        $('#ae_id').val(id);
//        var html='';
//
//        $(json).each(function(k,v){
//            var k=k*1+1;
//            if(k==parseInt(bank)){
//                var ss='selected';
//            }else{
//                var ss='';
//            }
//            html+='<option value="'+k+'" '+ss+'>'+v+'</option>';
//        });
////        console.log(json);
//        $('#bankclass').html(html);
//
//        $('#money').val('');
    }
//    function play_money(){
//        var ae_id=$('#ae_id').val();
//        var openid=$('#openid').val();
//        var money=$('#money').val();
//        var bankaccount=$('#bankaccount').val();
//        var truename=$('#truename').val();
//        var bankclass=$('#bankclass').val();
//        layer.confirm('是否确定打款？', {
//            btn: ['确定','取消'] //按钮
//        }, function(){
//            $.post('/indexadm.php/Finance/cash',{openid:openid,money:money,bankaccount:bankaccount,truename:truename,bankclass:bankclass,eid:ae_id},function(data){
//                if(data.code=='1001'){
//                    layer.alert(data.result,function(){
//                        window.location.reload();
//                    });
//                }else{
//                    if(data.status=='0'){
//                        layer.alert(data.info);
//                    }else{
//                        layer.alert(data.result);
//                    }
//                }
//            })
//        }, function(){
//
//        });
//    }

    $(document).ready(function () {
        $('.fancybox').fancybox({
            openEffect: 'none',
            closeEffect: 'none'
        });
    });

</script>


</body>

</html>