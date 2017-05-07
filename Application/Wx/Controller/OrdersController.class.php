<?php
namespace Wx\Controller;

use Think\Controller;

class OrdersController extends CommenController
{
    /**
     * 我的订单
     */
    public function my_orders()
    {
        $this->set_openid();
        $info = M('orders', '', 'NBYH');
        $openid = $_SESSION['openid'];
        $status = I('get.status');
        if($status=='4'){
            $where['status']=array('between',array(4,5));
        }else{
            $where['status']=array('eq',$status);
        }
        $where['openid']=array('eq',$openid);
        $row = '6';
        $list = $info->WHERE($where)->ORDER("ctime desc")->LIMIT(0, $row)->SELECT();
        foreach ($list as $k => $v) {
            $list[$k]['goods']=D('OrdergoodsView')->WHERE(['oe.order_no' => $v['order_no']])->SELECT();
            if($v['status']=='4' || $v['status']=='5'){
                $refund=M('apply_refund','','NBYH')->WHERE(['order_no'=>$v['order_no']])->FIND();
                $list[$k]['rtime']=$refund['ctime'];
                $list[$k]['check_opinion']=$refund['check_opinion'];
                $list[$k]['check_time']=$refund['check_time'];
            }
        }
        $this->assign(array(
            'list' => $list
        ));
        $this->display();
    }

    /**
     * 加载更多订单
     */
    public function load_more()
    {
        $info = M('orders', '', 'NBYH');
        $openid = $_SESSION['openid'];
        if (!empty($_POST)) {
            $row = 6;
            $p = I('p');
            $status = I('status');
            $start = $p * $row;
            if($status=='4'){
                $where['status']=array('between',array(4,5));
            }else{
                $where['status']=array('eq',$status);
            }
            $where['openid']=array('eq',$openid);
            $list = $info->WHERE($where)->ORDER("ctime desc")->LIMIT($start, $row)->SELECT();
            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $list[$k]['ctime']=date('Y-m-d H:i:s',$v['ctime']);
                    $list[$k]['goods']=D('OrdergoodsView')->WHERE(['oe.order_no' => $v['order_no']])->SELECT();
                    if($v['status']=='4' || $v['status']=='5'){
                        $refund=M('apply_refund','','NBYH')->WHERE(['order_no'=>$v['order_no']])->FIND();
                        $list[$k]['rtime']=date('Y-m-d H:i:s',$refund['ctime']);
                        $list[$k]['check_opinion']=!empty($refund['check_opinion'])?$refund['check_opinion']:'待审核...';
                        $list[$k]['check_time']=!empty($refund['check_time'])?date('Y-m-d H:i:s',$refund['check_time']):'';
                    }
                }
            }
            if (!empty($list)) {
                $this->ajaxReturn(['code' => '1001', 'result' => $list]);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '已无更多数据']);
            }
        }
    }

    /**
     * 确定收货
     */
    public function sure_accept(){
        $info=M('orders','','NBYH');
        if(!empty($_POST)){
            $order_no=I('order_no');
            $data['status']='3';
            $data['end_time']=time();
            $res=$info->WHERE(['order_no'=>$order_no])->SAVE($data);
            if($res!==false){
                $this->ajaxReturn(['code'=>'1001','result'=>'提交成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'系统错误，请重新进入公众号']);
            }
        }
    }

    /**
     * 申请退货
     */
    public function goods_refund(){
        $this->set_openid();
        $info=M('orders','','NBYH');
        $order_no=I('get.order_no');
        if(!empty($order_no)){
            $order=$info->WHERE(['order_no'=>$order_no])->FIND();
            if($order['status']!='3'){
                $this->error('系统错误');
                return false;
            }
            $goods=M('orders_ext','','NBYH')->JOIN("oe JOIN goods g ON oe.goods_id=g.id")->WHERE(['oe.order_no'=>$order_no])->FIELD("oe.*,g.name")->SELECT();
            $order['goods_name']=count($goods)>1?$goods[0]['name'].'等'.count($goods).'个商品':$goods[0]['name'];
            $this->assign(array(
                'order'=>$order
            ));
            $this->display();
        }else{
            $this->error('系统错误');
        }
    }

    /**
     * 提交退货申请
     */
    public function commit_refund_apply(){
        $info=D('Refund');
        if(!empty($_POST)){
            $info->startTrans();
            $data['openid']=$_SESSION['openid'];
            $data['order_no']=I('order_no');
            $data['reason']=I('reason');
            $data['ctime']=time();
            $res=$info->create($data,'1');
            if($res){
                $res=$info->add();
                $upd=M('orders','','NBYH')->WHERE(['order_no'=>I('order_no')])->setField('status','4');
                if($res && $upd!==false){
                    $info->commit();
                    $this->ajaxReturn(['code'=>'1001','result'=>'提交成功']);
                }else{
                    $info->rollback();
                    $this->ajaxReturn(['code'=>'1002','result'=>'提交失败']);
                }
            }else{
                $info->rollback();
                $this->ajaxReturn(['code'=>'1002','result'=>$info->getError()]);
            }
        }
    }


    /**
     * 确认订单（还未生成订单）
     */
    public function confirm_order()
    {
        $this->set_openid();
        $info = M('goods_size', '', 'NBYH');
        $address = M('user_address', '', 'NBYH');
        $openid = $_SESSION['openid'];
        $goods_id = explode('|', I('get.goods_id'));
        $gs_id = explode('|', I('get.gs_id'));
        $price = explode('|', I('get.price'));
        $num = explode('|', I('get.num'));
        $ourl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ourl=base64_encode($ourl);
        if (!empty($gs_id) && !empty($price) && !empty($num) && !empty($goods_id)) {
            foreach ($gs_id as $k => $v) {
//                $goods['name']=$info->JOIN("gs JOIN goods g ON gs.goods_id=g.id")->JOIN("JOIN size s ON gs.sid=s.id")->WHERE(['gs.id'=>$gs_id])->FIELD('g.name,s.name as sname')->SELECT();
                $res = D('GoodssizeView')->WHERE(['gs.id' => $gs_id[$k]])->FIND();
                $goods['goods_id'] = $goods_id[$k];
                $goods['gs_id'] = $gs_id[$k];
                $goods['price'] = $price[$k];
                $goods['num'] = $num[$k];
                $goods['name'] = $res['name'];
                $goods['sname'] = $res['sname'];
                $goods['title_img'] = $res['title_img'];
                $goods['all_price'] = $price[$k] * $num[$k];
                $all_goods[] = $goods;
            }
            $tol_price = array_sum(array_column($all_goods, 'all_price'));
            $tol_num = array_sum(array_column($all_goods, 'num'));
            $myaddress = $address->WHERE(['openid' => $openid, 'type' => '2'])->FIND();
            if (!empty($myaddress)) {
                $area = $myaddress['area_id'];
                $all_area = M('area', '', 'NBYH')->WHERE("id in({$area})")->getField('name', true);
                $myaddress['all_area'] = implode('-', $all_area) . '-' . $myaddress['detail'];
            }

            $this->assign(array(
                'all_goods' => $all_goods,
                'myaddress' => $myaddress,
                'tol_price' => $tol_price,
                'tol_num' => $tol_num,
                'ourl'=>$ourl
            ));
            $this->display();
        } else {
            $this->error('参数错误，请重新进入商城', U('Shop/shop'));
        }
    }

    /**
     * 提交订单
     */
    public function commit_order()
    {
        $info = D('Orders');
        if (!empty($_POST)) {
            //防止表单重复提交
//            if(!$info->autoCheckToken($_POST['__hash__'])){
//                $this->ajaxReturn(['code'=>'1002','result'=>'请勿重复提交表单']);
//                return false;
//            }
            $info->startTrans();
            $goods_id = explode('|', I('goods_id'));
            $gs_id = explode('|', I('gs_id'));
            $num = explode('|', I('num'));
            $address_id = I('address_id');
            $remark = I('remark');
            $tol_price = I('tol_price');
            if (count($goods_id) != count($gs_id) || count($gs_id) != count($num) || count($goods_id) != count($num)) {
                $this->ajaxReturn(['code' => '1002', 'result' => '参数有误，请重新去结算']);
                return false;
            }
            //核对订单总额是否正确
            $check = $info->check_price($gs_id, $num, $tol_price);
            if (!$check) {
                $this->ajaxReturn(['code' => '1002', 'result' => '参数有误，请重新去结算']);
                return false;
            }
            $order['openid'] = $_SESSION['openid'];
            $order['order_no'] = 'NB' . date('YmdHis',time()) . rand(1000, 9999);
            $order['address_id'] = $address_id;
            $order['money'] = $tol_price;
            $order['remark'] = $remark;
            $order['ctime'] = time();
            $res = $info->create($order, '1');
            if (!$res) {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => $info->getError()]);
                return false;
            }
            $res = $info->add();
            if (!$res) {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '订单生成失败']);
                return false;
            }
            //判断商品是否上架，并检查库存是否足够
            foreach ($goods_id as $k => $v) {
                $check = $info->check_goods($goods_id[$k], $gs_id[$k], $num[$k]);
                if ($check != '1001') {
                    $this->ajaxReturn(['code' => '1002', 'result' => $check]);
                    return false;
                }
                $o_ext['order_no'] = $order['order_no'];
                $o_ext['goods_id'] = $goods_id[$k];
                $o_ext['gs_id'] = $gs_id[$k];
                $o_ext['num'] = $num[$k];
                $order_ext[] = $o_ext;
            }
            $res = M('orders_ext', '', 'NBYH')->addAll($order_ext);
            if ($res) {
                commit_notice($_SESSION['openid'],$order['order_no'],$tol_price);
                $info->commit();
                $this->ajaxReturn(['code' => '1001', 'result' => $order['order_no']]);
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '订单生成失败']);
            }
//            $this->display();
        }

        $this->display();
    }

    /**
     * 支付订单
     */
    public function to_pay()
    {
        $this->set_openid();
        $info = D('Orders');
        $order_no = I('get.order_no');
        if (!empty($order_no)) {
            $openid = $_SESSION['openid'];
            $score = M('user', '', 'NBYH')->WHERE(['openid' => $openid])->getField('score');
            $tol_price = $info->WHERE(['order_no' => $order_no])->getField('money');

            $this->assign(array(
                'score' => $score,
                'tol_price' => $tol_price
            ));
            $this->display();
        }
    }

    /**
     * 支付操作
     */
    public function pay()
    {
        $info = D('Orders');
        $appid = C('APPID');
        $appsecret = C('APPSECRET');
        if (!empty($_POST)) {
            $order_no = I('order_no');
            $type = I('type');
            $order = $info->WHERE(['order_no' => $order_no])->FIND();
            $score = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->getField('score');
            if ($order['status'] > 0) {
                $this->ajaxReturn(['code' => '1002', 'result' => '请勿重复支付订单']);
                return false;
            }
            if ($type == 'balance') {
                if ($order['money'] > $score) {
                    $this->ajaxReturn(['code' => '1002', 'result' => '余额不足']);
                    return false;
                }
                $res = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->setDec('score', $order['money']);
                if ($res !== false) {
                    $this->ajaxReturn(['code' => '1000', 'result' => '支付成功']);
                } else {
                    $this->ajaxReturn(['code' => '1002', 'result' => '支付失败']);
                }
            } else {
                $title = "订单支付";
                $amount = (int)$order['money'];//支付总价
                $out_trade_no = $order_no;//订单号，需要保证唯一性
                //1.生成sign
                $sign = md5($appid . $title . $amount . $out_trade_no . $appsecret);
                $data['title'] = $title;
                $data['amount'] = $amount;
                $data['sn'] = $out_trade_no;
                $data['sign'] = $sign;
                $data['openid'] = $_SESSION['openid'];
                $this->ajaxReturn(['code' => '1001', 'result' => $data]);
            }
        }

    }
}