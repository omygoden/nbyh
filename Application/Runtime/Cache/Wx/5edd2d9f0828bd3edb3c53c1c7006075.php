<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>收货地址管理</title>
    <link href="/Public/wx/css/common.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/Public/wx/apla/css/common.css">
    <link href="/Public/wx/css/address.css" rel="stylesheet"/>
    <script src="/Public/wx/apla/js/jquery-1.min.js"></script>
</head>
<!--<header class="top-header fixed-header" style="position:fixed;padding-top:0">-->
    <!--<a class="icona" href="javascript:history.go(-1)">-->
        <!--<img src="/Public/wx/shangcheng/images/left.png"/>-->
    <!--</a>-->
    <!--<h3>收货地址列表</h3>-->

    <!--<a class="text-top">-->
    <!--</a>-->
<!--</header>-->
<header class="flex header">
    <i class="back" onclick="javascript:history.go(-1);"></i>
    <h1 class="flex-auto">收货地址列表</h1>
    <i></i>
</header>
<body>
<header>
    <!--<a class="icona" href="javascript:history.back('-1');"  style="float:left" >-->
        <!--<img src="/Public/wx/shangcheng/images/left.png"/>-->
    <!--</a>-->
    <?php if(empty($_GET['to'])){ ?>
    <a href="/Myinfo/add_address" style="margin-top:5rem" class="head" >
        <div></div>
        <span>新增收货地址</span>
    </a>
    <?php }else{ ?>
    <a href="/Myinfo/add_address?ourl=<?php echo $_GET['ourl'];?>" style="margin-top:5rem" class="head" >
        <div></div>
        <span>新增收货地址</span>
    </a>
    <?php } ?>
</header><!--head-->

<div class="addr-content">
    <ul class="addrs">
        <?php if(is_array($address)): foreach($address as $key=>$address): ?><li class="addr-li">
            <div class="addr-phone">
                <span>收货人：<?php echo ($address["name"]); ?></span>
                <span><?php echo ($address["mobile"]); ?></span>
            </div><!--addr-phone-->
            <div class="addr-details"><?php echo ($address["area"]); ?>-<?php echo ($address["detail"]); ?></div>
            <!--addr-details-->
            <div class="add-set">
                <?php if($address['type'] == '1'): ?><a href="javascript:void(0);" onclick="set_default_address('<?php echo ($address["id"]); ?>')">
                    <div class="set-1">
                        <div class="addr-default"></div>
                        <span>设为默认</span>
                    </div><!--set-1-->
                </a>
                    <?php else: ?>
                    <a href="javascript:void(0);" >
                        <div class="set-1">
                            <div class="addr-default"></div>
                            <span>当前默认地址</span>
                        </div><!--set-1-->
                    </a><?php endif; ?>
                <div class="addr-modify">
                    <a href="/Myinfo/add_address?aid=<?php echo ($address["id"]); ?>&ourl=<?php echo $_GET['ourl'];?>">
                        <div class="set-1">
                            <div class="addr-edit"></div>
                            <span>编辑</span>
                        </div><!--set-1-->
                    </a>
                    <a href="javascript:void(0);" onclick="del_address('<?php echo ($address["id"]); ?>')">
                        <div class="set-1">
                            <div class="addr-delete"></div>
                            <span>删除</span>
                        </div><!--set-1-->
                    </a>
                </div>  <!--addr-modify-->

            </div><!--addr-set-->
        </li><!--addr-li--><?php endforeach; endif; ?>

    </ul><!--addrs-->
</div><!--addr-content-->
<script>
    function set_default_address(aid){
        var to='<?php echo ($_GET['to']); ?>';
        $.post('/Myinfo/set_default_address',{aid:aid},function(data){
            if(data.code=='1001'){
                if(to=='1'){
                    window.location.replace(document.referrer);
                }else{
                    window.location.reload();
                }
            }else{
                alert(data.result);
            }
        });
    }
    function del_address(aid){
        var sure=confirm('是否确定删除');
        if(!sure){
            return false;
        }
        $.post('/Myinfo/del_address',{aid:aid},function(data){
            if(data.code=='1001'){
                window.location.reload();
            }else{
                alert(data.result);
            }
        });
    }

</script>
</body>
</html>