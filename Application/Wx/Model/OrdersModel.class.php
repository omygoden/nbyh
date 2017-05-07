<?php
namespace Wx\Model;

use Think\Model;

class OrdersModel extends Model
{
    protected $connection = 'NBYH';
    protected $trueTableName = 'orders';
//    protected $patchValidate = true;
    protected $updateFields = array('status');
    protected $_validate = array(
        array('openid', 'require', '参数有误，请重新关注'),
        array('order_no', 'require', '订单创建失败'),
        array('address_id', 'require', '地址不得为空'),
        array('money', 'require', '总金额不得为空'),
        array('ctime', 'require', '订单创建失败')
    );

    /**
     * 先判断商品是否上架，再检查库存是否足够
     * @param $goods_id 商品id
     * @param $gs_id 商品规格id
     * @param $num 对应的商品规格数量
     * @return string
     */
    public function check_goods($goods_id,$gs_id,$num)
    {
        $goods = M('goods', '', 'NBYH')->WHERE(['id' => $goods_id])->FIND();
        if($goods['status']=='1'){
            $stock = M('goods_size', '', 'NBYH')->WHERE(['id' => $gs_id])->getField('num');
            if ($num>$stock || $stock<1) {
                return '商品['.$goods['name'].']库存不足,订单生成失败';
            } else {
                return '1001';
            }
        }else{
            return '商品['.$goods['name'].']已下架,订单生成失败';
        }
    }

    /**
     * 核对商品总价是否正确
     * @param $gs_id 商品规格id,数组形式
     * @param $num 商品数量，数组形式
     * @param $all_price 订单总价
     * @return bool
     */
    public function check_price($gs_id,$num,$tol_price)
    {
        $goods_size = M('goods_size', '', 'NBYH')->WHERE(array('id'=>array('in',$gs_id)))->SELECT();
        $price='0';
        foreach($goods_size as $k=>$v){
            $price+=$v['price']*$num[$k];
        }
        if($price==$tol_price){
            return true;
        }else{
            return false;
        }
    }


}