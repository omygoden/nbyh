<?php
return array(
//    'DEFAULT_MODULE'        =>  'Wx',  // 默认模块
    'TMPL_L_DELIM'          =>  '<{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}>',            // 模板引擎普通标签结束标记

    'SHOW_PAGE_TRACE'       => false, // 显示页面Trace信息
    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
//    'COOKIE_EXPIRE'         =>  216000,       // Cookie有效期
    'DEFAULT_FILTER'        =>  'trim,removexss', // 默认参数过滤方法 用于I函数...
    'MODULE_DENY_LIST'      =>  array('Common','Runtime','Wx','Admin'),
//    'APP_SUB_DOMAIN_DEPLOY' =>  1,   // 是否开启子域名部署
//    'APP_SUB_DOMAIN_RULES'  =>  array(
//        'lsbsadm.86tudi.com'=>'Admin'
//    ), // 子域名部署规则
    'TOKEN_ON'      =>    false,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true

//    'DB_HOST' => 'test0577.mysql.rds.aliyuncs.com', // 服务器地址
//    'DB_NAME' => 'nuoya',          // 数据库名
//    'DB_USER' => 'nuoya',      // 用户名
//    'DB_PWD' => 'Nuoya0577',          // 密码
//
//    'DB_PORT' => '3306',        // 端口
//    'DB_PREFIX' => '',    // 数据库表前缀
//    'DB_FIELDTYPE_CHECK' => false,       // 是否进行字段类型检查
//    'DB_FIELDS_CACHE' => true,        // 启用字段缓存
//    'DB_CHARSET' => 'utf8',      // 数据库编码默认采用utf8
//    'DB_DEPLOY_TYPE' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
//    'DB_RW_SEPARATE' => false,       // 数据库读写是否分离 主从式有效
    'NBYH'                  => 'mysql://nuoya:Nuoya0577@test0577.mysql.rds.aliyuncs.com/nuoya#utf8',
    'DB_TYPE' => 'mysql',
    'URL_HTML_SUFFIX'       =>  'asp',  // URL伪静态后缀设置
    'MYURL'=>'http://nuoya.86tudi.com/',
    'APPID'=>'cb015ccd-da98-46cd-ac59-4cf40d0f10b1',
    'APPSECRET'=>'30ae7fe8-a7a5-46bf-aee2-c5b319812908'



);