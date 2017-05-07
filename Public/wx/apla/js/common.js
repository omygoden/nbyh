/**
 * Created by Administrator on 2016/11/29.
 */
(function () {
    document.getElementsByTagName('body')[0].style.height =window.screen.availHeight+'px';
})();
//===============底部弹出
function alertBottom(str) {
    var div=document.createElement('div');
    div.innerHTML='<span class="m-botAlert-con">' + str + ' </span>';
    div.className='m-botAlert';
    div.id='botAlert';
    document.body.appendChild(div);
    setTimeout(function () {
        div.className='m-botAlert alert-hide'
    }, 3000);
    setTimeout(function () {
        document.body.removeChild(div);
    }, 5000);
}