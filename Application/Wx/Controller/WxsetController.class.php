<?php
namespace Wx\Controller;

use Think\Controller;

class WxsetController extends Controller
{
    protected $appid = 'wx316eb0c609ad7e3a';
    protected $appsecret = '7e5b25fb7a42ead7f6fc469148c20a97';

    /**
     * 微信公众号配置设置
     * @return bool
     * @throws EXCEPTION
     */
    public function verify()
    {
        define('TOKEN', 'nuobuyihao');
        if (!defined("TOKEN")) {
            throw new EXCEPTION('TOKEN IS NOT DEFINED!');
        }
        $echostr = $_GET['echostr'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = TOKEN;
        $signature = $_GET['signature'];
        $arr = array($token, $timestamp, $nonce);
        sort($arr, SORT_STRING);
        $arr2 = implode('', $arr);
        $arr2 = sha1($arr2);
        if ($arr2 == $signature) {
            echo $echostr;
        } else {
            return false;
        }
    }

    /**
     * 微信公众号关注时的设置
     * @throws EXCEPTION
     */
    public function response()
    {
//        $this->verify();
        @$postmsg = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (!empty($postmsg)) {
            $postObj = simplexml_load_string($postmsg, 'SimpleXMLElement', LIBXML_NOCDATA);
            $content = trim($postObj->Content);
            $msgtype = $postObj->MsgType;
            $event = $postObj->Event;
            $eventkey = trim($postObj->EventKey);
            $openid = trim($postObj->FromUserName);
            $content = '欢迎关注诺布一号公众号！';
            if ($msgtype == 'text') {
                $this->responsetext($postObj, $content);
            } elseif ($msgtype == 'event') {
                if (strtolower($event) == 'subscribe') {
                	
                		if(file_exists('./Public/poster/'.$openid.'.jpg')){
                			$this->service_response($openid, $content);
                		}
            		   $res = $this->add_user_db($openid, $eventkey);
	                    if (!file_exists('./Public/poster/'.$openid.'.jpg') && $res) {
	                        $user = M('user', '', 'NBYH')->WHERE(['id' => $res])->FIND();
	                        $this->service_response($openid, $content);
	                        $this->service_response($openid, '正在给您生成二维码海报...');
	                        $ress=$this->create_poster($openid, $user['headimg'], $user['my_qrcode'], $user['nickname']);
	                        if($ress){
								$this->service_response($openid, '', 'image');
								exit();	
	                        }
	                    }
                 
                } elseif (strtolower($event) == 'unsubscribe') {
                    $this->cancel_subscribe($openid);
                } elseif (strtolower($event) == 'scan') {
                    $this->responsetext($postObj, $content);
                } elseif (strtolower($event) == 'click') {
                    if (strtolower($eventkey) == 'service') {
                        $service_number=M('service','','NBYH')->WHERE(['id'=>'1'])->getField('phone');
                        $this->responsetext($postObj, $service_number, 'service');
                    }
                }
                $this->createmenu();
            } else {
                $this->responsetext($postObj, $content);
            }
        }
    }

    /**
     * 创建自定义菜单
     */
    public function createmenu()
    {
        $access_token = $this->getaccesstoken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        $arr = '{
               "button":
             [
             {
                   "type":"view",
                   "name":"商城",
                   "url":"' . C('MYURL') . 'Shop/shop"
              },
              {
                    "name":"个人中心",
                    "sub_button":[
                        {
                           "type":"view",
                           "name":"个人中心",
                           "url":"' . C('MYURL') . 'Myinfo/myinfo"
                        },
                        {
                           "type":"click",
                           "name":"客服电话",
                           "key":"service"
                        },
                    ]
              }
               ]
              }';
        $this->getcurl($url, 'post', $arr);
//        $res = $this->getcurl($url, 'post', $arr);
//        var_dump($res);
    }

    /**
     * 以文本形式回复
     * @param $obj 微信用户向公众号发送的信息
     * @param $content 公众号向用户回复的信息
     */
    public function responsetext($obj, $content, $eventkey = '')
    {
        $tousertext = "
                 <xml>
                 <ToUserName><![CDATA[%s]]></ToUserName>
                 <FromUserName><![CDATA[%s]]></FromUserName>
                 <CreateTime>%s</CreateTime>
                 <MsgType><![CDATA[%s]]></MsgType>
                 <Content><![CDATA[%s]]></Content>
                 <EventKey><![CDATA[%s]]></EventKey>
                 </xml>
                 ";
        $strtype = 'text';
        $time = time();
        $resultstr = sprintf($tousertext, $obj->FromUserName, $obj->ToUserName, $time, $strtype, $content, $eventkey);
        echo $resultstr;
    }

    /**
     * 客服信息
     */
    public function service_response($openid, $content = '', $type = '')
    {
        if ($type == 'image') {
//            $media_id = '67fx2J8rNyab5DfHGQyqpNIfpl30bVNmpi4Liq6GhW2tMD9XHM9kvpAFAcO82ShA';
            $media_id = $this->add_source($openid);
            $msg = '{
		    "touser":"' . $openid . '",
		    "msgtype":"' . $type . '",
		    "image":
			    {
			      "media_id":"' . $media_id . '"
			    }
			}';
        } else {
            $msg = '{
             "touser":"' . $openid . '",
             "msgtype":"text",
             "text":
                {
               	 "content":"' . $content . '"
                }
            }';
        }

        $token = $this->getaccesstoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $token;
        $res = $this->getcurl($url, 'post', $msg);
//        var_dump($res);
    }


    /**
     * 新增素材
     */
    public function add_source($openid, $type = '')
    {
        //$type为空表示临时，否则为永久
        if (empty($type)) {
            $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $this->getaccesstoken() . "&type=image";
        } else {
            $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . $this->getaccesstoken() . "&type=image";
        }
        if (class_exists('\CURLFile')) {
            $data = array('media' => new \CURLFile(realpath("./Public/poster/{$openid}.jpg")));
        } else {
            $data = array('media' => '@' . realpath("./Public/poster/{$openid}.jpg"));
        }
        $res = $this->getcurl($url, 'post', $data);
        $res = json_decode($res);
        $media_id = $res->media_id;
        return $media_id;
//        var_dump($res);
    }

    /**
     * 获取素材
     */
    public function get_source()
    {
        $media_id = 'oJ8dev6ri0W0jkTN0wDEvWoz7x3LEZhdYpej0ZEkxKT7Csw-KboKYgB_X9pwN_P2';
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getaccesstoken() . '&media_id=' . $media_id;
        $res = $this->getcurl($url);
        var_dump($res);
    }

    /**
     * 获取永久素材
     */
    public function get_forever_source()
    {
        $media_id = 'lxA-IA51LGbnYe2rkpB_OAqr3XtJU2sVtYGWhZZT8ZDtEbNDUliYS-XB0ejaPd5Z';
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=' . $this->getaccesstoken() . '&media_id=' . $media_id;
        $res = $this->getcurl($url);
        var_dump($res);
    }

    /**
     * 生成二维码海报
     */
    public function create_poster($openid, $headimg, $qrcode, $nickname)
    {
        if (!file_exists('./Public/poster/' . $openid . '.jpg')) {
            $img_path = "./Public/poster/";
            //缩放头像图片
//            $head = $this->resize_img($headimg, $img_path);
            //缩放二维码
//            $code = $this->resize_img($qrcode, $img_path);
            //不做缩放处理亦可

            $bai = imagecreatefromstring($this->getcurl(C('MYURL') . ltrim($img_path . 'poster.jpg'), './'));
            $logo = imagecreatefromstring($this->getcurl($headimg));
            $logo1 = imagecreatefromstring($this->getcurl($qrcode));

            $logo_width = imagesx($logo);//头像图片宽度
            $logo_height = imagesy($logo);//头像图片高度

            $logo_width1 = imagesx($logo1);//二维码图片宽度
            $logo_height1 = imagesy($logo1);//二维码图片高度

            imagecopyresampled($bai, $logo, 20, 180, 0, 0, 80, 80, $logo_width, $logo_height);
            imagecopyresampled($bai, $logo1, 103, 710, 0, 0, 250, 250, $logo_width1, $logo_height1);
            $img1 = 's' . time() . '.jpg';
            Imagejpeg($bai, $img_path . $img1);

            $img = @imagecreatefromjpeg($img_path . $img1);
//            $share_num = $user['wx_share_id'];
            ImageTTFText($img, 20, 0, 125, 220, ImageColorAllocate($img, 255, 255, 255), $img_path . 'yzb.ttf', $nickname);
            $last_img = $openid . '.jpg';//新图名
            Imagejpeg($img, $img_path . $last_img);
//            unlink($head_2);
//            unlink($head);
//            unlink($code);
            unlink($img_path . $img1);
            return $last_img;
//        echo '<img src="'.C('MYURL').'/Public/poster/'.$last_img.'">';
        }
    }

    /**
     * 缩放图片比例
     */
    public function resize_img($url, $path)
    {
        $imgname = $path . uniqid() . '.jpg';
        $file = $url;
        list($width, $height) = getimagesize($file); //获取原图尺寸
        $percent = (300 / $width);
        //缩放尺寸
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $src_im = imagecreatefromjpeg($file);
        $dst_im = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($dst_im, $imgname); //输出压缩后的图片
        imagedestroy($dst_im);
        imagedestroy($src_im);
        return $imgname;
    }

    /**
     * 获取二维码图片并将openid和图片路径存入数据库
     * @param $openid 微信用户openid
     * @param $eventkey 二维码参数
     */
    public function get_code($openid)
    {
        //如果该用户之前关注过，这次关注只是对之前数据进行更新
        $token = $this->getaccesstoken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
        $arr = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "' . $openid . '"}}}';
        $res = $this->getcurl($url, 'post', $arr);
        $res = json_decode($res, true);
        //获取二维码图片
        $ticket = $res['ticket'];
        $path = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
        return $path;
    }


    /**
     * 获取access_token
     * @return mixed
     */
    public function getaccesstoken()
    {
        $appid = $this->appid;;
        $appsecret = $this->appsecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $res = $this->getcurl($url);
        $resobj = json_decode($res);
        $_SESSION['access_token'] = $resobj->access_token;
        $_SESSION['expires_time'] = time() + 3600;
        return $_SESSION['access_token'];
//        var_dump($_SESSION['access_token']);

    }

    /**
     * @param $url
     * @param string $type 默认get，可填post
     * @param string $arr $type为post时需要传输的数组
     * @return 返回json数据
     */
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

    /**
     * 添加用户信息到数据库
     */
    public function add_user_db($openid, $eventkey)
    {
        $info = M('user', '', 'NBYH');
        $token = $this->getaccesstoken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
        $res = $this->getcurl($url);
        $res = json_decode($res, true);
        $check = $info->WHERE(['openid' => $openid])->FIND();
        if (empty($check)) {
            //获取推荐用户的的openid
            if (isset($eventkey)) {
                $eventkey = trim($eventkey, 'qrscene_');
                if (!empty($eventkey)) {
                    $data['openid'] = $openid;
                    $data['pre_openid'] = $eventkey;
                    $data['ctime'] = time();
                    M('user_team', '', 'NBYH')->ADD($data);
                    //判断是否达到公星要求，若达到则给予响应的奖励
                    $this->check_star($eventkey);
                }
            } else {
                $eventkey = '';
            }
            $my_qrcode = $this->get_code($openid);
            $find=$info->ORDER("id desc")->getField('memberid');
            if(!empty($find)){
                $data['memberid'] = $find+rand(1,9);
            }else{
                $data['memberid']='8'.rand(1000000,9999999);
            }
            $data['openid'] = $res['openid'];

            $data['nickname'] = $res['nickname'];
            $data['sex'] = $res['sex'];
            $data['city'] = $res['city'];
            $data['provice'] = $res['provice'];
            $data['country'] = $res['country'];
            $data['headimg'] = $res['headimgurl'];
            $data['my_qrcode'] = $my_qrcode;
            $data['recommend_openid'] = $eventkey;
            $data['ctime'] = time();
            $result = $info->add($data);
            return $result;
        } else {
            $recommend_openid = $info->WHERE(['openid' => $openid])->getField('recommend_openid');
            //还没有推荐人的情况下才执行，有推荐人的情况下则不会再修改推荐人
            if(empty($recommend_openid)){
                if (isset($eventkey)) {
                    $eventkey = trim($eventkey, 'qrscene_');
                    //避免自己关注自己
                    if ($openid == $eventkey) {
                        $eventkey = '';
                    } else {
                        //获取该用户的所有下级
                        $arr=$info->SELECT();
                        $arr=get_new_array($arr,$openid);
                        $arr=array_column($arr,'openid');
                        //判断是否是该用户的下级
                        if (in_array($eventkey,$arr)) {
                            $eventkey = '';
                        }
                    }
                } else {
                    $eventkey = '';
                }
                if(!empty($eventkey)){
                    if (!empty($eventkey)) {
                        $data['openid'] = $openid;
                        $data['pre_openid'] = $eventkey;
                        $data['ctime'] = time();
                        M('user_team', '', 'NBYH')->ADD($data);
                        //判断是否达到公星要求，若达到则给予响应的奖励
                        $this->check_star($eventkey);
                    }
                    $info->WHERE(['openid' => $openid])->setField('recommend_openid',$eventkey);
                }
            }
            $info->WHERE(['openid' => $openid])->setField('status', '1');
            return false;
        }
    }

    /**
     * 取消关注
     */
    public function cancel_subscribe($openid)
    {
        $info = M('user', '', 'NBYH');
        $info->WHERE(['openid' => $openid])->setField('status', '2');
    }

    /**
     * 判断该openid之前是否已关注过该公众号
     */
    public function check($openid)
    {
        $info = M('user', '', 'NBYH');
        $res = $info->WHERE(['openid' => $openid])->FIND();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否达到公星要求
     * 发展6个直推下级代理，成为公星，并进行奖励（每增加6人奖励3万，人工操作）
     */
    public function check_star($pre_openid){
        $info= M('user_team', '', 'NBYH');
        $user=M('user','','NBYH');
        $count=$info->WHERE(['pre_openid'=>$pre_openid])->COUNT();
        if($count>=6){
            $user->WHERE(['openid'=>$pre_openid])->setField('is_star','1');
        }
    }

}

?>