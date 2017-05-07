<?php
namespace Admin\Controller;

use Think\Controller;

class UserController extends CommenController
{
    /**
     * 管理员列表
     */
    public function admin_list()
    {
        $info = M('admin', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "a.phone='$content' or a.name='$content'";
        }

        $where['a.rid'] = array('neq', '99');
        $tol = $info->JOIN("a LEFT JOIN role r ON a.rid=r.id")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->JOIN("a LEFT JOIN role r ON a.rid=r.id")->WHERE($where)->FIELD('a.*,r.rolename')->ORDER('ctime desc')->LIMIT($page->listfirst, $page->listRows)->SELECT();
        foreach ($list as $k => $v) {
            $list[$k]['ctime'] = date('Y-m-d', $v['ctime']);
        }
        $roles = M('role', '', 'NBYH')->WHERE("status=1")->SELECT();
//        var_dump($roles);
        $this->assign(array(
            'list' => $list,
            'roles' => $roles,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 添加管理员账号
     */
    public function add_admin()
    {
        $info = M('admin', '', 'NBYH');
        if (!empty($_POST)) {
            $data['name'] = I('admin_name');
            $data['phone'] = I('mobile');
            $data['pwd'] = empty(I('pwd')) ? md5('123456' . 'nbyh') : md5(I('pwd') . 'nbyh');
            $data['rid'] = I('rid');
            $data['ctime'] = time();
            $check = $info->WHERE(['phone' => I('mobile')])->FIND();

            if (!empty($check)) {
                $this->ajaxReturn(['code' => '1002', 'result' => '该账号已存在']);
            } else {
                $res = $info->add($data);
                if ($res) {
                    $content = '添加了管理员' . I('admin_name');
                    addlog($_SESSION['id'], $content);
                    $this->ajaxReturn(['code' => '1001', 'result' => '添加成功']);
                } else {
                    $this->ajaxReturn(['code' => '1002', 'result' => '添加失败']);
                }
            }
        }
    }

    /**
     * 获取角色列表
     */
    public function get_role()
    {
//        $check=$this->check_auth();
//        if(!$check){
//            $this->ajaxReturn(['code' => '1002', 'result' => $check]);
//        }
        $info = M('role', '', 'NBYH');
        if (!empty($_POST)) {
            $list = $info->WHERE(['status' => '1'])->FIELD('id,rolename')->SELECT();
            $this->ajaxReturn(['code' => '1001', 'result' => $list]);
        } else {
            $this->ajaxReturn(['code' => '1002', 'result' => '获取失败']);
        }
    }

    /**
     * 修改管理员角色
     */
    public function modify_role()
    {
        $info = M('admin', '', 'NBYH');
        if (!empty($_POST)) {
            $aid = I('aid');
            $rid = I('rid');
            $admin = $info->WHERE(['id' => $aid])->getField('name');
            $res = $info->WHERE(['id' => $aid])->setField('rid', $rid);
            if ($res !== false) {
                $content = '修改了' . $admin . '的角色';
                addlog($_SESSION['id'], $content);
                $data['code'] = '1001';
                $data['result'] = '修改成功';
            } else {
                $data['code'] = '1002';
                $data['result'] = '修改失败';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 重置管理员登录密码
     */
    public function reset_pwd()
    {
        $info = M('admin', '', 'NBYH');
        if (!empty($_POST)) {
            $aid = I('aid');
            $pwd = md5('123456' . 'nbyh');
            $admin = $info->WHERE(['id' => $aid])->getField('name');
            $res = $info->WHERE(['id' => $aid])->setField('pwd', $pwd);
            if ($res !== false) {
                $content = '重置了管理员' . $admin . '的登录密码';
                addlog($_SESSION['id'], $content);
                $data['code'] = '1001';
                $data['result'] = '重置成功';
            } else {
                $data['code'] = '1002';
                $data['result'] = '重置失败';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 锁定管理员账号
     */
    public function lock_admin()
    {
        $info = M('admin', '', 'NBYH');
        if (!empty($_POST)) {
            $aid = I('aid');
            $status = I('status');
            $admin = $info->WHERE(['id' => $aid])->getField('name');
            $res = $info->WHERE(['id' => $aid])->setField('status', $status);
            if ($status == '1') {
                $lock = '解锁';
            } else {
                $lock = '锁定';
            }
            if ($res !== false) {
                $content = $lock . '了管理员' . $admin . '的账号';
                addlog($_SESSION['id'], $content);
                $data['code'] = '1001';
                $data['result'] = $lock . '成功';
            } else {
                $data['code'] = '1002';
                $data['result'] = $lock . '失败';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 角色列表
     */
    public function role_list()
    {
        $info = M('role', '', 'NBYH');
        $list = $info->WHERE(['status' => '1'])->SELECT();
        foreach ($list as $k => $v) {
            $list[$k]['ctime'] = date('Y-m-d H:i:s', $v['ctime']);
        }
        $this->assign(array(
            'list' => $list
        ));
        $this->display();
    }

    /**
     * 添加角色并分配权限页面
     */
    public function addrole()
    {
        $info = M('power', '', 'NBYH');
        $list = $info->WHERE("status=1")->ORDER("weight asc")->SELECT();
        $list = get_new_arrays($list);

        $this->assign(array(
            'list' => $list
        ));
        $this->display();
    }

    /**
     * 添加角色操作
     */
    public function add_role()
    {
        if (!empty($_POST)) {
            $data['rolename'] = I('rolename');
            $data['top_id'] = I('top');
            $data['next_id'] = I('next');
            $data['last_id'] = I('last');
            $data['ctime'] = time();
            $res = M('role', '', 'NBYH')->add($data);
            if ($res) {
                $content = "添加了" . I('rolename') . '职位';
                addlog($_SESSION['id'], $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '添加成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '添加失败']);
            }
        }
    }


    /**
     * 修改角色权限页面
     */
    public function modify_power()
    {
        $info = M('power', '', 'NBYH');
        $rid = I('get.rid');
        if (!empty($rid)) {
            $role = M('role', '', 'NBYH')->WHERE(['id' => $rid])->FIND();
            $top = explode(',', $role['top_id']);
            $next = explode(',', $role['next_id']);
            $last = explode(',', $role['last_id']);
            $list = $info->WHERE("status=1")->ORDER("weight asc")->SELECT();
            $list = get_new_arrays($list);

            $this->assign(array(
                'list' => $list,
                'role' => $role,
                'top' => $top,
                'next' => $next,
                'last' => $last
            ));
            $this->display();
        } else {
            $this->error('系统错误');
        }
    }

    /**
     * 修改角色权限操作
     */
    public function modify_role_power()
    {
        if (!empty($_POST)) {
            $rid = I('rid');
            $data['rolename'] = I('rolename');
            $data['top_id'] = I('top');
            $data['next_id'] = I('next');
            $data['last_id'] = I('last');
            $res = M('role', '', 'NBYH')->WHERE(['id' => $rid])->SAVE($data);
            if ($res !== false) {
                $content = "修改了" . I('rolename') . '的权限';
                addlog($_SESSION['id'], $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
            }
        }
    }

    /**
     * 删除角色操作
     */
    public function del_role()
    {
        $info = M('role', '', 'NBYH');
        if (!empty($_POST)) {
            $rid = I('rid');
            $role = $info->WHERE(['id' => $rid])->FIND();
            $res = $info->WHERE(['id' => $rid])->setField('status', '2');
            if ($res) {
                $content = '删除了' . $role['rolename'] . '角色';
                addlog($_SESSION['id'], $content);
                $data['code'] = '1001';
                $data['result'] = '删除成功';
            } else {
                $data['code'] = '1002';
                $data['result'] = '删除失败';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 会员列表
     */
    public function user_list()
    {
        $info = M('user', '', 'NBYH');
        $where=$this->set_where(array('u.is_distribution','u.is_cert','u.is_star'));
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.memberid='$content' or u.nickname='$content' or uu.nickname";
        }
        // if (!empty(I('get.status') || I('get.status') == '0')) {
        //     $where['u.is_cert'] = array('eq', I('get.status'));
        // }
        $tol = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $user = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE($where)->FIELD("u.*,uu.nickname as r_nickname")->LIMIT($page->listfirst, $page->listRows)->ORDER('u.id desc')->SELECT();
        foreach ($user as $k => $v) {
            $user[$k]['ctime'] = date('Y-m-d', $v['ctime']);
            switch ($v['sex']) {
                case 0:
                    $user[$k]['sex'] = '保密';
                    break;
                case 1:
                    $user[$k]['sex'] = '男';
                    break;
                case 2:
                    $user[$k]['sex'] = '女';
                    break;
            }
            if (empty($v['r_nickname'])) {
                $user[$k]['r_nickname'] = '无';
            }
            $user[$k]['direct']=M('user_team','','NBYH')->WHERE(['pre_openid'=>$v['openid']])->COUNT();
        }
        $this->assign(array(
            'user' => $user,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

    /**
     * 用户详情
     */
    public function user_detail()
    {
        $info = M('user', '', 'NBYH');
        $team = M('user_team', '', 'NBYH'); //我的团队
        $exchange = M('apply_exchange', '', 'NBYH'); //积分兑换
        $score = M('score_record', '', 'NBYH'); //积分记录
        $money_record = M('money_record', '', 'NBYH');//退款/打款记录
        $user_address = M('user_address', '', 'NBYH');
        $cert = M('user_cert', '', 'NBYH');
        $distribution = M('apply_distribution', '', 'NBYH');
        $login = M('user_login_log', '', 'NBYH');
        $withdraw=M('withdraw','','NBYH');
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

            //积分收入
            $user['income'] = $score->WHERE("openid='$openid' and (type=1 or type=3)")->SELECT();
//            $all = $info->WHERE(['openid' => $openid, 'type' => '1'])->SUM('score');
            //积分兑换
            $user['exchange'] = $exchange->WHERE(['openid' => $openid])->SELECT();
            //退款记录
            $user['refund_record'] = $money_record->JOIN("mr JOIN admin a ON mr.aid=a.id")->JOIN("JOIN user u ON mr.openid=u.openid")->WHERE("mr.openid='$openid' and mr.type='1'")->FIELD("mr.*,a.name,u.nickname")->SELECT();
            //打款记录
            $user['play_money_record'] = $money_record->JOIN("mr JOIN admin a ON mr.aid=a.id")->WHERE("mr.openid='$openid' and mr.type='2'")->FIELD("mr.*,a.name")->SELECT();
            //收货地址
            $user['address'] = $user_address->WHERE(['openid' => $openid, 'status' => '1'])->ORDER('type desc,ctime desc')->SELECT();
            foreach ($user['address'] as $k => $v) {
                $area = M('area', '', 'NBYH')->WHERE("id in({$v['area_id']})")->getField('name', true);
                $user['address'][$k]['area'] = implode('-', $area) . '-' . $v['detail'];
            }
            //用户认证
            $user_cert = $cert->WHERE(['openid' => $openid])->FIND();
            //分销商申请
            $user_distribution = $distribution->WHERE(['openid' => $openid])->FIND();

            $this->assign(array(
                'user' => $user,
                'user_cert' => $user_cert,
                'user_distribution' => $user_distribution
            ));
            $this->display();
        }
    }

//    /**
//     * 发送信息
//     */
//    public function send_msg()
//    {
//        $info = M('message', '', 'NBYH');
//        if (!empty($_POST)) {
//            $vid = I('vid');
//            $content = I('content');
//            if (!empty($vid) && !empty($content)) {
//                $data['vid'] = 'admin';
//                $data['to_vid'] = $vid;
//                $data['title'] = '系统信息';
//                $data['content'] = $content;
//                $data['ctime'] = time();
////                $data['aid']=$_SESSION['id'];
//                $res = $info->ADD($data);
//                if ($res) {
//                    $account = M('user', '', 'NBYH')->WHERE(['vid' => $vid])->getField('account');
//                    $log_content = '发送推送信息给' . $account;
//                    addlog($_SESSION['id'], $log_content);
//                    $this->send('系统信息', $content, $vid);
//                    $this->ajaxReturn(['code' => '1001', 'result' => '发送成功']);
//                } else {
//                    $this->ajaxReturn(['code' => '1002', 'result' => '发送失败']);
//                }
//            }
//        }
//    }


    /**
     * 重审用户操作
     */
    public function review_user()
    {
        $info = M('user', '', 'NBYH');
        $cert = M('user_cert', '', 'NBYH');
        $distribution = M('apply_distribution', '', 'NBYH');
        if (!empty($_POST)) {
            $info->startTrans();
            $openid = I('openid');
            $opinion = I('opinion');
            $res1 = $info->WHERE(['openid' => $openid])->setField('is_distribution', '2');
            $res2 = $info->WHERE(['openid' => $openid])->setField('is_cert', '2');
            $check_cert = $cert->WHERE(['openid' => $openid])->getField('id');
            if ($check_cert) {
                $res3 = $cert->WHERE(['openid' => $openid])->delete();
            } else {
                $res3 = 1;
            }
            $check_distribution = $distribution->WHERE(['openid' => $openid])->getField('id');
            if ($check_distribution) {
                $res4 = $distribution->WHERE(['openid' => $openid])->delete();
            } else {
                $res4 = 1;
            }
            if ($res1 !== false && $res2 !== false && $res3 && $res4) {
                $nickname = $info->WHERE(['openid' => $openid])->getField('nickname');
                $content = '重审用户' . $nickname . '。理由：' . $opinion;
                addlog($_SESSION['id'], $content);
                $title = '系统信息';
                $result = '重审';
                $remark = $opinion;
                check_notice($openid, $title, $nickname, $result, $remark);
                $info->commit();
                $this->ajaxReturn(['code' => '1001', 'result' => '提交成功']);
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '提交失败']);
            }

        }
    }

    /**
     * 修改推荐人
     */
    public function modify_recommend()
    {
        $info = M('user', '', 'NBYH');
        $team = M('user_team', '', 'NBYH');
        if (!empty($_POST)) {
            $info->startTrans();
            $myopenid = I('myopenid');
            $my_r_openid = $info->WHERE(['openid' => $myopenid])->getField('recommend_openid');
            $r_openid = I('r_openid');
            $is_distribution = $info->WHERE(['openid' => $myopenid])->getField('is_distribution');
            $modify_num = $info->WHERE(['openid' => $myopenid])->getField('recommend_modify_num');
            if ($is_distribution != '1') {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '只有代理用户才可修改推荐人']);
                return false;
            }
//            if ($modify_num == 1) {
//                $info->rollback();
//                $this->ajaxReturn(['code' => '1002', 'result' => '该用户已修改过一次推荐人，不可再修改']);
//                return false;
//            }
            if ($my_r_openid != $r_openid && $r_openid != $myopenid) {
                $res1 = $info->WHERE(['openid' => $myopenid])->setField('recommend_openid', $r_openid);
                $res3 = $info->WHERE(['openid' => $myopenid])->setInc('recommend_modify_num', '1');
                if ($res1 !== false && $res3 !== false) {
                    //检查之前是否已经有推荐人
//                    $check = $info->WHERE(['openid' => $myopenid])->getField('recommend_openid');
                    if (!empty($my_r_openid)) {
                        $res2 = $team->WHERE(['openid' => $myopenid])->setField('pre_openid', $r_openid);
                        if ($res2 === false) {
                            $info->rollback();
                            $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                            return false;
                        }
                        //原推荐人直推人数减少，如果少于6则取消公星
                        $direct_num=$team->WHERE(['pre_openid'=>$my_r_openid])->COUNT();
                        if($direct_num<6){
                            $res5=$info->WHERE(['openid'=>$my_r_openid])->setField('is_star','2');
                            if ($res5 === false) {
                                $info->rollback();
                                $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                                return false;
                            }
                        }
                    } else {
                        $data['openid'] = $myopenid;
                        $data['pre_openid'] = $r_openid;
                        $data['ctime'] = time();
                        $res2 = $team->add($data);
                        if (!$res2) {
                            $info->rollback();
                            $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                            return false;
                        }
                    }
                    //判断是否到达公星要求
                    $direct_num=$team->WHERE(['pre_openid'=>$r_openid])->COUNT();
                    if($direct_num>=6){
                        $res4=$info->WHERE(['openid'=>$r_openid])->setField('is_star','1');
                        if ($res4===false) {
                            $info->rollback();
                            $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                            return false;
                        }
                    }
                    $mynickname = $info->WHERE(['openid' => $myopenid])->getField('nickname');
                    $r_nickname = $info->WHERE(['openid' => $r_openid])->getField('nickname');
                    $content = '修改用户' . $mynickname . '的推荐人为' . $r_nickname;
                    addlog($_SESSION['id'], $content);
                    $memberid = $info->WHERE(['openid' => $myopenid])->getField('memberid');
                    modify_msg_notice($myopenid,$mynickname,$memberid,$r_nickname);
                    $info->commit();
                    $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
                } else {
                    $info->rollback();
                    $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                    return false;
                }
            } else {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '推荐人不能为同一个人']);
            }

        }
    }

    /**
     * 拉黑/解除黑名单
     */
    public function set_black()
    {
        $info = M('user', '', 'NBYH');
        $openid = I('openid');
        $type = I('type');
        if (!empty($_POST)) {
            $nickname = $info->WHERE(['openid' => $openid])->getField('nickname');
            $res = $info->WHERE(['openid' => $openid])->setField('type', $type);
            if ($res !== false) {
                if ($type == '2') {
                    $content = '拉黑' . $nickname;
                } else {
                    $content = '取消' . $nickname . '的黑名单';
                }
                addlog($_SESSION['id'], $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
            }
        }
    }

    /**
     *设为分销商
     */
    public function set_distribution()
    {
        $info = M('user', '', 'NBYH');
        $distribution = M('apply_distribution', '', 'NBYH');
        $openid = I('openid');
        if (!empty($_POST)) {
            $info->startTrans();
            $nickname = $info->WHERE(['openid' => $openid])->getField('nickname');
            $res1 = $info->WHERE(['openid'=>$openid])->setField('is_distribution','1');
            if ($res1 === false) {
                $info->rollback();
                $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                return false;
            }
            //判断该用户是否已申请分销商
            $find = $distribution->WHERE(['openid' => $openid])->getField('openid');
            if (!empty($find)) {
                $res2 = $distribution->WHERE(['openid' => $openid])->setField('status', '1');
                if ($res2 === false) {
                    $info->rollback();
                    $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
                    return false;
                }
            }
            $content='设置'.$nickname.'为分销商';
            addlog($_SESSION['id'], $content);
            check_notice($openid,'分销商申请',$nickname,'通过');
            $info->commit();
            $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
        }
    }


//    public function test(){
//        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
//
//        $mch_appid='wx316eb0c609ad7e3a';
//        $mchid='1450888502';
//        $nonce_str=md5(uniqid());
//        $partner_trade_no=date('YmdHis',time()).rand(1001,9999);
//        $openid='o8_jh0mq9uZ63rD5J5RFkjcFYXaA';
//        $check_name='NO_CHECK';
//        $amount='1';
//        $desc='用户提现';
//        $spbill_create_ip=getIp();
//
//        $sign=$this->get_sign($amount,$check_name,$desc,$mch_appid,$mchid,$nonce_str,$openid,$partner_trade_no,$spbill_create_ip);
//        $xmlData = '<xml>';
//        $xmlData = $xmlData.'<mch_appid>'.$mch_appid.'</mch_appid>';
//        $xmlData = $xmlData.'<mchid>'.$mchid.'</mchid>';
//        $xmlData = $xmlData.'<nonce_str>'.$nonce_str.'</nonce_str>';
//        $xmlData = $xmlData.'<partner_trade_no>'.$partner_trade_no.'</partner_trade_no>';
//        $xmlData = $xmlData.'<openid>'.$openid.'</openid>';
//        $xmlData = $xmlData.'<check_name>'.$check_name.'</check_name>';
//        $xmlData = $xmlData.'<amount>'.$amount.'</amount>';
//        $xmlData = $xmlData.'<desc>'.$desc.'</desc>';
//        $xmlData = $xmlData.'<spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>';
//        $xmlData = $xmlData.'<sign>'.$sign.'</sign>';
//        $xmlData = $xmlData.'</xml>';
//        $header[]= "Content-type: text/xml";//定义content-type为xml
//        $data = $this->curl_post_ssl($url, $xmlData);
//
//            var_dump($data);
//
//    }
//
//    function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
//    {
//        $ch = curl_init();
//        //超时时间
//        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
//        //这里设置代理，如果有的话
//        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
//        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
//        curl_setopt($ch,CURLOPT_URL,$url);
//        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
//        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
//
//        //以下两种方式需选择一种
//
//        //第一种方法，cert 与 key 分别属于两个.pem文件
//        //默认格式为PEM，可以注释
//        //curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//        curl_setopt($ch,CURLOPT_SSLCERT,'./Public/wxpay/cert/apiclient_cert.pem');
//        //默认格式为PEM，可以注释
//        //curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//        curl_setopt($ch,CURLOPT_SSLKEY,'./Public/wxpay/cert/apiclient_key.pem');
//        //curl_setopt($ch,CURLOPT_CAINFO,'./Public/cert/rootca.pem');
//        //第二种方式，两个文件合成一个.pem文件
//        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
//
//        if( count($aHeader) >= 1 ){
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
//        }
//
//        curl_setopt($ch,CURLOPT_POST, 1);
//        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
//        $data = curl_exec($ch);
//        return $data;
//    }
//
//    public function get_sign($amount,$check_name,$desc,$mch_appid,$mchid,$nonce_str,$openid,$partner_trade_no,$spbill_create_ip){
//        $stringA="amount={$amount}&check_name={$check_name}&desc={$desc}&mch_appid={$mch_appid}&mchid={$mchid}&nonce_str={$nonce_str}&openid={$openid}&partner_trade_no={$partner_trade_no}&spbill_create_ip={$spbill_create_ip}";
//        $stringSignTemp=$stringA."&key=aaaaabbbbbccccc11111222223333345";
//        $sign=md5($stringSignTemp);
//        $sign=strtoupper($sign);
//        return $sign;
//    }


}