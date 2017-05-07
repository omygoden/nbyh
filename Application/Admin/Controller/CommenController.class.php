<?php
namespace Admin\Controller;
use Think\Controller;
class CommenController extends Controller {

    public function __construct(){
        parent::__construct();
        if(empty($_SESSION['id']))$this->redirect('indexadm.php/Login/login');
        $admin=M('admin','','NBYH')->JOIN("a JOIN role r ON a.rid=r.id")->WHERE(['a.id'=>$_SESSION['id']])->FIELD("a.id,r.top_id,r.next_id,r.last_id")->FIND();

        if(empty($admin) && $_SESSION['rid']!='99'){
            $this->error('您暂无职位,无法登录',U('indexadm.php/Login/login'));
        }
        $controller=strtolower(CONTROLLER_NAME);
        $action=strtolower(ACTION_NAME);

        $controller_id=M('power','','NBYH')->WHERE(['actionname'=>$controller,'level'=>'1'])->getField('id');
        $action_id=M('power','','NBYH')->WHERE("actionname='$action' and level>1")->getField('id');
        if($_SESSION['rid']!='99' && $controller!='index'){
            if(in_array($controller_id,explode(',',$admin['top_id']))){
                if(!in_array($action_id,explode(',',$admin['next_id'])) && !in_array($action_id,explode(',',$admin['last_id']))){
                    $this->error('您无访问权限');
                }
            }else{
                $this->error('您无访问权限');
            }
        }

    }

  public function set_where($arr){
        $where=array();
        foreach($arr as $k=>$v){
            $param=strpos($v,'.')!==false?explode('.',$v)['1']:$v;
            if(!empty(I($param)) || I($param)=='0'){
                $where[$v]=array('eq',I($param));
            }
        }
        return $where;
    }

    public function _empty(){
        $this->redirect('indexadm.php/Alerterror/error404');
    }


}