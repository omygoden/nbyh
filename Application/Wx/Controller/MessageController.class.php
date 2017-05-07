<?php
namespace Wx\Controller;
use Think\Controller;
class MessageController extends CommenController {
    /**
     *后台主页
     */
    public function message_list(){
        $this->display();
    }


}