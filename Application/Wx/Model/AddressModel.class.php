<?php
namespace Wx\Model;
use Think\Model;
class AddressModel extends Model {
    protected $connection='NBYH';
    protected $trueTableName='user_address';
//    protected $patchValidate = true;
    protected $_validate=array(
        array('openid','require','参数有误，请重新关注'),
        array('name','require','姓名不能为空'),
        array('mobile','require','手机号不能为空'),
        array('mobile','/^1[3|4|5|7|8]\d{9}$/','手机号格式有误'),
        array('area_id','require','地址不能为空'),
        array('detail','require','详细地址不能为空')
    );
}