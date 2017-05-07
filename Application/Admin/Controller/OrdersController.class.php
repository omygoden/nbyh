<?php
namespace Admin\Controller;

use Think\Controller;

class OrdersController extends CommenController
{
    /**
     *待付款订单
     */
    public function nopay_order()
    {
        $info = M('orders', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.nickname='$content' or o.order_no='$content'";
        }
        $where['o.status'] = array('eq', '0');
        $tol = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->FIELD("o.*,u.headimg,u.nickname,ua.name,ua.mobile,ua.area_id,ua.detail")->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        foreach ($list as $k => $v) {
            $address = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
            $list[$k]['address'] = implode('-', $address) . '-' . $v['detail'];
            if ($v['status'] >= '4' && $v['status'] <= 5) {

            }
        }
//        var_dump($list);
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 待发货订单列表
     */
    public function nodeliver_order()
    {
        $info = M('orders', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.nickname='$content' or o.order_no='$content'";
        }
        $where['o.status'] = array('eq', '1');
        $tol = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->FIELD("o.*,u.headimg,u.nickname,ua.name,ua.mobile,ua.area_id,ua.detail")->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        foreach ($list as $k => $v) {
            $address = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
            $list[$k]['address'] = implode('-', $address) . '-' . $v['detail'];
        }
//        var_dump($list);
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 订单发货操作
     */
    public function deliver_goods()
    {
        $info = M('orders', '', 'NBYH');
        if (!empty($_POST)) {
            $order_no = I('order_no');
            $data['express_name'] = I('express_name');
            $data['express_no'] = I('express_no');
            $data['start_time'] = time();
            $data['status'] = '2';
            if (!empty(I('express_name') && !empty(I('express_no')))) {
                $res = $info->WHERE(['order_no' => $order_no])->SAVE($data);
                if ($res !== false) {
                    $order = $info->WHERE(['order_no' => $order_no])->FIND();
                    shipping_notice($order['openid'], $order_no, I('express_name'), I('express_no'));
                    addlog($_SESSION['id'], '订单' . $order_no . '发货操作');
                    $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
                } else {
                    $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
                }
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '快递公司或者快递单号不能为空']);
            }
        }
    }

    /**
     * 待收货订单
     */
    public function noaccept_order()
    {
        $info = M('orders', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.nickname='$content' or o.order_no='$content'";
        }
        $where['o.status'] = array('eq', '2');
        $tol = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->FIELD("o.*,u.headimg,u.nickname,ua.name,ua.mobile,ua.area_id,ua.detail")->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        foreach ($list as $k => $v) {
            $address = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
            $list[$k]['address'] = implode('-', $address) . '-' . $v['detail'];
        }
//        var_dump($list);
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 已完成订单
     */
    public function over_order()
    {
        $info = M('orders', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.nickname='$content' or o.order_no='$content'";
        }
        $where['o.status'] = array('eq', '3');
        $tol = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->FIELD("o.*,u.headimg,u.nickname,ua.name,ua.mobile,ua.area_id,ua.detail")->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        foreach ($list as $k => $v) {
            $address = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
            $list[$k]['address'] = implode('-', $address) . '-' . $v['detail'];
        }
//        var_dump($list);
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 申请售后订单
     */
    public function apply_return_order()
    {
        $info = M('orders', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.nickname='$content' or o.order_no='$content'";
        }
        if (!empty(I('status') || I('status') == '0')) {
            $where['ar.status'] = array('eq', I('status'));
        }
        $where['o.status'] = array('between', array(4, 5));
        $tol = D("RefundorderView")->WHERE($where)->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();

//        $list = M('apply_refund','','NBYH')->JOIN("ar JOIN orders o ON ar.order_no=o.order_no")->JOIN("JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE($where)->FIELD("ar.reason,ar.ctime as rtime,ar.check_opinion,ar.check_time,ar.status as rstatus,ar.return_money,o.*,u.headimg,u.nickname,ua.name,ua.mobile,ua.area_id,ua.detail")->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        $list = D("RefundorderView")->WHERE($where)->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        foreach ($list as $k => $v) {
            $address = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
            $list[$k]['address'] = implode('-', $address) . '-' . $v['detail'];
        }

//        var_dump($list);
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 订单退款审核操作
     */
    public function refund_check()
    {
        $info = M('apply_refund', '', 'NBYH');
        $order = M('orders', '', 'NBYH');
        if (!empty($_POST)) {
            $info->startTrans();
            $order_no = I('order_no');
            $data['status'] = I('status');
            $data['check_opinion'] = I('check_opinion');
            $data['check_time'] = time();
            $res1 = $info->WHERE(['order_no' => $order_no])->SAVE($data);
            $res2 = $order->WHERE(['order_no' => $order_no])->setField('status', '5');
            if ($res1 !== false && $res2 !== false) {
                $orders = $order->WHERE(['order_no' => $order_no])->FIND();
                $goods = M('orders_ext', '', 'NBYH')->JOIN("oe JOIN goods g ON oe.goods_id=g.id")->WHERE(['oe.order_no' => $order_no])->FIELD("oe.*,g.name")->SELECT();
                $goods_name = count($goods) > 1 ? $goods[0]['name'] . '等' . count($goods) . '个商品' : $goods[0]['name'];
                $time = date('Y-m-d H:i:s', time());
                if (I('status') == '1') {
                    $result = '通过';
                    $remark = '';
                } else {
                    $result = '驳回';
                    $remark = I('check_opinion');
                }
                apply_return_goods_notice($orders['openid'], $order_no, $goods_name, $time, $orders['money'], $result, $remark);
                addlog($_SESSION['id'], '审核退款申请订单' . $order_no);
                $info->commit();
                $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
            }
        }
    }

    /**
     * 退款操作
     */
    public function refund_handle()
    {
        $info = M('apply_refund', '', 'NBYH');
        if (!empty($_POST)) {
            $info->startTrans();
            $order_no = I('order_no');
            $money = I('money');
            $order = M('orders', '', 'NBYH')->WHERE(['order_no' => $order_no])->FIND();
            if ($money > $order['money'] || $money <= 0) {
                $this->ajaxReturn(['code' => '1002', 'result' => '退款金额不得大于订单总额或者小于等于0']);
                return false;
            }
//            $res = $info->WHERE(['order_no' => $order_no])->setField('status', '3');
//            if ($res !== false) {

            $res = $this->refund($order['order_no'], $money);
            if ($res) {
                $data['refund_no'] = $res;
                $data['return_money'] = $money;
                $res = $info->WHERE(['order_no' => $order_no])->SAVE($data);
                if ($res !== false) {
                    addlog($_SESSION['id'], '对' . $order_no . '订单退款操作');
                    add_money_log($_SESSION['id'], $order['openid'], $money, '1');
                    return_money_notice($order['openid'], $order['order_no'], $money);
                    $info->commit();
                    $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
                } else {
                    $info->rollback();
                    $this->ajaxReturn(['code' => '1002', 'result' => '退款失败']);
                }
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '退款失败']);
            }
//            } else {
//                $info->rollback();
//                $this->ajaxReturn(['code' => '1002', 'result' => '退款失败']);
//            }
        }
    }

    /**
     * 退款接口
     */
    public function refund($transaction_id, $refund_fee)
    {
//        $transaction_id='NB14924207145133';
        $refund_order_no = date('YmdHis') . rand(1001, 9999);
        $refund_fee = intval($refund_fee);
        try {
            $data = array(
                'timestamp' => time() * 1000,
                'channel' => 'WX', //渠道类型
                'bill_no' => $transaction_id, //订单号
                'refund_no' => $refund_order_no, //退款单号
                'refund_fee' => $refund_fee, //退款金额,单位分
                //need_approval选填,是否为预退款,预退款need_approval为true,直接退款不加此参数或者为false
                'need_approval' => false
            );
            $result = \Service\beecloud\rest\api::refund($data);
            if ($result->result_code != 0) {
                print_r($result);
                exit();
            }
            //当channel为ALI_APP、ALI_WEB、ALI_QRCODE，并且不是预退款
            if (!isset($data["need_approval"]) && $data['channel'] == 'ALI') {
                header("Location:$result->url");
                exit();
            }
//            echo '(预)退款成功, 退款表记录唯一标识ID: ' . $result->id;
            return $refund_order_no;
        } catch (Exception $e) {
            return false;
//            echo $e->getMessage();
        }
    }

    /**
     * 订单详情
     */
    public function order_detail()
    {
        $info = M('orders', '', 'NBYH');
        $order_no = I('order_no');
        if (!empty($order_no)) {
            $order = $info->JOIN("o JOIN user u ON o.openid=u.openid")->JOIN("JOIN user_address ua ON o.address_id=ua.id")->WHERE(['o.order_no' => $order_no])->FIELD("o.*,u.headimg,u.nickname,ua.name,ua.mobile,ua.area_id,ua.detail")->FIND();
            $address = M('area', '', 'NBYH')->WHERE("id in({$order['area_id']})")->getField('name', true);
            $order['address'] = implode('-', $address) . '-' . $order['detail'];
            $order['goods'] = M('orders_ext', '', 'NBYH')->JOIN("oe JOIN goods g ON oe.goods_id=g.id")->JOIN("JOIN goods_size gs ON oe.gs_id=gs.id")->JOIN("JOIN size s ON gs.sid=s.id")->WHERE(['oe.order_no' => $order_no])->FIELD('g.title_img,g.name,s.name as sname,oe.num,gs.price')->SELECT();
            $refund = M('apply_refund', '', 'NBYH')->WHERE(['order_no' => $order_no])->FIND();
            $refund = !empty($refund) ? $refund : '';

            $this->assign(array(
                'detail' => $order,
                'refund' => $refund
            ));
            $this->display();
        }
    }

    /**
     * 自定义订单
     */
    public function custom_order()
    {
        $info = D('CustomorderView');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "co.order_no='$content' or g.name='$content' or s.name='$content'";
        }

        $where['co.status'] = array('eq', '1');
        $tol = $info->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->WHERE($where)->LIMIT($page->listfirst, $page->listRows)->ORDER('co.ctime desc')->SELECT();
//        var_dump($list);
        $goods=M('goods','','NBYH')->WHERE(['status'=>'1'])->ORDER("ctime desc")->SELECT();
        $goods_size=M('goods_size','','NBYH')->JOIN("gs JOIN size s ON gs.sid=s.id")->WHERE(['gs.goods_id'=>$goods['0']['id']])->FIELD("gs.id,s.name")->SELECT();
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol,
            'goods'=>$goods,
            'goods_size'=>$goods_size
        ));
        $this->display();
    }

    /**
     * 添加自定义菜单
     */
    public function add_custom_order(){
        $info=M('custom_orders','','NBYH');
        if(!empty($_POST)){
            $openid=M('user','','NBYH')->WHERE(['memberid'=>I('memberid')])->getField('openid');
            $data['aid']=$_SESSION['id'];
            $data['openid']=$openid;
            $data['order_no']='NB'.date('YmdHis',time()).rand(1000,9999);
            $data['goods_id']=I('goods_id');
            $data['gs_id']=I('gs_id');
            $data['money']=I('money');
            $data['ctime']=time();
            $res=$info->add($data);
            if($res){
                addlog($_SESSION['id'],'添加了订单'.$data['order_no']);
                $this->ajaxReturn(['code'=>'1001','result'=>'提交成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'提交失败']);
            }
        }
    }

    /**
     * 删除自定义订单
     */
    public function del_custom_order(){
        $info=M('custom_orders','','NBYH');
        if(!empty($_POST)){
            $order_no=I('order_no');
            $res=$info->WHERE(['order_no'=>$order_no])->setField('status','2');
            if($res!==false){
                $this->ajaxReturn(['code'=>'1001','result'=>'删除成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'删除失败']);
            }
        }
    }


}