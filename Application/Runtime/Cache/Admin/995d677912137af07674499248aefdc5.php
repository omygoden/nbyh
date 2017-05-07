<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> - 基本表单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="../favicon.ico">
    <link href="/Public/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/admin/css/animate.css" rel="stylesheet">
    <link href="/Public/admin/css/style.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="/Public/admin/css/liandong.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/admin/css/simditor.css" />
    <!--<script src="/Public/admin/upload_imgs/jquery.js"></script>-->
    <script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>
    <link rel="stylesheet" type="text/css" href="/Public/admin/upload_imgs/diyUpload/css/webuploader.css">
    <link rel="stylesheet" type="text/css" href="/Public/admin/upload_imgs/diyUpload/css/diyUpload.css">
    <script type="text/javascript"
            src="/Public/admin/upload_imgs/diyUpload/js/webuploader.html5only.min.js"></script>
    <script type="text/javascript" src="/Public/admin/upload_imgs/diyUpload/js/diyUpload.js"></script>
</head>
<style>

</style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>产品管理-
                        <small>修改产品-填写信息</small>
                    </h5>

                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data">

                        <input type="hidden" class="form-control" value="<?php echo ($_GET['gid']); ?>" name="gid">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*商品名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="<?php echo ($goods["name"]); ?>" name="name"
                                       placeholder="请输入商品名称" id="goods_name">

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">*商品显示价格</label>
                            <div class="col-sm-10">
                                <input type="number" value="<?php echo ($goods["price"]); ?>" class="form-control" name="price"
                                       placeholder="请输入参考价格" id="goods_price">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">*商品总库存</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" value="<?php echo ($goods["stock"]); ?>" name="stock"
                                       placeholder="请输入商品库存" id="goods_stock">
                            </div>
                        </div>

                        <div class="form-group tables">
                            <label class="col-sm-2 control-label">*商品规格</label>

                                <div class="">
                                    <button class="btn btn-success btn-lg add" style="padding:6.5px 16px" type="button"><i class="glyphicon glyphicon-plus"></i>
                                    </button>
                                </div>
                        </div>

                        <?php if(is_array($goods["goods_size"])): foreach($goods["goods_size"] as $key=>$sizes): ?><div class="form-group tables">
                                <input type="hidden"  name="gs_id[]"  value="<?php echo ($sizes["id"]); ?>">
                            <label class="col-sm-2 control-label">*商品规格</label>
                            <div class="">
                                <div class="col-sm-1">
                                    <select  class="form-control m-b" name="size[]">
                                        <?php if(is_array($size)): foreach($size as $key=>$sizess): ?><option value="<?php echo ($sizess["id"]); ?>" <?php if($sizes['sid'] == $sizess['id']): ?>selected<?php endif; ?>><?php echo ($sizess["name"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-1" >
                                    <input type="text"  name="prices[]" class="form-control" value="<?php echo ($sizes["price"]); ?>"  placeholder="价格">
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" name="stocks[]"  class="form-control" value="<?php echo ($sizes["num"]); ?>" placeholder="库存">
                                </div>
                                <button class="btn btn-danger btn-lg del" onclick="del_sizes('<?php echo ($sizes["id"]); ?>')"  id="del<?php echo ($sizes["id"]); ?>" style="padding:6.5px 16px" type="button"><i class="fa fa-times"></i></button>
                                </button>
                            </div>
                            </div><?php endforeach; endif; ?>

                        <div class="form-group" id="before">
                            <label class="col-sm-2 control-label">*商品简介</label>
                            <div class="col-sm-10">
                                <textarea type="text" class="form-control" value="<?php echo ($goods["description"]); ?>"
                                          name='description' id="description"><?php echo ($goods["description"]); ?></textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*商品标题图片</label>
                            <div class="col-sm-10">
                                <div class="btn-file">
                                    <label for="uploadimg" class="btn btn-primary">上传图片</label>
                                    <input id="uploadimg" onchange="upload()" style="display:none" type="file"
                                           name="uploadimg">
                                    <input id="title_img" type="hidden" value="<?php echo ($goods["title_img"]); ?>" name="title_img">
                                    <!--<span class="help-block m-b-none">请上传图片大小为400*400px</span>-->
                                </div>
                                <div class="product_img" id="img">
                                    <img style="width:15rem" src="<?php echo ($goods["title_img"]); ?>"/>
                                    <!--<img  src="/Public/admin/img/webuploader.png" style="height:150px;width: 150px">-->
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group" id="boxs">
                            <label class="col-sm-2 control-label">商品轮播图片</label>
                            <div id="box" style="margin-left:15px">
                                <div id="test"><img src="/Public/admin/img/webuploader.png"
                                                    style="height:150px;width: 150px"></div>
                            </div>
                            <div class="parentFileBox" style="width: 100%">
                                <ul class="fileBoxUl" style="margin-left:14%">
                                    <?php if(is_array($goods["detail_imgs"])): foreach($goods["detail_imgs"] as $key=>$detail): ?><li id="fileBox_WU_FILE_0" class="diyUploadHover">
                                        <div class="viewThumb"><img class="old_img" src="<?php echo ($detail["img"]); ?>"></div>
                                        <div class="diyCancel" id="imgs<?php echo ($detail["id"]); ?>" onclick="del_goods_img('<?php echo ($detail["id"]); ?>',this.id);"></div>
                                    </li><?php endforeach; endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*商品详情</label>
                            <div class="col-sm-10">
                                <textarea type="text" class="form-control" name='detail' id="detail"></textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input class="btn btn-primary" type="submit" value="提 交" id="sub"/>
                                <!--<button class="btn btn-white" type="reset">重置</button>-->
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- 全局js -->
<!--<script src="/Public/admin/js/jquery.min.js?v=2.1.4"></script>-->
<!--<script src="/Public/admin/js/bootstrap.min.js?v=3.3.6"></script>-->
<!-- 自定义js -->
<script src="/Public/admin/js/content.js?v=1.0.0"></script>
<script src="/Public/admin/js/plugins/layer/layer.min.js"></script>
<!-- <script src="/Public/admin/js/liandong.js"></script>  -->
<script type="text/javascript" src="/Public/admin/js/module.min.js"></script>
<script type="text/javascript" src="/Public/admin/js/hotkeys.min.js"></script>
<script type="text/javascript" src="/Public/admin/js/uploader.min.js"></script>
<script type="text/javascript" src="/Public/admin/js/simditor.min.js"></script>
<script type="text/javascript" src="/Public/admin/js/simditor-fullscreen.js"></script>

<script src="/Public/admin/js/plugins/layer/laydate/laydate.js"></script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->

<script>
    $('#test').diyUpload({
        url: '/indexadm.php/Public/many_uploadimg',
        success: function (data) {
            if (data.code == '1001') {
                $('#boxs').append('<input class="detail" name="detail_imgs[]" value="' + data.result + '" type="hidden" >');
            } else {
                var d = data.error;
//                console.log( d );
                layer.alert(d.message);
            }
        },
        error: function (err) {
//            console.info( err );
        },
        accept: {
            title: "Images",
            extensions: "gif,jpg,jpeg,bmp,png",
            mimeTypes: "image/gif,image/jpg,image/jpeg,image/bmp,image/png"
        }
    });
</script>
<script src="/Public/admin/js/ajaxfileupload.js"></script>
<script>
    function upload() {
//        layer.alert(123);
        var tourl = '/indexadm.php/Public/uploadimg';
        $.ajaxFileUpload({
            url: tourl,//需要链接到服务器地址
            secureuri: false,
            fileElementId: 'uploadimg',//文件选择框的id属性file的id
            dataType: 'json',
            success: function (data) {
//                console.log(data);
                if (data.code == '1001') {
                    var html = '<img  style="width:15rem" src="' + data.result + '" />';
                    $('#img').html(html);
                    $('#title_img').val(data.result);
                } else {
                    if (data.status == '0') {
                        layer.alert(data.info);
                    } else {
                        layer.alert(data.result);
                    }
                }
            }
        });
    }
    $('#sub').on('click', function () {
        var goods_name = $('#goods_name').val();
        var goods_price = $('#goods_price').val();
        var goods_stock = $('#goods_stock').val();
        var title_img = $('#title_img').val();
        var detail_imgs = $('.detail').length;
        var old_imgs=$('.old_img').length;
        var goods_size=$('.m-b').length;

        if (goods_name == '') {
            layer.alert('商品名不能为空');
            return false;
        }
        if (goods_price <= '0') {
            layer.alert('商品价格不能为空或者小于0');
            return false;
        }
        if (goods_stock <= '0') {
            layer.alert('商品库存不能为空或者小于0');
            return false;
        }
        if(goods_size==''){
            layer.alert('商品规格不能为空');
            return false;
        }
        if (title_img == '') {
            layer.alert('商品标题图片不能为空');
            return false;
        }
        if (detail_imgs <= '0'&& old_imgs<='0') {
            layer.alert('商品详情图不能为空');
            return false;
        }
    });

    function del_goods_img(id,imgid){
        var check=layer.confirm('是否确定删除？');
        if(!check){
            return false;
        }
        $.post('/indexadm.php/Goods/del_goods_img',{gid:id},function(data){
            if(data.code=='1001'){
                $('#'+imgid).parent().remove();
                layer.alert(data.result);
//                window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
    }

    $('.add').click(function(){
        var size='<?php echo ($json); ?>';
        var sizes=eval('('+size+')');
        var html='';
        var num=$('.tables').length;
        var num=num+1;
        if(num>8){
            layer.alert('图文最多不能超过8个');
            return false;
        }
        console.log(sizes);
        html+='<div class="form-group tables"><label class="col-sm-2 control-label">*商品规格</label>';
        html+='<div class="">';
        html+='<div class="col-sm-1">';
        html+='<select name="size[]" class="form-control m-b">';
        $(sizes).each(function(k,v){
            html+='<option value="'+ v.id+'">'+ v.name+'</option>';
        });
        html+='</select>';
        html+='</div>';
        html+='<div class="col-sm-1" >';
        html+='<input type="text" name="prices[]"  class="form-control"  placeholder="价格">';
        html+='</div>';
        html+='<div class="col-sm-1">';
        html+='<input type="text" name=stocks[]"  class="form-control"  placeholder="库存">';
        html+='</div>';
        html+='<button class="btn btn-danger btn-lg del" onclick="del(this.value)" value="del'+num+'" id="del'+num+'" style="padding:6.5px 16px" type="button"><i class="fa fa-times"></i></button>';
        html+='</div>';
        html+='</div>';
        $('#before').before(html);
    });
    function del(ids){
        $('#'+ids).parent().parent().remove();
        var num=$('.tables').length;
        console.log(num);
    }
    function del_sizes(id){
        var check=confirm('是否确定删除');
        if(!check){
            return false;
        }
        $.post('/indexadm.php/Goods/del_goods_size',{gs_id:id},function(data){
            if(data.code=='1001'){
                $('#del'+id).parent().parent().remove();
                layer.alert(data.result);
//                window.location.reload();
            }else{
                if(data.status=='0'){
                    layer.alert(data.info);
                }else{
                    layer.alert(data.result);
                }
            }
        });
    }
    var editor = new Simditor({
        textarea: $('#detail'),
        toolbar: [
            'title','bold','italic','underline','strikethrough','fontScale','color','ol','ul','blockquote','code','table','hr','indent','outdent','alignment','image','fullscreen'
        ],
        defaultImage: 'images/image.jpg',
        upload:{
            url: '/indexadm.php/Public/editor_upload',
            params: null,
            fileKey: 'fileDataFileName',
            connectionCount: 5,
            leaveConfirm: '正在上传...'
        }
    });
        var content='<?php echo ($goods["detail"]); ?>';
        editor.setValue(content);
</script>
</body>
</html>