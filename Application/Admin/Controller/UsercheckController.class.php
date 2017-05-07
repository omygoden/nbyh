<?php
namespace Admin\Controller;
use Think\Controller;
class UsercheckController extends CommenController {
    /**
     * 用户认证列表
     */
    public function user_cert_check(){
        $info=M('user_cert','','NBYH');
        if(!empty(I('content'))){
            $content=I('content');
//            $where['u.account']=array('eq',I('content'));
            $where['_string']="uc.name='$content' or u.nickname='$content' or uc.check_name='$content'";
        }
        if(!empty(I('get.status')||I('get.status')=='0')){
            $where['uc.status']=array('eq',I('get.status'));
        }else{
            $where['uc.status']=array('neq','1');
        }

        $tol=$info->JOIN("uc JOIN user u ON uc.openid=u.openid")->WHERE($where)->COUNT();
        $row='10';
        $page=new PageController($tol,$row);
        $fpage=$page->fpage();
        $list=$info->JOIN("uc JOIN user u ON uc.openid=u.openid")->WHERE($where)->FIELD("uc.*,u.nickname,u.headimg")->ORDER('uc.id')->LIMIT($page->listfirst,$page->listRows)->SELECT();

        $this->assign(array(
            'list'=>$list,
            'fpage'=>$fpage,
            'tol'=>$tol
        ));
        $this->display();
    }



    /**
     * 用户认证审核意见操作
     */
    public function sub_cert_check(){
        $info=M('user_cert','','NBYH');
        $user=M('user','','NBYH');
        if(!empty($_POST)){
            $info->startTrans();
            $openid=I('openid');
            $data['check_opinion']=I('opinion');
            $data['check_name']=$_SESSION['name'];
            $data['status']=I('status');
            $data['check_time']=time();
            $res1=$info->WHERE(['openid'=>$openid])->SAVE($data);
            if(I('status')=='1'){
                $res2=$user->WHERE(['openid'=>$openid])->setField('is_cert','1');
            }else{
                $res2=$user->WHERE(['openid'=>$openid])->setField('is_cert','4');
            }
            if($res1!==false && $res2!==false){
                switch(I('status')){
                    case 1:
                        $status='通过';
                        break;
                    case 2:
                        $status='驳回';
                        break;
                    default:
                        $status='';
                        break;
                }
                $nickname=$user->WHERE(['openid'=>$openid])->getField('nickname');
                $content='审核'.$nickname.'认证信息。操作内容：'.$status;
                addlog($_SESSION['id'],$content);
                $title='用户认证';
                $result=$status;
                $remark=I('opinion');
                check_notice($openid,$title,$nickname,$result,$remark);
                $info->commit();
                $this->ajaxReturn(['code'=>'1001','result'=>'提交成功']);
            }else{
                $info->rollback();
                $this->ajaxReturn(['code'=>'1002','result'=>'提交失败']);
            }
        }
    }

    /**
     * 分销商申请
     */
    public function apply_distribution_check(){
        $info=M('apply_distribution','','NBYH');
        if(!empty(I('content'))){
            $content=I('content');
//            $where['u.account']=array('eq',I('content'));
            $where['_string']="uc.name='$content' or uc.nickname='$content' or uc.check_name='$content'";
        }
        if(!empty(I('get.status')||I('get.status')=='0')){
            $where['uc.status']=array('eq',I('get.status'));
        }else{
            $where['uc.status']=array('neq','1');
        }

        $tol=$info->JOIN("uc JOIN user u ON uc.openid=u.openid")->WHERE($where)->COUNT();
        $row='10';
        $page=new PageController($tol,$row);
        $fpage=$page->fpage();
        $list=$info->JOIN("uc JOIN user u ON uc.openid=u.openid")->WHERE($where)->FIELD("uc.*,u.nickname,u.headimg")->ORDER('uc.id')->LIMIT($page->listfirst,$page->listRows)->SELECT();

        $this->assign(array(
            'list'=>$list,
            'fpage'=>$fpage,
            'tol'=>$tol
        ));
        $this->display();
    }



    /**
     * 分销商审核意见操作
     */
    public function sub_distribution_check(){
        $info=M('apply_distribution','','NBYH');
        $user=M('user','','NBYH');
        if(!empty($_POST)){
            $info->startTrans();
            $openid=I('openid');
            $data['check_opinion']=I('opinion');
            $data['check_name']=$_SESSION['name'];
            $data['status']=I('status');
            $data['check_time']=time();
            $res1=$info->WHERE(['openid'=>$openid])->SAVE($data);
            if(I('status')=='1'){
                $res2=$user->WHERE(['openid'=>$openid])->setField('is_distribution','1');
            }else{
                $res2=$user->WHERE(['openid'=>$openid])->setField('is_distribution','4');
            }
            if($res1!==false && $res2!==false){
                switch(I('status')){
                    case 1:
                        $status='通过';
                        break;
                    case 2:
                        $status='驳回';
                        break;
                    default:
                        $status='';
                        break;
                }
                $nickname=$user->WHERE(['openid'=>$openid])->getField('nickname');
                $content='审核'.$nickname.'分销商申请。操作内容：'.$status;
                addlog($_SESSION['id'],$content);
                $title='分销商申请';
                $result=$status;
                $remark=I('opinion');
                check_notice($openid,$title,$nickname,$result,$remark);
                $info->commit();
                $this->ajaxReturn(['code'=>'1001','result'=>'提交成功']);
            }else{
                $info->rollback();
                $this->ajaxReturn(['code'=>'1002','result'=>'提交失败']);
            }
        }
    }


}