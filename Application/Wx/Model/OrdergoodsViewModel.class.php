<?php
namespace Wx\Model;
use Think\Model\ViewModel;
class OrdergoodsViewModel extends ViewModel {
    protected $connection='NBYH';
    public $viewFields=array(
        'oe'=>array('order_no','num','_table'=>'orders_ext'),
        'g'=>array('name','title_img','_on'=>'oe.goods_id=g.id','_table'=>'goods'),
        'gs'=>array('price','_on'=>'oe.gs_id=gs.id','_table'=>'goods_size'),
        's'=>array('name'=>'sname','_on'=>'gs.sid=s.id','_table'=>'size')
    );
}