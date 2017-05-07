<?php
namespace Wx\Model;

use Think\Model;

class RefundModel extends Model
{
    protected $connection = 'NBYH';
    protected $trueTableName = 'apply_refund';
//    protected $patchValidate = true;

    protected $_validate = array(
        array('openid', 'require', '参数有误，请重新关注'),
        array('order_no', 'require', '参数有误，请重新关注'),
        array('reason', 'require', '申请原因不能为空'),
        array('ctime', 'require', '订单创建失败')
    );

}