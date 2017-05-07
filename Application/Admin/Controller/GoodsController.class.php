<?php
namespace Admin\Controller;

use Think\Controller;

class GoodsController extends CommenController
{
    /**
     *商品列表
     */
    public function goods_list()
    {
        $info = M('goods', '', 'NBYH');
        $where['status']=array('neq','3');
        if (!empty(I('content'))) {
            $content = I('content');
//            $where['_string'] = "name='$content'";
            $where['name']=array('eq',$content);
        }
        if(!empty(I('is_top')))$where['is_top']=array('eq',I('is_top'));
        if(!empty(I('status')))$where['status']=array('eq',I('status'));
        if(!empty(I('is_add_depot')))$where['is_add_depot']=array('eq',I('is_add_depot'));

//        echo $a=get_microtime();
//        echo '<br>';
        $tol = $info->WHERE($where)->COUNT();
        $row = '10';
        $page = new PageController($tol, $row);
        $fpage = $page->fpage();
        $list = $info->WHERE($where)->ORDER('id desc')->LIMIT($page->listfirst, $page->listRows)->SELECT();
//        foreach($list as $k=>$v){
//            $goods_size=M('goods_size','','NBYH')->WHERE(['goods_id'=>$v['id']])->SELECT();
//            foreach($goods_size as $kk=>$vv){
//                if($vv['num']<=0){
//                    $msg='部分商品缺货，请及时补仓';
//                    $list[$k]['msg']=$msg;
//                    break;
//                }
//            }
//        }
        $this->assign(array(
            'list' => $list,
            'fpage' => $fpage,
            'tol' => $tol
        ));
//        echo $b=get_microtime();
//        echo '<br>';
//        echo $b-$a;
        $this->display();
    }

    /**
     * 商品置顶
     */
    public function to_top()
    {
        $info = M('goods', '', 'NBYH');
        if (!empty($_POST)) {
            $gid = I('gid');
            $is_top = I('is_top');
            $name = $info->WHERE(['id' => $gid])->getField('name');
            $res = $info->WHERE(['id' => $gid])->setField('is_top', $is_top);
            if ($res !== false) {
                switch ($is_top) {
                    case 1:
                        $is_top = '置顶';
                        break;
                    case 2:
                        $is_top = '取消置顶';
                        break;
                    default:
                        $is_top = '';
                        break;
                }
                $content = '将商品' . $name . $is_top;
                addlog($_SESSION['id'], $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '置顶成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '置顶失败']);
            }
        }
    }

    /**
     * 商品上架/下架
     */
    public function up_or_down()
    {
        $info = M('goods', '', 'NBYH');
        if (!empty($_POST)) {
            $gid = I('gid');
            $status = I('status');
            $name = $info->WHERE(['id' => $gid])->getField('name');
            $res = $info->WHERE(['id' => $gid])->setField('status', $status);
            if ($res !== false) {
                switch ($status) {
                    case 1:
                        $status = '上架';
                        break;
                    case 2:
                        $status = '下架';
                        break;
                    default:
                        $status = '';
                        break;
                }
                $content = '将商品' . $name . $status;
                addlog($_SESSION['id'], $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '修改成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '修改失败']);
            }
        }
    }

    /**
     * 删除商品
     */
    public function del_goods(){
        $info = M('goods', '', 'NBYH');
        if (!empty($_POST)) {
            $gid = I('gid');
            $name = $info->WHERE(['id' => $gid])->getField('name');
            $res = $info->WHERE(['id' => $gid])->setField('status','3');
            if ($res !== false) {
                $content = '将商品' . $name . '删除';
                addlog($_SESSION['id'], $content);
                $this->ajaxReturn(['code' => '1001', 'result' => '删除成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '删除失败']);
            }
        }
    }


    /**
     * 商品详情
     */
    public function goods_detail()
    {
        $info = M('goods', '', 'NBYH');
        $gid = I('get.gid');
        if (!empty($gid)) {
            $detail = $info->WHERE(['id' => $gid])->FIND();
            $detail['goods_imgs'] = M('goods_detail_img', '', 'NBYH')->WHERE(['goods_id' => $gid])->SELECT();
            $detail['goods_size'] = M('goods_size','','NBYH')->JOIN("gs JOIN size s ON gs.sid=s.id")->WHERE(['gs.goods_id'=>$gid])->FIELD("gs.*,s.name")->SELECT();
            $detail['detail']=M('goods_detail','','NBYH')->WHERE(['goods_id'=>$gid])->getField('detail');
            $this->assign(array(
                'detail' => $detail
            ));
            $this->display();
        }
    }

    /**
     * 添加商品
     */
    public function add_goods()
    {
        $info = D('Goods');
        $size = M('size', '', 'NBYH');
        $size = $size->WHERE(['status' => '1'])->SELECT();
        $json = json_encode($size);
        if (!empty($_POST)) {
            $info->startTrans();
            $data['name'] = I('name');
            $data['title_img'] = I('title_img');
            $data['description'] = I('description');
            $data['price'] = I('price');
            $data['stock'] = I('stock');
            $data['ctime'] = time();
            $sizes = I('size');
            $prices = I('post.prices');
            $stocks = I('post.stocks');

            $check = $info->create($data, '1');
            if ($check) {
                $res = $info->add();
                if ($res) {
                    //本来是详情图片，后期修改为轮播图
                    $detail_imgs = I('post.detail_imgs');
                    foreach ($detail_imgs as $k => $v) {
                        $img['goods_id'] = $res;
                        $img['img'] = $v;
                        $imgs[] = $img;
                    }
                    $res1 = M('goods_detail_img', '', 'NBYH')->addAll($imgs);
                    if (!$res1) {
                        $info->rollback();
                        $this->error('添加失败');
                        return false;
                    }
                    if (!empty($sizes)) {
                        foreach ($sizes as $key => $value) {
                            $arr['goods_id'] = $res;
                            $arr['sid'] = $sizes[$key];
                            $arr['num'] = $stocks[$key];
                            $arr['price'] = $prices[$key];
                            $arrs[] = $arr;
                        }
                        $res2 = M('goods_size', '', 'NBYH')->addAll($arrs);
                        if (!$res2) {
                            $info->rollback();
                            $this->error('添加失败');
                            return false;
                        }
                    }

                    $detail['goods_id']=$res;
                    $detail['detail']=$_POST['detail'];
                    $res3=M('goods_detail','','NBYH')->add($detail);
                    if(!$res3){
                        $info->rollback();
                        $this->error('添加失败');
                        return false;
                    }

                    addlog($_SESSION['id'], '添加商品' . I('name'));
                    $info->commit();
                    $this->success('添加成功', U('indexadm.php/Goods/goods_list'));
                    exit();

                } else {
                    $info->rollback();
                    $this->error('添加失败');
                }
            } else {
                $info->rollback();
                $this->error($info->getError());
            }

        }

        $this->assign(array(
            'size' => $size,
            'json' => $json
        ));
        $this->display();
    }

    /**
     * 修改商品
     */
    public function upd_goods()
    {
        $info = D('Goods');
        $gid = I('get.gid');
        if (!empty($gid)) {
            $goods = $info->WHERE(['id' => $gid])->FIND();
            $size = M('size', '', 'NBYH');
            $size = $size->WHERE(['status' => '1'])->SELECT();
            $json = json_encode($size);
            $goods['detail_imgs'] = M('goods_detail_img', '', 'NBYH')->WHERE(['goods_id' => $gid])->SELECT();
            $goods['goods_size'] = M('goods_size', '', 'NBYH')->WHERE(['goods_id' => $gid])->SELECT();
            $goods['detail']=M('goods_detail','','NBYH')->WHERE(['goods_id'=>$gid])->getField('detail');
//            var_dump($goods);
            $this->assign(array(
                'goods' => $goods,
                'size' => $size,
                'json' => $json
            ));
            if (!empty($_POST)) {
                $info->startTrans();
                $gid = I('gid');
                $data['name'] = I('name');
                $data['title_img'] = I('title_img');
                $data['description'] = I('description');
                $data['price'] = I('price');
                $data['stock'] = I('stock');
                $gs_id=I('gs_id');
                $sizes = I('size');
                $prices = I('post.prices');
                $stocks = I('post.stocks');
                $check = $info->create($data, '2');
                if ($check) {
                    $res = $info->WHERE(['id' => $gid])->SAVE();
                    if ($res !== false) {
                        $detail_imgs = I('post.detail_imgs');
                        if (!empty($detail_imgs)) {
                            foreach ($detail_imgs as $k => $v) {
                                $img['goods_id'] = $gid;
                                $img['img'] = $v;
                                $imgs[] = $img;
                            }
                            $res1 = M('goods_detail_img', '', 'NBYH')->addAll($imgs);
                            if (!$res1) {
                                $info->rollback();
                                $this->error('添加失败');
                                exit();
                            }

                        }
                        if (!empty($sizes)) {
                            foreach ($sizes as $key => $value) {
                                $find=M('goods_size','','NBYH')->WHERE(['id'=>$gs_id[$key]])->getField('id');
                                if($find){
                                    $upd['goods_id'] = $gid;
                                    $upd['sid'] = $sizes[$key];
                                    $upd['num'] = $stocks[$key];
                                    $upd['price'] = $prices[$key];
                                    $result=M('goods_size','','NBYH')->WHERE(['id'=>$gs_id[$key]])->SAVE($upd);
                                    if($result===false){
                                        $info->rollback();
                                        $this->error('添加失败');
                                        exit();
                                    }
                                }else{
                                    $arr['goods_id'] = $gid;
                                    $arr['sid'] = $sizes[$key];
                                    $arr['num'] = $stocks[$key];
                                    $arr['price'] = $prices[$key];
                                    $arrs[] = $arr;
                                }
                            }
                            if(!empty($arrs)){
                                $res2 = M('goods_size', '', 'NBYH')->addAll($arrs);
                                if (!$res2) {
                                    $info->rollback();
                                    $this->error('添加失败');
                                    exit();
                                }
                            }
                        }

//                        $detail['goods_id']=$res;
                        $detail=$_POST['detail'];
                        $res3=M('goods_detail','','NBYH')->WHERE(['goods_id'=>$gid])->setField('detail',$detail);
                        if($res3===false){
                            $info->rollback();
                            $this->error('添加失败');
                            return false;
                        }

                        addlog($_SESSION['id'], '修改商品' . I('name'));
                        $info->commit();
                        $this->success('修改成功', U('indexadm.php/Goods/goods_list'));
                        exit();
                    } else {
                        $info->rollback();
                        $this->error('修改失败');
                    }
                } else {
                    $info->rollback();
                    $this->error($info->getError());
                }
            }
        } else {
            $this->error('参数错误');
        }
        $this->display();
    }

    /**
     * 删除商品规格
     */
    public function del_goods_size()
    {
        $info = M('goods_size', '', 'NBYH');
        if (!empty($_POST)) {
            $gs_id = I('gs_id');
            $res = $info->WHERE(['id' => $gs_id])->DELETE();
            $size_name = $info->JOIN("gs JOIN size s ON gs.sid=s.id")->WHERE(['gs.id' => $gs_id])->getField('name');
            $goods_name = $info->JOIN("gs JOIN goods g ON gs.goods_id=g.id")->WHERE(['gs.id' => $gs_id])->getField('name');
            if ($res !== false) {
                addlog($_SESSION['id'], '删除商品' . $goods_name . '的' . $size_name . '规格');
                $this->ajaxReturn(['code' => '1001', 'result' => '删除成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '删除失败']);
            }
        }
    }

    /**
     * 删除商品详情图片
     */
    public function del_goods_img()
    {
        $info = M('goods_detail_img', '', 'NBYH');
        if (!empty($_POST)) {
            $gid = I('gid');
            $link = $info->WHERE(['id' => $gid])->getField('img');
            $res = $info->WHERE(['id' => $gid])->DELETE();
            if ($res !== false) {
                unlink(str_replace(C('MYURL'), '.', $link));
                addlog($_SESSION['id'], '删除商品' . I('name'));
                $this->ajaxReturn(['code' => '1001', 'result' => '删除成功']);
            } else {
                $this->ajaxReturn(['code' => '1002', 'result' => '删除失败']);
            }
        }
    }

    /**
     * 商品规格管理
     */
    public function size_list(){
        $info=M('size','','NBYH');
        $list=$info->WHERE(['status'=>'1'])->ORDER('ctime desc')->SELECT();

        $this->assign(array(
            'list'=>$list
        ));
        $this->display();
    }

    /**
     * 添加规格
     */
    public function add_size(){
        $info=M('size','','NBYH');
        if(!empty($_POST)){
            $data['name']=I('name');
            $data['ctime']=time();
            $res=$info->ADD($data);
            if($res){
                $content='添加规格';
                addlog($_SESSION['id'],$content);
                $this->ajaxReturn(['code'=>'1001','result'=>'添加成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'添加失败']);
            }
        }
    }

    /**
     * 删除规格
     */
    public function del_size(){
        $info=M('size','','NBYH');
        $sid=I('sid');
        if(!empty($sid)){
            $res=$info->WHERE(['id'=>$sid])->setField('status','2');
            if($res!==false){
                $content='删除规格';
                addlog($_SESSION['id'],$content);
                $this->ajaxReturn(['code'=>'1001','result'=>'删除成功']);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'删除失败']);
            }
        }
    }
}