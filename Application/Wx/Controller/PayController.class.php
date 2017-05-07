<?php
namespace Wx\Controller;
use Think\Controller;
class PayController extends Controller {
    /**
     * 支付回调地址
     */
    public function webhook(){
        $info = M('orders','','NBYH');
        $appId = C('APPID');
        $appSecret = C('APPSECRET');
        $jsonStr = file_get_contents("php://input");
        $msg = json_decode($jsonStr);
//        $param = $msg->optional;
//        var_dump($msg);die();
        //返回信息详情
        $detail = $msg->message_detail;
        $sign = md5($appId . $appSecret . $msg->timestamp);
        //验证签名是否一致
        if ($sign == $msg->sign) {
//            支付信息
            if ($msg->transaction_type == "PAY") {
//                判断支付是否成功，返回true表示成功
                $status = $msg->trade_success;
                if ($status) {
                    $sn = $detail->out_trade_no;
//                    $refund_no=$detail->transaction_id;
//                    if ($param->param == 'recharge') {
                        $order = $info->WHERE(['order_no'=>$sn])->FIND();
                        //如果该订单已经判断支付成功，则不再重复判断
                        if ($order['status'] == '0') {
                            //更新支付状态
                            $info->WHERE(['order_no'=>$sn])->setField('status', 1);
                            //这里做分销商业务处理
                            $this->business_handle($sn);
//                            $info->WHERE(['order_no'=>$sn])->setField('transaction_id', $refund_no);
//                            $this->redirect('Index/index');
                            echo 'success';
                        } else {
                            exit();
                        }
//                    }
                }
            }
            if($msg->transaction_type=='REFUND'){
                //                判断支付是否成功，返回true表示成功
                $status = $msg->trade_success;
                if ($status) {
                    $order_no = $detail->out_trade_no;
                    $refund_order = M('apply_refund','','NBYH')->WHERE(['order_no'=>$order_no])->FIND();
                    //如果该订单已经判断支付成功，则不再重复判断
                    if ($refund_order['status'] != '3') {
                        //更新退款状态
                        M('apply_refund','','NBYH')->WHERE(['order_no'=>$order_no])->setField('status', 3);
                        M('apply_refund','','NBYH')->WHERE(['order_no'=>$order_no])->setField('refund_time', time());
//                            $info->WHERE(['order_no'=>$sn])->setField('transaction_id', $refund_no);
//                            $this->redirect('Index/index');
                        echo 'success';
                    } else {
                        exit();
                    }
                }
            }

        }
    }

    /**
     * 业务处理
     * 如：A-B-C-D-E-F-G  都是公星  G买了8000元产品
            F收益800（8000*10%）+8000*10%
            E收益80（8000*10%*10%）+8000*15%
            D收益88（800*10%+80*10%）+8000*20%
            C收益0
            B收益0
            A收益0
     * 2、公星奖：发展6个直推下级代理，成为公星，并进行奖励（每增加6人奖励3万，人工操作）
     * 公星奖和分层奖可以同时享有
     * 前面是公星奖，后面是层级奖
     */
    public function business_handle($order_no='NB14925898188720'){
        $info=M('orders','','NBYH');
        $team=M('user_team','','NBYH');
        $user=M('user','','NBYH');
        $order=$info->WHERE(['order_no'=>$order_no])->FIND();
        $goods=M('orders_ext','','NBYH')->JOIN("oe JOIN goods g ON oe.goods_id=g.id")->WHERE(['oe.order_no'=>$order_no])->FIELD("oe.*,g.name")->SELECT();
        $order_ext=M('orders_ext','','NBYH')->WHERE(['order_no'=>$order_no])->SELECT();
        //更新库存和销量
        foreach($order_ext as $k=>$v){
            M('goods_size','','NBYH')->WHERE(['id'=>$v['gs_id']])->setDec('num',$v['num']);
            M('goods','','NBYH')->WHERE(['id'=>$v['goods_id']])->setDec('stock',$v['num']);
            M('goods','','NBYH')->WHERE(['id'=>$v['goods_id']])->setInc('sale',$v['num']);
            $goods_stock=M('goods_size','','NBYH')->WHERE(['id'=>$v['gs_id']])->getField('num');
            //部分商品规格缺货
            if($goods_stock<=0){
                M('goods','','NBYH')->WHERE(['id'=>$v['goods_id']])->setField('is_add_depot','2');
            }
        }
        $goods_name=count($goods)>1?$goods[0]['name'].'等'.count($goods).'个商品':$goods[0]['name'];
        //订单支付成功通知
        pay_notice($order['openid'],$goods_name,$order_no,$order['money']);
        $nickname=$user->WHERE(['openid'=>$order['openid']])->getField('nickname');
        $all_team=$team->SELECT();
        //获取该用户的所有直推上级
        $all_pre=get_all_pre($all_team,$order['openid']);
        $all_pre_openid=array_column($all_pre,'pre_openid');
        $money=$order['money'];
        $n=0;
//        var_dump($all_pre_openid);
        foreach($all_pre_openid as $k=>$v){
            $msg=$user->WHERE(['openid'=>$v])->FIND();

            if($msg['is_distribution']=='1' && $k=='0'){
                $income=$money*0.1;
                $user->WHERE(['openid'=>$v])->setInc('score',$income);
                $remark=$nickname.'购买了价值'.$order['money'].'元的商品';
                add_score_log($v,$income,'1',$remark);
            }elseif($msg['is_distribution']=='1' && $k=='1'){
                $income=$money*0.15;
                $user->WHERE(['openid'=>$v])->setInc('score',$income);
                $remark=$nickname.'购买了价值'.$order['money'].'元的商品';
                add_score_log($v,$income,'1',$remark);
            }elseif($msg['is_distribution']=='1' && $k=='2'){
                $income=$money*0.2;
                $user->WHERE(['openid'=>$v])->setInc('score',$income);
                $remark=$nickname.'购买了价值'.$order['money'].'元的商品';
                add_score_log($v,$income,'1',$remark);
            }

            if($msg['is_star']=='1' && $n=='0'){
                $star_income=$money*0.1;
                $user->WHERE(['openid'=>$v])->setInc('score',$star_income);
                $remark=$nickname.'购买了价值'.$order['money'].'元的商品(公星奖)';
                add_score_log($v,$star_income,'1',$remark);
                $n++;
            }elseif($msg['is_star']=='1' && $n=='1'){
                $star_income=$money*0.1*0.1;
                $user->WHERE(['openid'=>$v])->setInc('score',$star_income);
                $remark=$nickname.'购买了价值'.$order['money'].'元的商品(公星奖)';
                add_score_log($v,$star_income,'1',$remark);
                $n++;
            }elseif($msg['is_star']=='1' && $n=='2'){
                $star_income=$money*0.1*0.1+$money*0.1*0.1*0.1;
                $user->WHERE(['openid'=>$v])->setInc('score',$star_income);
                $remark=$nickname.'购买了价值'.$order['money'].'元的商品(公星奖)';
                add_score_log($v,$star_income,'1',$remark);
                $n++;
            }
        }

    }

}
?>