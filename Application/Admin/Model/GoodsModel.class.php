<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
    protected $connection='NBYH';
    protected $trueTableName='goods';
//    protected $patchValidate = true;
    protected $updateFields = array('name','title_img','description','price','stock');
    protected $_validate=array(
        array('name','require','商品名不能为空'),
        array('title_img','require','商品图片不能为空'),
        array('price','require','商品价格不能为空'),
        array('stock','require','商品库存不能为空'),
    );
}