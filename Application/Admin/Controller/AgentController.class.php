<?php
namespace Admin\Controller;
use Think\Controller;
class AgentController extends CommenController {
    /**
     * 用户认证列表
     */
    public function agent_list(){
        $info = M('user', '', 'NBYH');
        if (!empty(I('content'))) {
            $content = I('content');
            $where['_string'] = "u.memberid='$content' or u.nickname='$content' or uu.nickname='$content'";
        }
        if (!empty(I('get.status') || I('get.status') == '0')) {
            $where['u.is_cert'] = array('eq', I('get.status'));
        }
        $where['u.is_distribution']=array('eq','1');
        $tol = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $user = $info->JOIN("u LEFT JOIN user uu ON u.recommend_openid=uu.openid")->WHERE($where)->FIELD("u.*,uu.nickname as r_nickname")->LIMIT($page->listfirst, $page->listRows)->ORDER('u.id desc')->SELECT();
        foreach ($user as $k => $v) {
            $user[$k]['ctime'] = date('Y-m-d H:i:s', $v['ctime']);
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
        }
        $this->assign(array(
            'user' => $user,
            'fpage' => $fpage,
            'tol' => $tol
        ));
        $this->display();
    }

}