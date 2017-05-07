<?php
namespace Wx\Model;
use Think\Model\ViewModel;
class MycartViewModel extends ViewModel {
    protected $connection='NBYH';
    public $viewFields=array(
        'c'=>array('id','price','num','gs_id','goods_id','_table'=>'cart'),
        'gs'=>array('_on'=>'c.gs_id=gs.id','_table'=>'goods_size'),
        'g'=>array('name','title_img','status','_on'=>'gs.goods_id=g.id','_table'=>'goods'),
        's'=>array('name'=>'sname','_on'=>'gs.sid=s.id','_table'=>'size')
    );
}