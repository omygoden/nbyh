<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title> 诺布一号- 主页</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <link rel="shortcut icon" href="/Public/admin/favicon.ico">
    <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin/css/animate.css" rel="stylesheet">
    <link href="/Public/admin/css/style.css?v=4.1.0" rel="stylesheet">
</head>
<!-- <style>
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
</div> -->
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu" style="margin-bottom: 3rem">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <i class="fa fa-area-chart"></i>
                                        <strong class="font-bold">诺布一号</strong>
                                    </span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element">hAdmin
                    </div>
                </li>
                <li>
                    <a class="J_menuItem" href="/indexadm.php/Index/home">
                        <i class="fa fa-home"></i>
                        <span class="nav-label">主页</span>
                    </a>
                </li>
                <?php if(is_array($top_power)): foreach($top_power as $key=>$top): ?><li>
                    <a href="#">
                        <?php switch($top["actionname"]): case "user": ?><i class="fa fa-user"></i><?php break;?>
                            <?php case "goods": ?><i class="fa fa-shopping-cart"></i><?php break;?>
                            <?php case "finance": ?><i class="fa fa-dollar"></i><?php break;?>
                            <?php case "usercheck": ?><i class="fa fa-calendar-check-o"></i><?php break;?>
                            <?php case "team": ?><i class="fa fa-leanpub"></i><?php break;?>
                            <?php case "message": ?><i class="fa fa-volume-up"></i><?php break;?>
                            <?php case "agent": ?><i class="fa fa-table"></i><?php break;?>
                            <?php case "orders": ?><i class="fa fa-sticky-note-o"></i><?php break; endswitch;?>

                        <span class="nav-label"><?php echo ($top["name"]); ?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <?php if(is_array($next_power)): foreach($next_power as $key=>$next): if($next['pid'] == $top['id']): ?><li><a class="J_menuItem" href='/indexadm.php/<?php echo ($top["actionname"]); ?>/<?php echo ($next["actionname"]); ?>'><?php echo ($next["name"]); ?></a>
                        </li><?php endif; endforeach; endif; ?>

                    </ul>
                </li><?php endforeach; endif; ?>

                <?php if($_SESSION['rid'] == '99'): ?><li>
                    <a href="javascript:void(0);"><i class="fa fa-edit"></i> <span class="nav-label">权限管理</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a class="J_menuItem" href="/indexadm.php/Power/power" >添加权限</a>
                        </li>
                    </ul>
                </li><?php endif; ?>
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i
                        class="fa fa-bars"></i> </a>
                    <!--<form role="search" class="navbar-form-custom" method="post" action="search_results.html">-->
                        <!--<div class="form-group">-->
                            <!--<input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search"-->
                                   <!--id="top-search">-->
                        <!--</div>-->
                    <!--</form>-->
                </div>
                <ul class="nav navbar-top-links navbar-right">
               
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <!--<li>-->
                                <!--<a href="html/mailbox.html">-->
                                    <!--<div>-->
                                        <!--<i class="fa fa-envelope fa-fw"></i> 您有16条未读消息-->
                                        <!--<span class="pull-right text-muted small">4分钟前</span>-->
                                    <!--</div>-->
                                <!--</a>-->
                            <!--</li>-->
                            <li class="divider"></li>
                            <li>
                                <a >
                                    <div>
                                        <i class="fa fa-qq fa-fw"></i> <?php echo $_SESSION['name'];?>
                                        <span class="pull-right text-muted small"><?php echo $_SESSION['login_time'];?></span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/indexadm.php/Login/modify">
                                    <div>
                                        <i class="fa fa-refresh"></i> 修改密码
                                        <!--<span class="pull-right text-muted small">12分钟钱</span>-->
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/indexadm.php/Login/logout">
                                    <div>
                                        <i class="fa fa-power-off"></i> 退出登录
                                        <!--<span class="pull-right text-muted small">12分钟钱</span>-->
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe id="J_iframe" width="100%" height="100%" src="/indexadm.php/Index/home" frameborder="0" data-id="index_v1.html" seamless></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
</div>
<!-- 全局js -->
<script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/Public/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<!-- 自定义js -->
<script src="/Public/admin/js/hAdmin.js?v=4.1.0"></script>
<script type="text/javascript" src="/Public/admin/js/index.js"></script>
<!-- 第三方插件 -->
<!--<script src="/Public/admin/js/plugins/pace/pace.min.js"></script>-->
</body>
</html>