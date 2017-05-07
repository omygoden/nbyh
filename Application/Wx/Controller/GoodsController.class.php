<?php
namespace Wx\Controller;

use Think\Controller;

class GoodsController extends CommenController
{
    /**
     *商品展示
     */
    public function goods_show()
    {
        $this->set_openid();
        $is_distribution=M('user','','NBYH')->WHERE(['openid'=>$_SESSION['openid']])->getField('is_distribution');
        if($is_distribution!='1' || empty($is_distribution)){
            $this->error('目前只支持分销商访问。',U('Myinfo/myinfo'));
            exit();
        }
        $info=M('goods','','NBYH');
        $row='6';
        $list=$info->WHERE(['status'=>'1'])->ORDER('id desc')->LIMIT(0,$row)->SELECT();

        $this->assign(array(
            'list'=>$list
        ));
        $this->display();
    }

    /**
     * 加载更多
     */
    public function load_more(){
        $info=M('goods','','NBYH');
        if(!empty($_POST)){
            $row=6;
            $p=I('p');
            $start=$p*$row;
            $goods=$info->WHERE(['status'=>'1'])->ORDER('id desc')->LIMIT($start,$row)->SELECT();
            if(!empty($goods)){
                $this->ajaxReturn(['code'=>'1001','result'=>$goods]);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'已无更多数据']);
            }
        }
    }

    /**
     * 加载更多商品
     */

    /**
     * 商品详情
     */
    public function goods_detail(){
        $this->set_openid();
        $is_distribution=M('user','','NBYH')->WHERE(['openid'=>$_SESSION['openid']])->getField('is_distribution');
        if($is_distribution!='1' || empty($is_distribution)){
            $this->error('目前只支持分销商访问。',U('Myinfo/myinfo'));
            exit();
        }
        $info=M('goods','','NBYH');
        $goods_detail_img=M('goods_detail_img','','NBYH');
        $goods_size=M('goods_size','','NBYH');
        $gid=I('gid');
        if(!empty($gid)){
            $goods=$info->WHERE(['id'=>$gid])->FIND();
            $goods['detail_imgs']=$goods_detail_img->WHERE(['goods_id'=>$gid])->SELECT();
            $goods['goods_size']=$goods_size->JOIN("gs JOIN size s ON gs.sid=s.id")->WHERE(['gs.goods_id'=>$gid])->FIELD("gs.*,s.name")->SELECT();
            $goods['goods_size']=!empty($goods['goods_size'])?$goods['goods_size']:'';
            $goods['detail']=M('goods_detail','','NBYH')->WHERE(['goods_id'=>$gid])->getField('detail');
            $this->assign(array(
                'goods'=>$goods
            ));
            $this->display();
        }else{
            $this->error('参数错误');
        }
    }

    /**
     * 添加到购物车
     */
    public function add_cart(){
        $info=D('Cart');
        if(!empty($_POST)){
            $openid = $_SESSION['openid'];
            if (empty($openid)) {
                echo '系统出错，请重新关注公众号';
                return false;
            }
            $res=$info->check_num(I('gs_id'),I('num'),$openid);
            if(!$res){
                $this->ajaxReturn(['code'=>'1002','result'=>'库存不足']);
                return false;
            }
            $res=$info->check_status(I('goods_id'));
            if(!$res){
                $this->ajaxReturn(['code'=>'1002','result'=>'商品已下架']);
                return false;
            }
            $res=$info->check_cart(I('gs_id'),$openid);
            if($res){
                $data['num']=I('num')+$res['num'];
                $result=$info->create($data,'2');
                if(!$result){
                    $this->ajaxReturn(['code'=>'1002','result'=>$info->getError()]);
                    return false;
                }
                $result=$info->WHERE(['gs_id'=>I('gs_id'),'openid'=>$openid])->SAVE();
                if($result!==false){
                    $this->ajaxReturn(['code'=>'1001','result'=>'添加成功']);
                }else{
                    $this->ajaxReturn(['code'=>'1002','result'=>'添加失败']);
                }
            }else{
                $data['goods_id']=I('goods_id');
                $data['openid']=$openid;
                $data['gs_id']=I('gs_id');
                $data['ctime']=time();
                $data['num']=I('num');
                $data['price']=I('price');
                $result=$info->create($data,'1');
                if(!$result){
                    $this->ajaxReturn(['code'=>'1002','result'=>$info->getError()]);
                    return false;
                }
                $result=$info->add();
                if($result){
                    $this->ajaxReturn(['code'=>'1001','result'=>'添加成功']);
                }else{
                    $this->ajaxReturn(['code'=>'1002','result'=>'添加失败']);
                }
            }
        }
    }



}