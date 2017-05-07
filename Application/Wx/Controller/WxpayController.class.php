<?php
namespace Wx\Controller;
use Think\Controller;
class WxpayController extends Controller {
    protected $appid='';

    /**
     * 注意：
     * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
     *
     * 支付接口
     */
    public function to_pay(){
        ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
        include("./Public/wxpay/lib/WxPay.Api.php");
        include("./Public/wxpay/example/WxPay.JsApiPay.php");
        include('./Public/wxpay/example/log.php');
        include("./Public/wxpay/lib/WxPay.Data.php");
        include("./Public/wxpay/lib/WxPay.Config.php");
//初始化日志
//        $logHandler= new \CLogFileHandler("./Public/Wxpay/logs/".date('Y-m-d').'.log');
//        $log = Log::Init($logHandler, 15);

//打印输出数组信息


//①、获取用户openid
        $tools = new \JsApiPay();
//        $openId = $tools->GetOpenid();
        $openId='o8_jh0mq9uZ63rD5J5RFkjcFYXaA';

//②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url(C('MYURL')."Wxpay/check_pay");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order =\WxPayApi::unifiedOrder($input);
//        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';

//        var_dump($order);
        $order['jsApiParameters'] = $tools->GetJsApiParameters($order);
//获取共享收货地址js函数参数
        $order['editAddress'] = $tools->GetEditAddressParameters();
//        var_dump($order);
        $this->assign(array(
            'order'=>$order
        ));
        $this->display();
    }

    /**
     * 回调地址
     */
    public function check_pay(){
        ini_set('date.timezone','Asia/Shanghai');
        error_reporting(E_ERROR);
        include("./Public/wxpay/lib/WxPay.Api.php");
        include('./Public/wxpay/lib/WxPay.Notify.php');
        $notify = new \WxPayNotify();
        $notify->Handle(false);
        $res=$notify->Getvalues();
        var_dump($res);


    }

}
?>