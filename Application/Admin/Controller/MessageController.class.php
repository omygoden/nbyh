<?php
namespace Admin\Controller;

use Think\Controller;

class MessageController extends CommenController
{
    /**
     *管理员操作日志
     */
    public function admin_log(){
        $info=M('admin_log','','NBYH');
        if (!empty(I('content'))) {
            $content=I('content');
//            $where['a.name'] = array('eq', I('content'));
            $where['_string'] = "a.name='$content' or a.phone='$content'";
        }
        $tol=$info->JOIN("al JOIN admin a ON al.aid=a.id")->JOIN("LEFT JOIN role r ON a.rid=r.id")->WHERE($where)->COUNT();
        $row='20';
        $page=new PageController($tol,$row);
        $fpage=$page->fpage();
        $list=$info->JOIN("al JOIN admin a ON al.aid=a.id")->JOIN("LEFT JOIN role r ON a.rid=r.id")->WHERE($where)->FIELD("al.*,a.name,a.phone,a.rid,r.rolename")->ORDER('al.id desc')->LIMIT($page->listfirst,$page->listRows)->SELECT();
        foreach($list as $k=>$v){
            if($v['rid']=='99'){
                $list[$k]['rolename']='超级管理员';
            }
        }
        $this->assign(array(
            'list'=>$list,
            'fpage'=>$fpage,
            'tol'=>$tol
        ));
        $this->display();

    }

    /**
     * 客服电话
     */
    public function service_number(){
        $info=M('service','','NBYH');
        $phone=$info->WHERE(['id'=>'1'])->getField('phone');
        if(!empty($_POST)){
            $phone=I('phone');
            $res=$info->WHERE(['id'=>1])->setField('phone',$phone);
            if($res!==false){
                $this->redirect('indexadm.php/Message/service_number');
            }else{
                $this->error('修改失败');
            }
        }
        $this->assign(array(
            'phone'=>$phone
        ));
        $this->display();
    }

    public function test(){
        $this->display();
    }
    public function test1(){
        echo '<p>第一页</p>';
    }
}