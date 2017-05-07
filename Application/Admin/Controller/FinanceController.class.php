<?php
namespace Admin\Controller;

use Think\Controller;

class FinanceController extends CommenController
{
    /**
     * 奖励记录
     */
    public function reward_record()
    {
        $info = M('score_record', '', 'NBYH');
        $user = M('user', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.memberid='$content' or u.nickname='$content'";
        }
//        if (!empty(I('get.status') || I('get.status') == '0')) {
//            $where['u.is_cert'] = array('eq', I('get.status'));
//        }
        $where['sr.type'] = array('eq', '1');
        $tol = $info->JOIN("sr JOIN user u ON sr.openid=u.openid")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $user = $info->JOIN("sr JOIN user u ON sr.openid=u.openid")->WHERE($where)->FIELD("sr.score,sr.remark,sr.ctime,u.nickname,u.memberid,u.score as uscore,u.headimg")->LIMIT($page->listfirst, $page->listRows)->ORDER('sr.id desc')->SELECT();

        $this->assign(array(
            'user' => $user,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 提现审核
     */
    public function exchange_check()
    {
        $info = M('apply_exchange', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
//            $where['u.account']=array('eq',I('content'));
            $where['_string'] = "ae.name='$content' or u.nickname='$content' or ae.mobile='$content'";
        }
        if (!empty(I('get.status') || I('get.status') == '0')) {
            $where['ae.status'] = array('eq', I('get.status'));
        }

        $tol = $info->JOIN("ae JOIN user u ON ae.openid=u.openid")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("ae LEFT JOIN user u ON ae.openid=u.openid")->WHERE($where)->FIELD("ae.*,u.nickname,u.headimg")->ORDER('ae.id desc')->LIMIT($page->listfirst, $page->listRows)->SELECT();
//        $bank = array(
//            '1' => '工商银行',
//            '2' => '华夏银行',
//            '3' => '建设银行',
//            '4' => '邮政储蓄',
//            '5' => '中信银行',
//            '6' => '交通银行',
//            '7' => '招商银行',
//            '8' => '农业银行',
//            '9' => '中国银行',
//            '10' => '平安银行',
//            '11' => '民生银行'
//        );
//        $json = json_encode(array_values($bank));

        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol,
//            'bank' => $bank,
//            'json' => $json
        ));
        $this->display();
    }

    /**
     * 提现审核操作
     */
    public function sub_exchange_check()
    {
        $info = M('apply_exchange', '', 'NBYH');
        if (!empty($_POST)) {
            $eid = I('eid');
            $check = $info->WHERE(['id' => $eid])->getField('status');
            if ($check == '1') {
                $this->ajaxReturn(['code' => '1002', 'result' => '已审核，请勿重复操作']);
                return false;
            }
            $data['check_opinion'] = I('opinion');
            $data['check_name'] = $_SESSION['name'];
            $data['status'] = I('status');
            $data['check_time'] = time();
            $res = $info->WHERE(['id' => $eid])->SAVE($data);
            if ($res !== false) {
                switch (I('status')) {
                    case 1:
                        $status = '通过,待打款';
                        break;
                    case 2:
                        $status = '驳回';
                        break;
                    default:
                        $status = '';
                        break;
                }
                $exchange = $info->WHERE(['id' => $eid])->FIND();
                $openid = $exchange['openid'];
                if (I('status') == '2') {
                    M('user', '', 'NBYH')->WHERE(['openid' => $openid])->setInc('score', $exchange['score']);
                    add_score_log($openid, $exchange['score'], '3', '兑换申请驳回,积分归还');
                }

                $nickname = $info->JOIN('ae JOIN user u ON ae.openid=u.openid')->WHERE(['ae.id' => $eid])->getField('nickname');
                $content = '审核' . $nickname . '提现申请。操作内容：' . $status;
                addlog($_SESSION['id'], $content);
                $title = '提现申请';
                $result = $status;
                $remark = I('opinion');
                check_notice($openid, $title, $nickname, $result, $remark);
                $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
            }
        }
    }

    /**
     * 兑现
     */
    public function cash()
    {
//        $withdraw = D('withdraw');
        $info=M('apply_exchange','','NBYH');
        if (!empty($_POST)) {
            $eid=I('eid');
            $apply = $info->WHERE(['id' => $eid])->FIND();
            if ($apply['status'] == '3') {
                $this->ajaxReturn(['code' => '1002', 'result' => '已打款，请勿重复操作']);
                return false;
            }
            $res=$info->WHERE(['id'=>$eid])->setField('status','3');
            if ($res!==false) {
                $info->WHERE(['id'=>$eid])->setField('play_time',time());
                addlog($_SESSION['id'],'成功打款给用户'.$apply['name']);
                $ctime=date('Y-m-d H:i:s',$apply['ctime']);
                $play_time=date('Y-m-d H:i:s',$apply['play_time']);
                play_money_notice($apply['openid'],'',$ctime,$apply['score'],$apply['score'],$apply['account'],$play_time,'');
                $this->ajaxReturn(['code' => '1001', 'result' => '打款成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '打款失败']);
            }
        }
    }

    /**
     * 商家收益
     */
    public function merchant_income(){
        $info = M('orders', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.memberid='$content' or u.nickname='$content' or o.order_no='$content'";
        }
//        if (!empty(I('get.status') || I('get.status') == '0')) {
//            $where['u.is_cert'] = array('eq', I('get.status'));
//        }
        $where['o.status']=array('gt','0');
        $tol = $info->JOIN("o LEFT JOIN score_record sr ON o.order_no=sr.order_no")->JOIN("LEFT JOIN user u ON o.openid=u.openid")->WHERE($where)->GROUP("o.order_no")->SELECT();
        $tol=count($tol);
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("o LEFT JOIN score_record sr ON o.order_no=sr.order_no")->JOIN("LEFT JOIN user u ON o.openid=u.openid")->WHERE($where)->GROUP("o.order_no")->FIELD("o.*,sum(sr.score) as sr_score,u.nickname,u.headimg,u.memberid")->LIMIT($page->listfirst, $page->listRows)->ORDER('o.id desc')->SELECT();
        $lists = $info->JOIN("o LEFT JOIN score_record sr ON o.order_no=sr.order_no")->JOIN("LEFT JOIN user u ON o.openid=u.openid")->WHERE($where)->GROUP("o.order_no")->FIELD("o.money,sum(sr.score) as sr_score")->SELECT();
        $income=array_sum(array_column($lists,'money'));
        $pay=array_sum(array_column($lists,'sr_score'));
        $money['income']=!empty($income-$pay)?($income-$pay):0;
        $money['pay']=!empty($pay)?$pay:0;
        $money['all']=!empty($income)?$income:0;

        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol,
            'money'=>$money
        ));
        $this->display();
    }

    /**
     * 商家支出
     */
    public function merchant_pay(){
        $info = M('money_record', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.memberid='$content' or u.nickname='$content'";
        }
        if(!empty(I('type'))){
            $where['type']=array('eq',I('type'));
        }
        $tol = $info->JOIN("mr JOIN user u ON mr.openid=u.openid")->JOIN("JOIN admin a ON mr.aid=a.id")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("mr JOIN user u ON mr.openid=u.openid")->JOIN("JOIN admin a ON mr.aid=a.id")->WHERE($where)->FIELD("mr.*,u.nickname,u.headimg,u.memberid,a.name")->LIMIT($page->listfirst, $page->listRows)->ORDER('mr.id desc')->SELECT();
        $pay=$info->JOIN("mr JOIN user u ON mr.openid=u.openid")->JOIN("JOIN admin a ON mr.aid=a.id")->WHERE($where)->SUM('money');
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol,
            'pay'=>$pay
        ));
        $this->display();
    }

}