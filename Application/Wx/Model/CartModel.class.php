<?php
namespace Wx\Model;
use Think\Model;
class CartModel extends Model {
    protected $connection='NBYH';
    protected $trueTableName='cart';
//    protected $patchValidate = true;
    protected $updateFields=array('num');
    protected $_validate=array(
        array('goods_id','require','参数有误，请重新进入商城'),
        array('gs_id','require','参数有误，请重新进入商城'),
        array('openid','require','参数有误，请重新关注'),
        array('num','require','数量有误'),
        array('all_price','require','商品价格不能为空'),
    );

    //添加/更新时候检查数量是否大于库存
    public function check_num($gs_id,$num,$openid){
        $snum=M('goods_size','','NBYH')->WHERE(['id'=>$gs_id])->getField('num');
        $res=$this->check_cart($gs_id,$openid);
        if(!empty($res)){
            if(($num+$res['num'])>$snum || $snum<1){
                return false;
            }else{
                return true;
            }
        }else{
            if($num>$snum){
                return false;
            }else{
                return true;
            }
        }
    }
    //检查购物车里是否已经存在，存在则返回数据
    public function check_cart($gs_id,$openid){
        $res=$this->WHERE(['gs_id'=>$gs_id,'openid'=>$openid,'status'=>'1'])->FIND();
        if(!empty($res)){
            return $res;
        }else{
            return false;
        }
    }
    //检查商品是否已下架
    public function check_status($goods_id){
        $res=M('goods','','NBYH')->WHERE(['id'=>$goods_id])->getField('status');
        if($res=='1'){
            return true;
        }else{
            return false;
        }
    }


}