<?php
namespace Admin\Controller;

use Think\Controller;

class SendsController extends CommenController
{

    /**
     * 批量推送信息
     */
    public function sendall()
    {
        $this->display();
    }

    /**
     * 批量推送信息操作
     */
    public function send_all()
    {
        if (!empty($_POST)) {
            $title = I('title');
            $content = I('content');
            if(!empty($title) && !empty($content)){
                $mycontent='群推送操作';
                addlog($_SESSION['id'],$mycontent);
                $this->send($title, $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '发送成功']);
            }else{
                $this->ajaxReturn(['code' => '1002', 'result' => '标题或者内容不能为空']);
            }
        }
    }


}