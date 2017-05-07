/**
 * Created by xhj on 2016/11/11 0011.
 */
var upload = document.getElementById('upload');
upload.addEventListener('change', function () {
    var file = upload.files[0];
    window.URL = window.URL || window.webkitURL;
    var url = window.URL.createObjectURL(file);
    var img = new Image();
    img.src = url;
    img.onload = function (e) {
        window.URL.revokeObjectURL(this.src); //销毁
    };
    var $li_1 = $('<div class="img"><div class="img-box"><img src="' + url + '"></div><i class="icon icon-close"></i></div>');
    var $parent = $("#img-list-con");
    $("#img-list-con").append($li_1);
    $('.img-list .img i').click(function () {
        var parent = document.getElementById('img-list-con');
        var child = this.parentNode;
        parent.removeChild(child);
    });
}, false);
$('.img-list .img i').click(function () {
    var parent = document.getElementById('img-list-con');
    var child = this.parentNode;
    parent.removeChild(child);
});