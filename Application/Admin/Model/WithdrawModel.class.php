<?php
namespace Admin\Model;
use Think\Model;
class WithdrawModel extends Model {
    protected $connection='NBYH';
    protected $trueTableName='withdraw';
//    protected $patchValidate = true;
    protected $_validate=array(
        array('orderid','require','商品订单号不能为空'),
        array('bankaccount','require','银行账户号不能为空'),
        array('truename','require','用户真实姓名不能为空'),
        array('ctime','require','创建时间不能为空'),
        array('money','check_money','金额不得小于等于0','0','callback'),
        array('uid','require','用户标示不能为空'),
        array('quit','require','随机值不能为空'),
        array('wid','require','关联id不能为空'),
        array('bankclass','require','银行类型不能为空'),
    );

    //检查金额
    public function check_money($money){
        if($money<=0){
            return false;
        }else{
            return true;
        }
    }
}