<?php
namespace Wx\Controller;
use Think\Controller;
class ShopController extends CommenController {
    /**
     *商城首页
     */
    public function shop(){
        $this->set_openid();
        $is_distribution=M('user','','NBYH')->WHERE(['openid'=>$_SESSION['openid']])->getField('is_distribution');
        if($is_distribution!='1' || empty($is_distribution)){
            $this->error('目前只支持分销商访问。',U('Myinfo/myinfo'));
            exit();
        }
        $info=M('goods','','NBYH');
        $row=4;
        $top=$info->WHERE(['is_top'=>'1','status'=>'1'])->ORDER('id desc')->SELECT();
        $goods=$info->WHERE(['is_top'=>'2','status'=>'1'])->ORDER('id desc')->LIMIT(0,$row)->SELECT();
//        var_dump($top);
        $this->assign(array(
            'top'=>$top,
            'goods'=>$goods
        ));
        $this->display();
    }

    public function load_more(){
        $info=M('goods','','NBYH');
        if(!empty($_POST)){
            $row=4;
            $p=I('p');
            $start=$p*$row;
            $goods=$info->WHERE(['is_top'=>'2','status'=>'1'])->ORDER('id desc')->LIMIT($start,$row)->SELECT();
            if(!empty($goods)){
                $this->ajaxReturn(['code'=>'1001','result'=>$goods]);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'已无更多数据']);
            }
        }
    }


}