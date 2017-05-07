<?php
namespace Admin\Controller;

use Think\Controller;

class TeamController extends CommenController
{
    /**
     * 用户认证列表
     */
    public function user_team()
    {
        $info = M('user', '', 'NBYH');
        $team = M('user_team', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.memberid='$content' or u.nickname='$content' or uu.nickname='$content'";
        }

        $teams = $team->FIELD('openid,pre_openid')->SELECT();
        $tol = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $user = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE($where)->FIELD("u.*,uu.nickname as r_nickname")->LIMIT($page->listfirst, $page->listRows)->ORDER('u.id desc')->SELECT();

        foreach ($user as $k => $v) {
            $user_team = get_new_array($teams, $v['openid']);
            $arr = array(
                'openid' => $v['openid'],
                'pre_openid' => ''
            );
            get_new_array('', '', $user_team);
            array_unshift($user_team, $arr);
            $user[$k]['count'] = count($user_team);
            $wheres['openid'] = array('in', implode(',', array_column($user_team, 'openid')));
            $user[$k]['allscore'] = $info->WHERE($wheres)->SUM('score');
            $user[$k]['direct_num'] = $team->WHERE(['pre_openid' => $v['openid']])->COUNT();
            if (empty($v['r_nickname'])) {
                $user[$k]['r_nickname'] = '无';
            }

        }

        //1工商2华夏3建设4邮政5中信6交通7招商8农业9中国10平安11民生
        $bank = array(
            '1' => '工商银行',
            '2' => '华夏银行',
            '3' => '建设银行',
            '4' => '邮政储蓄',
            '5' => '中信银行',
            '6' => '交通银行',
            '7' => '招商银行',
            '8' => '农业银行',
            '9' => '中国银行',
            '10' => '平安银行',
            '11' => '民生银行'
        );

        $this->assign(array(
            'user' => $user,
            'fpage' => $fpage,
            'tol' => $tol,
            'bank' => $bank
        ));
        $this->display();
    }

    /**
     * 团队详情
     */
    public function team_detail()
    {
        $info = M('user', '', 'NBYH');
        $team = M('user_team', '', 'NBYH'); //我的团队
        $login = M('user_login_log', '', 'NBYH');
        $openid = I('openid');
        if (!empty($openid)) {
            $user = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE(['u.openid' => $openid])->FIELD("u.*,uu.nickname as r_nickname")->FIND();
            if (empty($user['r_nickname'])) {
                $user['r_nickname'] = '无';
            }
            $check = M('user_team', '', 'NBYH')->WHERE(['pre_openid' => $openid])->getField('id');
            if (!empty($check)) {
                $user['is_start'] = '是';
            } else {
                $user['is_start'] = '不是';
            }
            //团队信息
            $top = array(
                'openid' => $openid,
                'pre_openid' => '',
                'ctime' => $user['ctime'],
                'memberid' => $user['memberid'],
                'nickname' => $user['nickname']
            );
            $user['all_user'] = $team->JOIN("ut JOIN user u ON ut.openid=u.openid")->FIELD("ut.openid,ut.pre_openid,ut.ctime,u.memberid,u.nickname")->ORDER("ut.id desc")->SELECT();
            $user['all_user'] = get_new_array($user['all_user'], $openid);
            get_new_array('', '', $user['all_user']);
            array_unshift($user['all_user'], $top);
            $user['all_user_num'] = COUNT($user['all_user']);//团队总人数
            $all_user_openid = implode(',', array_column($user['all_user'], 'openid'));
            $where_auo['openid'] = array('in', $all_user_openid);
            $all_score = $info->WHERE($where_auo)->SUM('score');//团队总积分
            $user['all_score'] = !empty($all_score) ? $all_score : 0;
            $user['direct_num'] = $team->WHERE(['pre_openid' => $openid])->COUNT();
            $user['login_time'] = $login->WHERE(['openid' => $openid])->ORDER('id desc')->LIMIT(1)->getField('ctime');
            foreach ($user['all_user'] as $k => $v) {
                //递归获取所有下级
                $arr = get_new_array($user['all_user'], $v['openid']);
                array_unshift($arr, $v);
                $all_user_openids = implode(',', array_column($arr, 'openid'));
                $user['all_user'][$k]['count'] = COUNT($arr);
                $where_auos['openid'] = array('in', $all_user_openids);
                $user['all_user'][$k]['score'] = $info->WHERE($where_auos)->SUM('score');
//            var_dump($score);
                $user['all_user'][$k]['score'] = !empty($user['all_user'][$k]['score']) ? $user['all_user'][$k]['score'] : '0';
                //初始化静态变量
                get_new_array('', '', $arr);
            }

            $this->assign(array(
                'user' => $user,
            ));
            $this->display();
        }
    }

    /**
     * 读取打款记录
     */
    public function play_money_record()
    {
        $info = M('money_record', '', 'NBYH');
        if (!empty($_POST)) {
            $openid = I('openid');
            $list = $info->JOIN("mr LEFT JOIN admin a ON mr.aid=a.id")->WHERE(['mr.openid' => $openid, 'mr.type' => '2'])->FIELD("mr.*,a.name")->ORDER("ctime desc")->SELECT();
            foreach($list as $k=>$v){
                $list[$k]['ctime']=date('Y-m-d H:i:s',$v['ctime']);
            }
            if ($list !== false) {
                $this->ajaxReturn(['code' => '1001', 'result' => $list]);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '获取失败']);
            }
        }
    }

    /**
     * 企业打款操作
     * ◆ 给同一个实名用户付款，单笔单日限额2W/2W
     * ◆ 不支持给非实名用户打款
     * ◆ 一个商户同一日付款总额限额100W
     */
    public function play_money()
    {
        $info = M('money_record', '', 'NBYH');
        $withdraw = D('withdraw');
        if (!empty($_POST)) {
            $info->startTrans();
            $openid = I('openid');
            $money = I('money');
            if ($money <= 0) {
                $this->ajaxReturn(['code' => '1002', 'result' => '金额不得小于等于0']);
                return false;
            }
//            if($money>20000){
//                $this->ajaxReturn(['code'=>'1002','result'=>'单笔最高金额不得大于2万']);
//                return false;
//            }
            $data['aid'] = $_SESSION['id'];
            $data['openid'] = $openid;
            $data['money'] = $money;
            $data['type'] = '2';
            $data['ctime'] = time();
            $data['bank'] = I('bank');
            $data['account'] = I('account');
            $data['truename'] = I('truename');
            $res = $info->add($data);
            if ($res) {
                addlog($_SESSION['id'],'成功打款给用户'.I('truename'));
                $info->commit();
                $this->ajaxReturn(['code' => '1001', 'result' => '打款成功']);
//                $datas['orderid']='PM'.time().rand(1000,9999);
//                $datas['bankaccount']=I('bankaccount');
//                $datas['truename']=I('truename');
//                $datas['ctime']=time();
//                $datas['money']=I('money');
//                $datas['uid']=$openid;
//                $datas['quit']=rand(1,9);
//                $datas['wid']=$res;
//                $datas['bankclass']=I('bankclass');
//                $res=$withdraw->create($datas,'1');
//                if(!$res){
//                    $info->rollback();
//                    $this->ajaxReturn(['code'=>'1002','result'=>$withdraw->getError()]);
//                    return false;
//                }
//                $res=$withdraw->add();
//                if($res){
//                    $info->commit();
//                    $this->ajaxReturn(['code'=>'1001','result'=>'打款成功']);
//                }else{
//                    $info->rollback();
//                    $this->ajaxReturn(['code'=>'1002','result'=>'打款失败']);
//                }
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '打款失败']);
            }
        }
    }

    //    /**
//     * 企业打款接口
//     */
//    public function play_moneys(){
//        try {
//            $data = array(
//                'timestamp' => time() * 1000,
//                'channel' => 'WX_TRANSFER',
//                'title' => '微信企业打款',   //订单标题
//                'transfer_no' => "bcdemo" . time(),    //打款单号
//                'total_fee' => 1, //订单金额(int 类型) ,单位分
//                'channel_user_id' => 'o8_jh0mq9uZ63rD5J5RFkjcFYXaA',   //微信openid
//                'desc'=>'退款'
//            );
//            $result = \Service\beecloud\rest\api::transfer($data);
//            //不使用namespace的用户
//            //$result = BCRESTApi::transfer($data);
//            if ($result->result_code != 0) {
//                print_r($result);
//                exit();
//            }
//            echo ' 打款成功';
//        } catch (Exception $e) {
//            echo $e->getMessage();
//        }
//    }

}