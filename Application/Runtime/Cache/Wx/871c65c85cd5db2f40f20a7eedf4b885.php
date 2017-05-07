<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <link rel="stylesheet" type="text/css" href="/Public/shangcheng/css/style.css"/>
    <script src="/Public/wx/apla/js/jquery-1.min.js"></script>
    <title>我的收益</title>
    <link href="/Public/wx/apla/css/common.css" rel="stylesheet"/>
    <link href="/Public/wx/apla/css/order.css" rel="stylesheet"/>
    <link href="/Public/wx/apla/css/spokesman.css" rel="stylesheet" />
</head>
<style type="text/css">
    .top-header {
        padding-top: 4rem;
    }
    .change-address div{
        margin:3px 0
    }
    .change-address span{
        width:5rem
    }
    .myrecord{
        margin: 0;
        /*padding: 0 3%;*/
        list-style-type: none;
        color: #808080;
    }
    .myrecord td {
        width:20%
    }
    .dleft{
        width:50%;float:left
    }
    .dright{
        width:50%;float:right
    }
    .dd{
        margin:5px 0;
    }

    .top-header{
        border-bottom:0;
    }
    .all{
        font-size:1rem;
        padding-bottom:2rem;
        border-bottom:1px solid #ccc;
    }
</style>
<body class="top-header">
<header class="header flex">
    <i class="back" onclick="javascript:history.go(-1)"></i>
    <h1 class="flex-auto">我的收益</h1>
    <i></i>
</header>
<div class="contaniner fixed-conta">
    <div class="men-content">
        <!--<div style="background-color:#FC0; color:#F00; padding:5px; text-align:center">-->

        <!--每次最低提现*元，每月最多提现*次-->
        <!--</div>-->
        <div style="width: 100%;border-bottom: 2px solid #ccc" class="change-address">
            <div style="line-height: 30px;">
                <span style="font-size:1.3rem">总积分：<?php echo ($all); ?></span>
            </div>
        </div>
        <?php if(is_array($record)): foreach($record as $key=>$record): ?><div style="width: 100%;border-bottom: 2px solid #ccc" class="change-address">
            <div style="line-height: 30px;">
                <span ><?php echo ($record["remark"]); ?> &nbsp;&nbsp;&nbsp;积分+<?php echo ($record["score"]); ?> &nbsp;&nbsp;&nbsp;<?php echo (date('Y-m-d H:i:s',$record["ctime"])); ?></span>
            </div>
        </div><?php endforeach; endif; ?>
    </div>

</div>
</body>
<script type="text/javascript">


</script>
</html>