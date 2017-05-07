<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
    protected $connection='NBYH';
    protected $trueTableName='admin';
//    protected $patchValidate = true;
    protected $updateFields = array('pwd');
    protected $_validate=array(
        array('name','require','管理员名不能为空'),
        array('phone','require','账号不能为空'),
        array('pwd','require','密码不能为空'),
        array('rid','require','角色不能为空'),
    );

    public function check_admin($phone,$pwd){
        $res=$this->getByphone($phone);
        if($res){
            if($res['pwd']==$pwd && $res['status']=='1'){
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

}