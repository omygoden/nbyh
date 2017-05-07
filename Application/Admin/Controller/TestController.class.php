<?php
namespace Admin\Controller;

use Think\Controller;

class TestController extends Controller
{
//     public function __construct()
//     {
//         parent::__construct();
//         if (empty($_SESSION['id'])) {
//             $this->redirect('Login/login');
//         }
//     }
    public function _empty(){
        $this->redirect('indexam.php/Alerterror/error404');
    }

    public function test1()
    {
        $res=md5('123456'.'nbyh');
        var_dump($res);
    }

    public function test2(){
//        unlink('./Public/uploadimg/2017-04-06/58e5e564d0e18.jpg');
    }
    public function test3(){
        $letters = array('a', 'p');
        $fruit   = 'apple';
        $text    = 'a p';
        $output  = str_replace($letters, $fruit, $text);
        var_dump($output);
    }

    public function test4(){
        $url=urlencode('http://nuoya.86tudi.com/indexadm.php/Index/index');
        var_dump($url);
    }
    public function test5(){
        var_dump(strlen('http://wx.qlogo.cn/mmopen/daibZxOVtgjgdBbdj3A2H0Fmic1fqVAqTbKahmE2MYMFT9ChKVTbicX05gwCQTgqb2SHlngjjpMFOqCm4uSoHxj2eCrHIcyFgJa/0'));
    }

}