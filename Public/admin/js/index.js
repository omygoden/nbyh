$(function () {
    //菜单点击
    var all_url = window.location.href;
    var now_url = window.location.hash;
    if (now_url) {
        now_url=now_url.replace("#", '');
        console.log(now_url);
        $("#J_iframe").attr('src', now_url);
        now_url=all_url.replace("#"+now_url,"");
    }else {
        now_url=window.location.href;
    }
    // J_iframe
    $(".J_menuItem").on('click', function () {
        var url = $(this).attr('href');
      //  window.location.href = now_url + '/' + url;
        $("#J_iframe").attr('src', url);
        return false;
    });

});