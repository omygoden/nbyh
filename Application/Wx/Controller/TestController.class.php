<?php
namespace Wx\Controller;
use Think\Controller;
class TestController extends Controller {
    public function test(){
        $openid='o8_jh0mq9uZ63rD5J5RFkjcFYXaA';
        $res=(int)$openid;
        var_dump($res);
//        $this->display();
    }

    public function test2(){
        $arr1=array(
            array(1),
            array(2),
            array(3)
        );
        $arr2=array(
            array(1),
            array(2)
        );
        for($i=0;$i<count($arr2);$i++){
            array_shift($arr1);
        }
        var_dump($arr1);
    }

    public function test3(){
        $modal=D('TestView');
        $res=$modal->SELECT();

        var_dump($res);
    }
    public function test4(){
        $modal=M('user','','NBYH');
        $res=$modal->FIELD("openid,nickname")->SELECT();

        var_dump($res);
    }
    public function test5(){
        $url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $data='<xml>
<mch_appid>wxe062425f740c30d8</mch_appid>
<mchid>10000098</mchid>
<nonce_str>3PG2J4ILTKCH16CQ2502SI8ZNMTM67VS</nonce_str>
<partner_trade_no>100000982014120919616</partner_trade_no>
<sign>C380BEC2BFD727A4B6845133519F3AD6</sign>
<openid>ohO4Gt7wVPxIT1A9GjFaMYMiZY1s</openid>
<check_name>NO_CHECK</check_name>
<re_user_name>张三</re_user_name>
<amount>100</amount>
<desc>节日快乐!</desc>
<spbill_create_ip>10.2.3.10</spbill_create_ip>
<sign>C97BDBACF37622775366F38B629F45E3</sign>
</xml>';
        $ch = curl_init();
        //设置1表示存入变量，设置0的话表示直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSLCERT, realpath('./Public/wxpay/cert/apiclient_cert.pem'));
        curl_setopt($ch, CURLOPT_SSLKEY, realpath('./Public/wxpay/cert/apiclient_key.pem'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //禁用后cURL将终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $res = curl_exec($ch);
        var_dump($res);
    }
    public function test6(){
        $info=M('user_team','','NBYH');
        $data=$info->SELECT();
        $openid='o8_jh0mq9uZ63rD5J5RFkjcFYXaA';
        $res=get_all_pre($data,$openid);
        var_dump(array_column($res,'pre_openid'));
    }
    public function test7(){
        var_dump(strlen('http://wx.qlogo.cn/mmopen/daibZxOVtgjgdBbdj3A2H0Fmic1fqVAqTbKahmE2MYMFT9ChKVTbicX05gwCQTgqb2SHlngjjpMFOqCm4uSoHxj2eCrHIcyFgJa/0'));
    }
    public function test8(){
        $info=M('user_team','','NBYH');
        $count=$info->count();
        echo $count;
        $info->add(array('openid'=>'1','pre_openid'=>'2','ctime'=>time()));
        $count=$info->count();
        echo '<br>';
        echo $count;
    }
    public function test9(){
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx316eb0c609ad7e3a&redirect_uri=http%3A%2F%2Fnuoya.86tudi.com%2FCart%2Fmycart&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        if(strpos($url,'redirect_uri')){

            $uri=explode('=',explode('&',$url)['1'])[1];
            $url=urldecode($uri);
        }
        var_dump($url);
    }
    public function getcurl($url = '', $type = 'get', $arr = '')
    {
//        $curl=I('url');
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

}