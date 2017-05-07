<?php
//function myajax($code,$result){
//    $this->ajaxReturn(['code'=>$code,'result'=>$result]);
//}

//获取IP 地址
function getIP()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IPaddress = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $IPaddress = getenv("HTTP_CLIENT_IP");
        } else {
            $IPaddress = getenv("REMOTE_ADDR");
        }
    }
    return $IPaddress;
}

/**
 * 添加管理员登录日志
 * @param $data管理员id
 */
function addlog($aid, $content)
{
    $logmodel = M('admin_log', '', 'NBYH');
    if (empty($content)) {
        $content = "在ip为:" . getIP() . "登录。";
    }
    $data = [
        'aid' => $aid,
        'content' => $content,
        'ctime' => time()
    ];
    $logmodel->add($data);
}

/**
 * 添加用户积分日志
 * @param $openid 用户openid
 * @param $score 积分
 * @param $type 1是收入。2是兑换，3是兑换失败退还
 * @param $remark 备注
 */
function add_score_log($openid, $score, $type, $remark)
{
    $info = M('score_record', '', 'NBYH');
    $data['openid'] = $openid;
    $data['score'] = $score;
    $data['type'] = $type;
    $data['remark'] = $remark;
    $data['ctime'] = time();
    $info->add($data);
}

/**
 * 退款/打款记录
 * @param $aid 管理员id
 * @param $openid 用户openid
 * @param $money 金额
 * @param $type 1是退款，2打款
 */
function add_money_log($aid, $openid, $money, $type)
{
    $info = M('money_record', '', 'NBYH');
    $data['aid'] = $aid;
    $data['openid'] = $openid;
    $data['money'] = $money;
    $data['type'] = $type;
    $data['ctime'] = time();
    $info->add($data);
}

/**
 * 用户登录日志
 */
function add_user_login_log($openid)
{
    $info = M('user_login_log', '', 'NBYH');
    $start = mktime('0', '0', '0', date('m', time()), date('d', time()), date('Y', time()));
    $end = mktime('23', '59', '59', date('m', time()), date('d', time()), date('Y', time()));
    $time = $info->WHERE(['openid' => $openid])->ORDER('ctime desc')->LIMIT(1)->getField('ctime');
    if ($time > $end || $time < $start) {
        $data['openid'] = $openid;
        $data['login_ip'] = getIP();
        $data['ctime'] = time();
        $info->add($data);
    }
}

/**
 * 添加系统信息日志
 */
function add_message($aid, $openid, $title, $content)
{
    $info = M('message');
    $data['aid'] = $aid;
    $data['openid'] = $openid;
    $data['title'] = $title;
    $data['content'] = $content;
    $data['ctime'] = time();
    $info->add($data);
}

/**
 * 递归获取无限级分类（微信端获取团队下级使用）
 */
function get_new_array($data = array(), $pre_openid = '', $arrs = array())
{
    static $arr = array();
    if (!empty($arrs)) {
        for ($i = 0; $i < count($arrs); $i++) {
            array_shift($arr);
        }
    }else{
        $pre_openid = empty($pre_openid) ? '0' : $pre_openid;
        foreach ($data as $key => $value) {
            if ($value['pre_openid'] == $pre_openid) {
                $arr[] = $value;
                get_new_array($data, $value['openid']);
            }
        }
        return $arr;
    }

}

/**
 * 获取该用户的所有上级
 */
//static $a;
function get_all_pre($data = array(), $openid = '')
{

    static $parr;
    foreach ($data as $key => $value) {
        if ($value['openid'] == $openid) {
            $parr[] = $value;
            get_all_pre($data, $value['pre_openid']);
        }
    }
    return $parr;
}


/**
 * 递归获取无限级分类(后台权限添加使用)
 */
function get_new_arrays($data = array(), $pid = '', $arrs = array())
{
    static $arrss = array();
    if (!empty($arrs)) {
        for ($i = 0; $i < count($arrs); $i++) {
            array_shift($arrss);
        }
    }
    $pid = empty($pid) ? '0' : $pid;
    foreach ($data as $key => $value) {
        if ($value['pid'] == $pid) {
            $arrss[] = $value;
            get_new_arrays($data, $value['id']);
        }
    }
    return $arrss;
}

/**
 * 审核结果通知 信息模板
 * @param $openid 用户openid
 * @param string $title 信息模板标题
 * @param $nickname 用户昵称
 * @param $result 审核结果
 * @param $remark 审核意见
 */
function check_notice($openid, $title = '', $nickname, $result, $remark = '')
{
    if (empty($remark)) {
        $remark = '谢谢您的参与';
    }
    $remark = '备注:' . $remark;
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"lWynLPL6HP2eTJCFzd48e7nA0OifeZyJBOaJYpT-uuQ",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $nickname . '",
                       "color":"#000000"
                   },
                   "keyword2": {
                "value":"' . $result . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
//    var_dump($res);
}

/**
 * 订单提交成功通知 信息模板
 * @param $openid 用户openid
 * @param string $title 信息模板标题
 * @param $order_no 订单号
 * @param $money 订单总金额
 * @param $remark 备注
 */
function commit_notice($openid, $order_no, $money)
{
    $title = '亲，您的订单已经提交成功，请及时付款，别错过哦。';
    $remark = '如您在支付中遇到问题，请联系我们的客服，协助您处理！';
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"owpiUairGLHtCDOcRg51znxduD_FsAoalia9kVSN5gM",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $order_no . '",
                       "color":"#000000"
                   },
                   "keyword2": {
                "value":"' . $money . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}

/**
 * 订单支付成功模板
 * @param $openid 用户openid
 * @param $goods_name 商品名称
 * @param $order_no 订单号
 * @param $money 订单总金额
 * @return bool
 */
function pay_notice($openid, $goods_name, $order_no, $money)
{
    $title = '您好，您的订单已支付成功！';
    $remark = '感谢您的光临,我们会尽快发货的~';
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"lEOtwRPXbqtFzcc1vS0jYnKzboCvu73vHvsXS48lS_g",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $goods_name . '",
                       "color":"#000000"
                   },
                   "keyword2":{
                "value":"' . $order_no . '",
                       "color":"#000000"
                   },
                   "keyword3": {
                "value":"' . $money . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}

/**
 * 订单发货通知
 * @param $openid 用户openid
 * @param $order_no 订单号
 * @param $express_name 快递公司
 * @param $express_no 快递单号
 * @return bool
 */
function shipping_notice($openid, $order_no, $express_name, $express_no)
{
    $title = '您好，您的订单已发货';
    $remark = '请注意查收,O(∩_∩)O~';
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"fb6i7xjUNklMWqYU727vbHGDc80a5T17nRahZ0OK6v4",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $order_no . '",
                       "color":"#000000"
                   },
                   "keyword2":{
                "value":"' . $express_name . '",
                       "color":"#000000"
                   },
                   "keyword3": {
                "value":"' . $express_no . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}

/**
 * 退款审核通知
 * @param $openid 用户openid
 * @param $order_no 订单号
 * @param $goods_name 商品名称
 * @param $check_time 审核时间
 * @param $order_money 订单金额
 * @param $result 审核结果
 * @param string $remark 备注
 * @return bool
 */
function apply_return_goods_notice($openid, $order_no, $goods_name, $check_time, $order_money, $result, $remark = '')
{
    $title = '您好，您的退货申请已审核。';
    if (empty($remark)) {
        $remark = '我们在收到货之后，3个工作日内为您打款。如您还有疑问，请联系客服';
    }
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"tg1qz4YK-KVXQQ70zWrti-f-6fojM3-0DnttVcevkmM",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $order_no . '",
                       "color":"#000000"
                   },
                   "keyword2":{
                "value":"' . $goods_name . '",
                       "color":"#000000"
                   },
                   "keyword3": {
                "value":"' . $check_time . '",
                       "color":"#000000"
                   },
                   "keyword4": {
                "value":"' . $order_money . '",
                       "color":"#000000"
                   },
                   "keyword5": {
                "value":"' . $result . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}

/**
 * 退款成功通知
 * @param $openid 用户openid
 * @param $order_no 订单号
 * @param $order_money 退款金额
 * @param string $remark 备注
 * @return bool
 */
function return_money_notice($openid, $order_no, $money, $remark = '')
{
    $title = '您好,您的退款已成功,请注意查收';
    if (empty($remark)) {
        $remark = '感谢您的使用';
    }
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"5I_M9ckHqmLUmJbMXoaA7HiUXuYjnI9Raipon7dnAyk",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $order_no . '",
                       "color":"#000000"
                   },
                   "keyword2":{
                "value":"' . $money . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}

/**
 * 提现通知
 * @param $openid 用户openid
 * @param $title 标题
 * @param $time 提现时间
 * @param $exchange_money  提现金额
 * @param $real_money  到账金额
 * @param $bank_account 银行账号
 * @param $play_time 打款时间
 * @param string $remark 备注
 */
function play_money_notice($openid,$title,$time,$exchange_money,$real_money,$bank_account,$play_time,$remark=''){
    $title = empty($title)?'你的提现申请已经打款':$title;
    $remark = empty($remark)?'感谢你的使用，不同银行到账时间不同，若2个工作日内未到账，请联系银行客服':$remark;
    if (empty($remark)) {
        $remark = '我们在收到货之后，3个工作日内为您打款。如您还有疑问，请联系客服';
    }
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"NeswNelT7892sAT-pNXZAIlW052p2IelCqWWTzAjtOQ",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $time . '",
                       "color":"#000000"
                   },
                   "keyword2":{
                "value":"' . $exchange_money . '",
                       "color":"#000000"
                   },
                   "keyword3": {
                "value":"' . $real_money . '",
                       "color":"#000000"
                   },
                   "keyword4": {
                "value":"' . $bank_account . '",
                       "color":"#000000"
                   },
                   "keyword5": {
                "value":"' . $play_time . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}

function modify_msg_notice($openid,$nickname,$memberid,$r_nickname){
    $title = '亲爱的'.$nickname.'，您的推荐人已修改为'.$r_nickname;
    $remark = '如有疑问，请联系客服';

    $time=date('Y-m-d H:i:s',time());
    $token = getaccesstoken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
    $data = '{
           "touser":"' . $openid . '",
           "template_id":"fpRWl3ZT1rtvjIZJrw4jfuk-iSLnMYr3UW3r7xjd0wE",
           "data":{
                   "first": {
                "value":"' . $title . '",
                       "color":"#000000"
                   },
                   "keyword1":{
                "value":"' . $memberid . '",
                       "color":"#000000"
                   },
                   "keyword2":{
                "value":"' . $time . '",
                       "color":"#000000"
                   },
                   "remark":{
                "value":"' . $remark . '",
                       "color":"#000000"
                   }
                }
            }';
    $res = getcurl($url, 'post', $data);
    return true;
}


/**
 * 获取access_token
 * @return mixed
 */
function getaccesstoken()
{
    $appid = 'wx316eb0c609ad7e3a';
    $appsecret = '7e5b25fb7a42ead7f6fc469148c20a97';
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
    $res = getcurl($url);
    $resobj = json_decode($res);
    $_SESSION['access_token'] = $resobj->access_token;
    $_SESSION['expires_time'] = time() + 3600;
    return $_SESSION['access_token'];
}

/**
 * @param $url
 * @param string $type 默认get，可填post
 * @param string $arr $type为post时需要传输的数组
 * @return 返回json数据
 */
function getcurl($url = '', $type = 'get', $arr = '')
{
    $ch = curl_init();
    //设置1表示存入变量，设置0的话表示直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($type == 'post') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
    }
    //禁用后cURL将终止从服务端进行验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $res = curl_exec($ch);
    if (curl_errno($ch)) {
        echo '错误' . curl_errno($ch);
    }
    curl_close($ch);
    return $res;
}
//测试程序执行时间使用
function get_microtime()
{
    $time = explode(' ', microtime());
    return $time['1'] + $time['0'];
}

function removexss($val){
    static $obj=null;
    if($obj===null){
        include('./Public/htmlpurifier/HTMLPurifier.includes.php');
        $config=HTMLpurifier_Config::createDefault();
        $config->set('HTML.TargetBlank',TRUE);
        $obj=new HTMLPurifier($config);
    }
    return $obj->purify($val);
}

