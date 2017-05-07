<?php
namespace Wx\Model;
use Think\Model\ViewModel;
class TestViewModel extends ViewModel {
    protected $connection='NBYH';
    public $viewFields=array(
        'user'=>array('openid','nickname','_table'=>'user')
    );
}