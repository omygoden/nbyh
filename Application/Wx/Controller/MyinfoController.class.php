<?php
namespace Wx\Controller;

use Think\Controller;

class MyinfoController extends CommenController
{
    /**
     *个人中心
     */
    public function myinfo()
    {
        $this->set_openid();
        $info = M('user', '', 'NBYH');
        $orders = M('orders', '', 'NBYH');
        $openid = $_SESSION['openid'];
        $user = $info->WHERE(['openid' => $openid])->FIND();

        $recommend_nickname = $info->WHERE(['openid' => $user['recommend_openid']])->getField('nickname');
        $user['nopay'] = $orders->WHERE(['openid' => $openid, 'status' => '0'])->COUNT();
        $user['nodeliver'] = $orders->WHERE(['openid' => $openid, 'status' => '1'])->COUNT();
        $user['noaccept'] = $orders->WHERE(['openid' => $openid, 'status' => '2'])->COUNT();
        $user['service'] = $orders->WHERE(['openid' => $openid, 'status' => '4'])->COUNT();
        $user['service'] = $orders->WHERE("openid='$openid' and (status=4 or status=5)")->COUNT();
        $user['num'] = M('user_team', '', 'NBYH')->WHERE(['pre_openid' => $openid])->COUNT();
        $all = M('score_record', '', 'NBYH')->WHERE(['openid' => $openid, 'type' => '1'])->SUM('score');
        $user['all'] = !empty($all) ? $all : 0;
        $this->assign(array(
            'user' => $user,
            'recommend_nickname' => $recommend_nickname
        ));
        $this->display();
    }

    /**
     * 积分明细
     */
    public function score_record()
    {
//        $this->set_openid();
        $info = M('score_record', '', 'NBYH');
        $openid = $_SESSION['openid'];

        $record = $info->WHERE("openid='$openid' and (type!='4' and type!='5')")->SELECT();
        $all_income = $info->WHERE("openid='$openid' and (type='1' or type='3')")->SUM('score');
        $all_pay = $info->WHERE("openid='$openid' and type='2'")->SUM('score');

        $money['all_income'] = !empty($all_income) ? $all_income : 0;
        $money['all_pay'] = !empty($all_pay) ? $all_pay : 0;
        $this->assign(array(
            'money' => $money,
            'record' => $record
        ));
        $this->display();
    }

    /**
     * 我的团队
     */
    public function my_team()
    {
        $this->set_openid();
        $is_distribution=M('user','','NBYH')->WHERE(['openid'=>$_SESSION['openid']])->getField('is_distribution');
        if($is_distribution!='1' || empty($is_distribution)){
            $this->error('目前只支持分销商访问。',U('Myinfo/myinfo'));
            exit();
        }
        $info = M('user', '', 'NBYH');
        $team = M('user_team', '', 'NBYH');
        $openid = $_SESSION['openid'];

        $start = mktime('0', '0', '0', date('m', time()), date('d', time()), date('Y', time()));
        $end = mktime('23', '59', '59', date('m', time()), date('d', time()), date('Y', time()));
        $user = $info->WHERE(['openid' => $openid])->FIND();
        $user['direct_user'] = $team->WHERE(['pre_openid' => $openid])->COUNT();
        //判断该用户是否是团队起始者
        $check = $team->WHERE(['pre_openid' => $openid])->FIND();
        if (!empty($check)) {
            $user['is_start'] = '是';
//            $top_openid=$openid;
        } else {
            $user['is_start'] = '否';
//            $top_openid = $team->WHERE(['openid' => $openid])->getField('top_openid');
        }
//        $top_user=M('user','','NBYH')->WHERE(['openid'=>$top_openid])->FIND();
        $top=array(
            'openid'=>$openid,
            'pre_openid'=>'',
            'ctime'=>$user['ctime'],
            'memberid'=>$user['memberid'],
            'nickname'=>$user['nickname']
        );
        $user['all_user'] = $team->JOIN("ut JOIN user u ON ut.openid=u.openid")->FIELD("ut.openid,ut.pre_openid,ut.ctime,u.memberid,u.nickname")->ORDER("ut.id desc")->SELECT();
        $user['all_user']=get_new_array($user['all_user'],$openid);
        get_new_array('','',$user['all_user']);
        array_unshift($user['all_user'],$top);
        $user['all_user_num'] = COUNT($user['all_user']);

        $where['ut.ctime'] = array('between', array($start, $end));
        $user['today_user'] = $team->JOIN("ut JOIN user u ON ut.openid=u.openid")->WHERE($where)->FIELD("ut.openid,ut.pre_openid,ut.ctime,u.memberid,u.nickname")->ORDER("ut.id desc")->SELECT();
        $user['today_user']=get_new_array($user['today_user'],$openid);
        get_new_array('','',$user['today_user']);
        $user['today_user_num'] = COUNT($user['today_user']);

        $all_user_openid=implode(',',array_column($user['all_user'],'openid'));
        $where_auo['openid']=array('in',$all_user_openid);
        $all_score = $info->WHERE($where_auo)->SUM('score');
        $user['all_score'] = !empty($all_score) ? $all_score : 0;
        //历史
        foreach($user['all_user'] as $k=>$v){
            //递归获取所有下级
            $arr=get_new_array($user['all_user'],$v['openid']);
            array_unshift($arr,$v);
            $all_user_openids=implode(',',array_column($arr,'openid'));
//            var_dump($arr);
//            var_dump($all_user_openids);
            $user['all_user'][$k]['count']=COUNT($arr);
            $where_auos['openid']=array('in',$all_user_openids);
            $user['all_user'][$k]['score']=$info->WHERE($where_auos)->SUM('score');
//            var_dump($score);
            $user['all_user'][$k]['score']=!empty($user['all_user'][$k]['score'])?$user['all_user'][$k]['score']:'0';
            //初始化静态变量
            get_new_array('','',$arr);
        }
        //今日
        foreach($user['today_user'] as $key=>$value){
            $arr=get_new_array($user['today_user'],$value['openid']);
            array_unshift($arr,$value);
            $today_user_openids=implode(',',array_column($arr,'openid'));
//            var_dump($arr);
            $user['today_user'][$key]['count']=COUNT($arr);
            $where_tuo['openid']=array('in',$today_user_openids);
            $user['today_user'][$key]['score']=$info->WHERE($where_tuo)->SUM('score');
            $user['today_user'][$key]['score']=!empty($user['today_user'][$key]['score'])?$user['today_user'][$key]['score']:'0';
            get_new_array('','',$arr);
        }
//        var_dump($user);
        $this->assign(array(
            'user' => $user,
        ));
        $this->display();

    }

    /**
     * 申请经销商
     */
    public function apply_distribution()
    {
        $this->set_openid();
        $openid = $_SESSION['openid'];
        $msg = M('apply_distribution', '', 'NBYH')->WHERE(['openid' => $openid])->FIND();
        $this->assign(array(
            'msg' => $msg
        ));
        $this->display();

    }

    /**
     * 提交分销商申请
     */
    public function sub_apply_distribution()
    {
        $info = M('apply_distribution', '', 'NBYH');
        if (!empty($_POST)) {
            if (!empty(I('name') && !empty(I('mobile')))) {
                $info->startTrans();
                $check = $info->WHERE(['openid' => $_SESSION['openid']])->FIND();
                $data['name'] = I('name');
                $data['mobile'] = I('mobile');
                $data['remark'] = I('remark');
                $data['ctime'] = time();
                $data['status'] = '0';
                if (empty($check)) {
                    $data['openid'] = $_SESSION['openid'];
                    $res1 = $info->add($data);
                    $res2 = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->setField('is_distribution', '3');
                    if ($res1 && $res2 !== false) {
                        $info->commit();
                        $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
                    } else {
                        $info->rollback();
                        $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
                    }
                } else {
                    $res1 = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->setField('is_distribution', '3');
                    $res2 = $info->WHERE(['openid' => $_SESSION['openid']])->SAVE($data);
                    if ($res1 !== false && $res2 !== false) {
                        $info->commit();
                        $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
                    } else {
                        $info->rollback();
                        $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                    }
                }
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '姓名或者手机号不能为空']);
            }
        }
    }

    /**
     * 申请兑换
     */
    public function apply_exchange()
    {
        $this->set_openid();
        $info = M('user', '', 'NBYH');
        $openid = $_SESSION['openid'];

        $score = $info->WHERE(['openid' => $openid])->getField('score');
        $record = M('apply_exchange', '', 'NBYH')->WHERE(['openid' => $openid])->ORDER("ctime desc")->SELECT();
        $success = M('apply_exchange', '', 'NBYH')->WHERE(['openid' => $openid, 'status' => '1'])->SUM('score');
        $success = !empty($success) ? $success : '0';
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
        $this->assign(array(
            'score' => $score,
            'record' => $record,
            'success' => $success,
//            'bank'=>$bank
        ));
        $this->display();
    }

    /**
     * 提交兑换申请
     */
    public function sub_apply_exchange()
    {
        $info = D('Exchange');
        if (!empty($_POST)) {
            $info->startTrans();
            $data['openid'] = $_SESSION['openid'];
            $data['order_no'] = 'AE' . time() . rand(100, 999);
            $data['name'] = I('name');
            $data['mobile'] = I('mobile');
            $data['bank'] = I('bank');
            $data['account'] = I('account');
            $data['score'] = I('score');
            $data['ctime'] = time();
//            $res=$info->check_score('1000');
//            $this->ajaxReturn(['code' => '1002', 'result' => $res]);
            $check = $info->create($data, '1');
            if ($check) {
                $res = $info->add();
                $res2 = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->setDec('score', I('score'));
                if ($res && $res2 !== false) {
                    add_score_log($_SESSION['openid'], '-' . I('score'), '2', '积分兑换');
                    $info->commit();
                    $this->ajaxReturn(['code' => '1001', 'result' => '提现成功']);
                } else {
                    $info->rollback();
                    $this->ajaxReturn(['code' => '1002', 'result' => '提现失败']);
                }
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => $info->getError()]);
            }

        }
    }

    /**
     * 我的认证
     */
    public function my_cert()
    {
        $this->set_openid();
        $openid = $_SESSION['openid'];
        $msg = M('user_cert', '', 'NBYH')->WHERE(['openid' => $openid])->FIND();
        $this->assign(array(
            'msg' => $msg
        ));
        $this->display();

    }

    /**
     * 提交认证申请
     */
    public function sub_cert()
    {
        $info = M('user_cert', '', 'NBYH');
        if (!empty($_POST)) {
            if (!empty(I('name') && !empty(I('mobile'))) && !empty(I('img'))) {
                $info->startTrans();
                $check = $info->WHERE(['openid' => $_SESSION['openid']])->FIND();
                $data['name'] = I('name');
                $data['mobile'] = I('mobile');
                $data['img'] = I('img');
                $data['status']='0';
                $data['ctime'] = time();
                if (empty($check)) {
                    $data['openid'] = $_SESSION['openid'];
                    $res1 = $info->add($data);
                    $res2 = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->setField('is_cert', '3');
                    if ($res1 && $res2 !== false) {
                        $info->commit();
                        $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
                    } else {
                        $info->rollback();
                        $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
                    }
                } else {
                    $res1 = M('user', '', 'NBYH')->WHERE(['openid' => $_SESSION['openid']])->setField('is_cert', '3');
                    $res2 = $info->WHERE(['openid' => $_SESSION['openid']])->SAVE($data);
                    if ($res1 !== false && $res2 !== false) {
                        $info->commit();
                        $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
                    } else {
                        $info->rollback();
                        $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                    }
                }

            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '信息不全，请填写完整']);
            }
        }
    }

    /**
     * 收货地址管理
     */
    public function address_list()
    {
//        $this->set_openid();
        $info = M('user_address', '', 'NBYH');
        $openid = $_SESSION['openid'];

        $address = $info->WHERE(['openid' => $openid, 'status' => '1'])->ORDER('type desc,ctime desc')->SELECT();
        foreach ($address as $k => $v) {
            $area = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
            $address[$k]['area'] = implode('-', $area);
        }
        $this->assign(array(
            'address' => $address
        ));
        $this->display();
    }

    /**
     * 添加收货地址
     */
    public function add_address()
    {
//        $this->set_openid();
        $openid = $_SESSION['openid'];
        if(!empty(I('get.ourl'))){
            $ourl=base64_decode(I('get.ourl'));
            $this->assign('ourl',$ourl);
        }
        $info = M('area', '', 'NBYH');
        $aid = I('get.aid');
        $area = '';
        if (!empty($aid)) {
            $address = M('user_address', '', 'NBYH')->WHERE(['id' => $aid])->FIND();
            $area = explode(',', $address['area_id']);
            $address['next_area'] = $info->WHERE(['pid' => $area['0']])->SELECT();
            $address['last_area'] = $info->WHERE(['pid' => $area['1']])->SELECT();
        }
        $address['top_area'] = $info->WHERE(['pid' => '1'])->SELECT();
        $this->assign(array(
            'address' => $address,
            'area' => $area
        ));
        $this->display();
    }

    /**
     * 添加地址操作
     */
    public function sub_add_address()
    {
        $info = D('Address');
//        $info=M('user_address','','NBYH');
        if (!empty($_POST)) {
            $aid = I('aid');
            $data['openid'] = $_SESSION['openid'];
            $data['name'] = I('name');
            $data['mobile'] = I('mobile');
            $data['area_id'] = trim(I('area'), ',');
            $data['detail'] = I('detail');
//            if(!empty(I('to'))){
//                $data['type']='2';
//            }else{
//                $data['type']='1';
//            }
            if (empty($aid)) {
                $data['ctime'] = time();
                $check = $info->create($data, '1');
                if ($check) {
                    $res = $info->add();
                    if ($res) {
                        if(!empty(I('ourl'))){
                            $info->WHERE(['openid'=>$_SESSION['openid']])->setField('type','1');
                            $info->WHERE(['id'=>$res])->setField('type','2');
                        }
                        $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
                    } else {
                        $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
                    }
                } else {
                    $this->ajaxReturn(['code' => '1002', 'result' => $info->getError()]);
                }
            } else {
                $check = $info->create($data, '2');
                if ($check) {
                    $res = $info->WHERE(['id' => $aid])->SAVE($data);
                    if ($res) {
                        if(!empty(I('ourl'))){
                            $info->WHERE(['openid'=>$_SESSION['openid']])->setField('type','1');
                            $info->WHERE(['id'=>$aid])->setField('type','2');
                        }
                        $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
                    } else {
                        $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                    }
                } else {
                    $this->ajaxReturn(['code' => '1002', 'result' => $info->getError()]);
                }
            }
        }
    }

    /**
     * 设置为默认地址
     */
    public function set_default_address()
    {
        $info = M('user_address', '', 'NBYH');
        if (!empty($_POST)) {
            $info->startTrans();
            $aid = I('aid');
            $openid = $_SESSION['openid'];
            $res1 = $info->WHERE(['openid' => $openid, 'type' => '2'])->setField('type', '1');
            $res2 = $info->WHERE(['openid' => $openid, 'id' => $aid])->setField('type', '2');
            if ($res1 !== false && $res2 !== false) {
                $info->commit();
                $this->ajaxReturn(['code' => '1001', 'result' => '设置成功']);
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '设置失败']);
            }
        } else {
            $this->ajaxReturn(['code' => '1002', 'result' => '设置失败']);
        }
    }

    /**
     * 删除地址
     */
    public function del_address()
    {
        $info = M('user_address', '', 'NBYH');
        if (!empty($_POST)) {
            $aid = I('aid');
            $openid = $_SESSION['openid'];
            $res = $info->WHERE(['openid' => $openid, 'id' => $aid])->setField('status', '2');
            if ($res !== false) {
                $this->ajaxReturn(['code' => '1001', 'result' => '删除成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '删除失败']);
            }
        } else {
            $this->ajaxReturn(['code' => '1002', 'result' => '删除失败']);
        }
    }

    /**
     *我的二维码
     */
    public function my_qrcode()
    {
        $this->set_openid();
        $openid = $_SESSION['openid'];
//        $qrcode=M('user','','NBYH')->WHERE(['openid'=>$openid])->getField('my_qrcode');
        $poster = C('MYURL') . 'Public/poster/' . $openid . '.jpg';
        $this->assign(array(
            'poster' => $poster
        ));
        $this->display();
    }

    /**
     * 购买记录
     */
    public function shopping_record()
    {
        $this->set_openid();
        $openid = $_SESSION['openid'];

        $this->display();
    }

    /**
     * 我的收益
     */
    public function my_income()
    {
        $this->set_openid();
        $info = M('score_record', '', 'NBYH');
        $openid = $_SESSION['openid'];

        $record = $info->WHERE(['openid' => $openid, 'type' => '1'])->SELECT();
        $all = $info->WHERE(['openid' => $openid, 'type' => '1'])->SUM('score');
        $all = !empty($all) ? $all : 0;
        $this->assign(array(
            'all' => $all,
            'record' => $record
        ));
        $this->display();
    }


}