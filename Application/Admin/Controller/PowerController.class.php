<?php
namespace Admin\Controller;
use Think\Controller;
class PowerController extends Controller {

//    public function __construct()
//    {
//        parent::__construct();
//        if(empty($_SESSION['id'])){
//            $this->redirect('indexadm.php/Login/login');
//        }else{
//            if($_SESSION['rid']!='99'){
//                $this->redirect('indexadm.php/Login/login');
//            }
//        }
//    }

    /**
     * 添加权限页面
     */
    public function power(){
        $info=M('power','','NBYH');
        $list=$info->WHERE(['status'=>1])->ORDER("weight asc")->SELECT();
        $list=get_new_arrays($list);

        $this->assign(array(
            'list'=>$list
        ));
        $this->display();
    }

    /**
     * 添加权限操作
     */
    public function add_power(){
        $info=M('power','','NBYH');
        if(!empty($_POST)){
            $data['name']=I('name');
            $data['actionname']=I('actionname');
            $pid=I('pid');
            if(empty($pid)){
                $data['pid']='0';
                $data['level']='1';
            }else{
                $data['pid']=$pid;
                $level=$info->WHERE(['id'=>$pid])->getField('level');
                $data['level']=$level+1;
            }
            $data['ctime']=time();
            $res=$info->add($data);
            if($res){
                $info->WHERE(['id'=>$res])->setField('weight',$res);
                $this->ajaxReturn(['code'=>'1001','result'=>'添加成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'添加失败']);
            }
        }
    }

}