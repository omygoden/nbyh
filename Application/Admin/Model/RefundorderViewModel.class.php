<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class RefundorderViewModel extends ViewModel {
    protected $connection='NBYH';
    public $viewFields=array(
        'ar'=>array('reason','ctime'=>'rtime','check_opinion','check_time','status'=>'rstatus','return_money','_table'=>'apply_refund'),
        'o'=>array('order_no','money','ctime','_on'=>'ar.order_no=o.order_no','_table'=>'orders'),
        'u'=>array('nickname','headimg','_on'=>'o.openid=u.openid','_table'=>'user'),
        'ua'=>array('name','mobile','area_id','detail','_on'=>'o.address_id=ua.id','_table'=>'user_address')
    );
}