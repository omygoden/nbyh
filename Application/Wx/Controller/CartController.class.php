<?php
namespace Wx\Controller;
use Think\Controller;
class CartController extends CommenController {
    /**
     *购物车页面
     */
    public function mycart(){
        $this->set_openid();
        $is_distribution=M('user','','NBYH')->WHERE(['openid'=>$_SESSION['openid']])->getField('is_distribution');
        if($is_distribution!='1' || empty($is_distribution)){
            $this->error('目前只支持分销商访问。',U('Myinfo/myinfo'));
            exit();
        }
        $info=D('MycartView');
//        $info=new \Wx\ViewModel\MycartViewModel();
        $openid=$_SESSION['openid'];
        $list=$info->WHERE(['c.openid'=>$openid,'c.status'=>'1','g.status'=>'1'])->ORDER("c.id desc")->SELECT();
//        var_dump($list);

        $this->assign(array(
            'list'=>$list
        ));
        $this->display();
    }

    /**
     * 减少购物车里的商品数量
     */
    public function reduce_cart_num(){
        $info=D('Cart');
        if(!empty($_POST)){
            $openid=$_SESSION['openid'];
            $cid=I('id');
            $cart=$info->WHERE(['id'=>$cid,'openid'=>$openid])->FIND();
            if($cart['num']<='1'){
                $res=$info->WHERE(['id'=>$cid,'openid'=>$openid])->setField('status','2');
            }else{
                $res=$info->WHERE(['id'=>$cid,'openid'=>$openid])->setDec('num','1');
            }
            if($res!==false){
                $this->ajaxReturn(['code'=>'1001','result'=>'修改成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'修改失败']);
            }
        }
    }

    /**
     * 增加购物车里的商品数量
     */
    public function add_cart_num(){
        $info=D('Cart');
        if(!empty($_POST)){
            $openid=$_SESSION['openid'];
            $cid=I('id');
            $cart=$info->WHERE(['id'=>$cid,'openid'=>$openid])->FIND();
            $stock=M('goods','','NBYH')->WHERE(['id'=>$cart['goods_id']])->getField('stock');
            if($cart['num']+1>$stock){
                $this->ajaxReturn(['code'=>'1002','result'=>'库存不足']);
                return false;
            }
            $res=$info->WHERE(['id'=>$cid,'openid'=>$openid])->setInc('num','1');
            if($res!==false){
                $this->ajaxReturn(['code'=>'1001','result'=>'修改成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'修改失败']);
            }
        }
    }

    /**
     * 购物车点击结算之后，清空已选中的购物车商品
     */
    public function clear_cart(){
        $info=M('cart','','NBYH');
        if(!empty($_POST)){
            $cid=I('cid');
            $cid=str_replace('|',',',trim($cid,'|'));
            $res=$info->WHERE("id in({$cid})")->setField('status','2');
            if($res!==false){
                $this->ajaxReturn(['code'=>'1001','result'=>'提交成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'提交失败']);
            }
        }
    }


}