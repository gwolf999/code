function initMenu() {
$('#menu ul').hide();  //隐藏菜单
$('#menu ul:first').show();  //显示第一项
$('#menu li a').click(   //点击事件
function() {
var checkElement = $(this).next();   //获得被点击的菜单
if((checkElement.is('ul')) && (checkElement.is(':visible'))) {  //判断是否为可见菜单
return false;
}
if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
$('#menu ul:visible').slideUp('normal'); //下拉显示菜单内容
checkElement.slideDown('normal');
return false;
}
}
);
}
$(document).ready(function() {initMenu();});
