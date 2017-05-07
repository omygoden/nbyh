<?php
namespace Wx\Model;
use Think\Model;
class ExchangeModel extends Model {
    protected $connection='NBYH';
    protected $trueTableName='apply_exchange';
//    protected $patchValidate = true;
    protected $_validate=array(
        array('openid','require','参数有误，请重新关注'),
        array('order_no','require','订单号不能为空'),
        array('name','require','姓名不能为空'),
        array('mobile','require','手机号不能为空'),
        array('mobile','/^1[3|4|5|7|8]\d{9}$/','手机号格式有误'),
        array('bank','require','开户行不能为空'),
        array('account','require','银行账户不能为空'),
        array('score','check_score','提现金额不能大于现有金额或者小于等于0','0','callback')
    );

    public function check_score($score){
        $info=M('user','','NBYH');
        $openid=$_SESSION['openid'];
        $user_score=$info->WHERE(['openid'=>$openid])->getField('score');
        if($score>$user_score || $score<=0){
//            return $user_score;
            return false;
        }else
            return true;
    }
}