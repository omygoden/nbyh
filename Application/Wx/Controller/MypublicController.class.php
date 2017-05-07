<?php
namespace Wx\Controller;
use Think\Controller;
class MypublicController extends CommenController {
    /**
     *上传图片
     */
    public function uploadimg(){
        if(!empty($_FILES)){
            $config = array(
                'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  array('jpg','jpeg','png'), //允许上传的文件后缀
                'rootPath'      =>  './Public/uploadimg/' //保存根路径
            );
            $upload=new \Think\Upload($config);
            $res=$upload->upload($_FILES);
            $res=$res['uploadimg'];
            if($res){
                $img_url=C('MYURL').ltrim($upload->rootPath,'./').$res['savepath'].$res['savename'];
                $this->ajaxReturn(['code'=>'1001','result'=>$img_url]);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'上传失败']);
            }
//            $res=json_encode($_FILES);
//            $this->ajaxReturn(['code'=>'1001','result'=>$res]);
        }
    }

    /**
     * 根据父类id，查询所有下一级地址分类
     */
    public function get_next_area(){
        $info=M('area','','NBYH');
        if(!empty($_POST)){
            $pid=I('pid');
            $next=$info->WHERE(['pid'=>$pid])->SELECT();
            if(!empty($next)){
                $this->ajaxReturn(['code'=>'1001','result'=>$next]);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'获取失败']);
            }
        }
    }


}