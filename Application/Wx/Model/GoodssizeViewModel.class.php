<?php
namespace Wx\Model;
use Think\Model\ViewModel;
class GoodssizeViewModel extends ViewModel {
    protected $connection='NBYH';
    public $viewFields=array(
        'gs'=>array('id','sid','goods_id','_table'=>'goods_size'),
        'g'=>array('name','title_img','_on'=>'gs.goods_id=g.id','_table'=>'goods'),
        's'=>array('name'=>'sname','_on'=>'gs.sid=s.id','_table'=>'size')
    );
}