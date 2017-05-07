<?php
namespace Admin\Controller;

use Think\Controller;

class PublicController extends Controller
{
    //该控制内的方法不受权限限制
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['id'])) {
            $this->redirect('indexadm.php/Login/login');
        }
    }

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
        }
    }

    /**
     * 获取角色列表
     */
    public function get_role()
    {
        $info = M('role', '', 'NBYH');
        if (!empty($_POST)) {
            $list = $info->WHERE(['status' => '1'])->FIELD('id,rolename')->SELECT();
            $this->ajaxReturn(['code' => '1001', 'result' => $list]);
        } else {
            $this->ajaxReturn(['code' => '1002', 'result' => '获取失败']);
        }
    }

    /**
     * 获取用户列表
     */
    public function get_user(){
        $info=M('user','','NBYH');
        if(!empty($_POST)){
            $list = $info->FIELD('openid,nickname')->SELECT();
            $this->ajaxReturn(['code' => '1001', 'result' => $list]);
        }else{
            $this->ajaxReturn(['code' => '1002', 'result' => '获取失败']);
        }
    }

    /**
     * 获取推荐人列表（除当前的推荐人、自身以及包含的下级外）
     */
    public function get_recommend(){
        $info=M('user','','NBYH');
        $team=M('user_team','','NBYH');
        if(!empty($_POST)){
            $r_openid=I('r_openid');
            $openid=I('openid');
            $list=$team->SELECT();
            $all_openid=get_new_array($list,$openid);
            $all_openid=array_column($all_openid,'openid');
            array_push($all_openid,$openid);
            array_push($all_openid,$r_openid);
            $where['openid']=array('not in',$all_openid);
            $list=$info->WHERE($where)->SELECT();
            if(!empty($list)){
                $this->ajaxReturn(['code'=>'1001','result'=>$list]);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'占无其他可选推荐人']);
            }
        }
    }

    public function get_goods_size(){
        $info=M('goods_size','','NBYH');
        $gid=I('gid');
        if(!empty($_POST)){
            $goods_size=$info->JOIN("gs JOIN size s ON gs.sid=s.id")->WHERE(['gs.goods_id'=>$gid])->FIELD("gs.id,s.name")->SELECT();
            if($goods_size!==false){
                $this->ajaxReturn(['code'=>'1001','result'=>'获取成功','data'=>$goods_size]);
            }else{
                $this->ajaxReturn(['code'=>'1002','result'=>'系统异常']);
            }
        }
    }

    /**
     * 编辑器图片上传
     */
    public function editor_upload(){
        $config = array(
            'exts' => array('jpg', 'jpeg','png', 'gif', 'ico'), //允许上传的文件后缀
            'rootPath' => './Public/upload/', //保存根路径
        );

        $img = new \Think\Upload($config);
        $arimg = $img->uploadOne($_FILES['fileDataFileName']);
        if ($arimg) {
            $imgs = $img->rootPath . $arimg['savepath'] . $arimg['savename'];
//            $smlimg = new \Think\Image();
//            $smlimg->open($imgs);
////            $smlimg->thumb(120, 120);
//            $small = $img->rootPath . $arimg['savepath'] . 'small' . $arimg['savename'];
//            $smlimg->save($small);
//            $smallimg = $small;
            //file_path必须写完整的路径名称，否则js端将获取不到图片，这样js将会把图片路径保存成数据流格式
            echo json_encode(['success'=>'true','msg'=>'success','file_path'=>C('MYURL').ltrim($imgs,'./')]);
        } else {
            echo json_encode(['success'=>'false','msg'=>'fail']);
        }
    }

    /**
     * 多图片上传
     */
    public function many_uploadimg(){
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


// Support CORS
// header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

// header("HTTP/1.0 500 Internal Server Error");
// exit;


// 5 minutes execution time
        @set_time_limit(5 * 60);

// Uncomment this one to fake upload time
        usleep(5000);

// Settings
// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = './Public/many_uploadimg/upload_tmp';
        $uploadDir = './Public/many_uploadimg/upload';

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds


// Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

// Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }

// Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = time().rand(100,999).'.'.pathinfo($_REQUEST["name"],PATHINFO_EXTENSION);
        } elseif (!empty($_FILES)) {
            $fileName = time().rand(100,999).'.'.pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
        } else {
            $fileName = uniqid("file_");
        }

        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


// Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


// Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }
        $uploadPath=C('MYURL').ltrim($uploadPath,'./');
//        die('{"jsonrpc" : "2.0", "error" : {"code": 1, "message": '.$uploadPath.'}, "id" : "id"}');
        echo json_encode(['code'=>'1001','result'=>$uploadPath]);
    }

    public function _empty(){
        $this->redirect('indexadm.php/Alerterror/error404');
    }



}