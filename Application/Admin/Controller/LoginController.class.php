<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function login()
    {
        if ($_SESSION['phone'] && $_SESSION['id']) {
            $this->redirect('indexadm.php/Index/index');
        }
        $this->display();
    }

    /**
     * 用户登录验证
     */
    public function get_login()
    {
        if (!empty($_POST)) {
            $info=D('Admin');
            $phone = I('phone');
            $pwd = md5(I('pwd') . 'nbyh');
            $res=$info->check_admin($phone,$pwd);
            if($res){
                session('id',$res['id']);
                session('phone', $res['phone']);
                session('name', $res['name']);
                session('rid', $res['rid']);
                session('login_time', date('Y-m-d H:i:s', time()));
                $data['code'] = '1001';
                $data['result'] = '登录成功';
            }else{
                $data['code'] = '1002';
                $data['result'] = '用户名或者密码错误';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 用户密码修改
     */
    public function modify()
    {
        if(empty($_SESSION['id'])){
            $this->redirect('indexadm.php/Login/login');
        }
        $this->display();
    }

    /**
     * 修改密码操作
     */
    public function modify_pwd()
    {
        if(empty($_SESSION['id'])){
            $this->redirect('indexadm.php/Login/login');
        }
        if (!empty($_POST)) {
            $info = M('admin', '', 'NBYH');
            $old_pwd = md5(I('old_pwd') . 'nbyh');
            $new_pwd = md5(I('new_pwd') . 'nbyh');
            $check = $info->WHERE(['pwd' => $old_pwd, 'phone' => $_SESSION['phone']])->FIND();
            if (!empty($check)) {
                $res = $info->WHERE(['pwd' => $old_pwd, 'phone' => $_SESSION['phone']])->setField('pwd', $new_pwd);
                if ($res !== false) {
                    $content = '修改登录密码';
                    addlog($check['id'], $content);
                    session(null);
                    $data['code'] = '1001';
                    $data['result'] = '修改成功';
                } else {
                    $data['code'] = '1002';
                    $data['result'] = '修改失败';
                }
            } else {
                $data['code'] = '1002';
                $data['result'] = '原始密码错误';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 退出登录
     */
    public function logout(){
        session(null);
        $this->redirect('indexadm.php/Login/login');
    }

    public function _empty(){
        $this->redirect('indexadm.php/Alerterror/error404');
    }
}