<?php
namespace Wx\Controller;
use Think\Controller;
class CommenController extends Controller {
    protected $appid = 'wx316eb0c609ad7e3a';
    protected $appsecret = '7e5b25fb7a42ead7f6fc469148c20a97';
    public function __construct(){
        parent::__construct();
//        $_SESSION['openid'] = 'o8_jh0mq9uZ63rD5J5RFkjcFYXaA';

    }

    public function set_openid(){
//        $_SESSION['openid'] = 'o8_jh0mq9uZ63rD5J5RFkjcFYXaA';
        $code=I('get.code');
        if(empty($code)){
            $this->getopenid();
        }
        $res=$this->get_user_info($code);
        $openid=json_decode($res)->openid;
        //用于刷新时判断，刷新时code依然还在，但code重复调用则会失效，必须重新获取
        if(empty($openid)){
            $this->getopenid();
        }
        $type=M('user','','NBYH')->WHERE(['openid'=>$openid])->getField('type');
        if($type=='2'){
            echo '<meta content="text/html" charset="utf-8" />';
            echo '<p style="font-size:3rem;width:100%">您的账号已被拉黑，如有疑问请联系客服。</p>';
            exit();
        }
        //检测该openid是否已存数据库
        $result = $this->check_openid($openid);
        if (!$result) {
            echo '<meta content="text/html" charset="utf-8" />';
            echo '<p style="font-size:3rem;width:100%">您的账号状态异常，请重新关注公众号</p>';
            exit();
        }
        session('openid', $openid);
        add_user_login_log($openid);
    }

    /**
     * 获取code
     * @return [type] [description]
     */
    public function getopenid(){
//        $to_url=C('MYURL').I('get.con').'/'.I('get.act');
        $to_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if(strpos($to_url,'redirect_uri')){
            $uri=explode('=',explode('&',$to_url)['1'])[1];
            $to_url=urldecode($uri);
        }
        $redirect_uri=urlencode($to_url);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
//        var_dump($to_url);
        header('location:'.$url);
    }

    /**
     * 获取用户详情
     * @return [type] [description]
     */
    public function get_user_info($code){
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->appsecret&code={$code}&grant_type=authorization_code";
        $data=$this->get_curl($url);
        $data=json_decode($data,true);
        $token=$this->get_access_token();
        $openid=$data['openid'];
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
        $res=$this->get_curl($url);
        return $res;

    }

    /**
     * 获取access_token
     * @return mixed
     */
    public function get_access_token(){
        $access_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$access_url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        $data = curl_exec($ch);//运行curl
        $data = json_decode($data,true);
        return $data['access_token'];
    }

    /**
     * 获取curl
     * @param $url
     * @param bool $post
     * @param string $data
     * @return mixed
     */
    public function get_curl($url,$post=false,$data=''){
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        //设置1表示存入变量，设置0的话表示直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        //禁用后cURL将终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if($post==true){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        $datas = curl_exec($ch);//运行curl
        return $datas;
    }

    /**
     * 判断该openid是否已在数据库
     */
    public function check_openid($openid){
        $info=M('user','','NBYH');
        $check=$info->WHERE(['openid'=>$openid])->FIND();
        if(empty($check)){
            return false;
        }else{
            return true;
        }
    }



}