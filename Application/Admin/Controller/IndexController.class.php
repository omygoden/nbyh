<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommenController {
    /**
     *后台主页
     */
    public function index(){
//        var_dump(I('get.res'));return;
//        var_dump($_SESSION);
        $info=M('role','','NBYH');
        if($_SESSION['rid']=='99'){
            $top_power=M('power','','NBYH')->WHERE(['level'=>'1','status'=>'1'])->ORDER("weight asc")->SELECT();
            $next_power=M('power','','NBYH')->WHERE(['level'=>'2','status'=>'1'])->ORDER("weight asc")->SELECT();
        }else{
            $role=$info->WHERE(['id'=>$_SESSION['rid']])->FIND();
            $top_power=M('power','','NBYH')->WHERE("id in({$role['top_id']}) and level=1 and status=1")->ORDER("weight asc")->SELECT();
            $next_power=M('power','','NBYH')->WHERE("id in({$role['next_id']}) and level=2 and status=1")->ORDER("weight asc")->SELECT();
        }

        $this->assign(array(
            'top_power'=>$top_power,
            'next_power'=>$next_power
        ));
        $this->display();
    }

    public function home(){
        $user=M('user','','NBYH');
        $distribution=M('apply_distribution','','NBYH');
        $cert=M('user_cert','','NBYH');
        $order=M('orders','','NBYH');

        $start_time=mktime('0','0','0',date('m',time()),date('d',time()),date('Y',time()));
        $end_time=mktime('23','59','59',date('m',time()),date('d',time()),date('Y',time()));
        $where['ctime']=array('between',array($start_time,$end_time));
        //今日用户新增人数和总人数
        $msg['today_user_num']=$user->WHERE($where)->COUNT();
        $msg['all_user_num']=$user->COUNT();

        //今日商品成交价和总成交价
        $where_o['_complex']=$where;
        $where_o['status']=array('neq','0');
        $msg['today_order_money']=$order->WHERE($where_o)->SUM('money');
        $msg['all_order_money']=$order->WHERE(array('status'=>array('neq','0')))->SUM('money');

        //今日分销商申请人数和总通过人数

        $msg['today_apply_distribution']=$distribution->WHERE($where)->COUNT();
        $msg['all_distribution']=$user->WHERE(array('is_distribution'=>'1'))->COUNT();

        //今日认证人数和总通过人数

        $msg['today_user_cert']=$cert->WHERE($where)->COUNT();
        $msg['all_user_cert']=$user->WHERE(array('is_cert'=>'1'))->COUNT();

        $this->assign(array(
            'msg'=>$msg
        ));
        $this->display();
    }

}