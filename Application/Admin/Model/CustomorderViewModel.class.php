<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class CustomorderViewModel extends ViewModel {
    protected $connection='NBYH';
    public $viewFields=array(
        'co'=>array('order_no','ctime','money','status','_table'=>'custom_orders'),
        'a'=>array('name','_on'=>'co.aid=a.id','_table'=>'admin'),
        'g'=>array('name'=>'gname','_on'=>'co.goods_id=g.id','_table'=>'goods'),
        'gs'=>array('_on'=>'co.gs_id=gs.id','_table'=>'goods_size'),
        's'=>array('name'=>'sname','_on'=>'gs.sid=s.id','_table'=>'size'),
        'u'=>array('nickname','memberid','headimg','_on'=>'co.openid=u.openid','_table'=>'user')
    );
}