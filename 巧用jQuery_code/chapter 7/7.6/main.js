function initMenu() {
$('#menu ul').hide();  //���ز˵�
$('#menu ul:first').show();  //��ʾ��һ��
$('#menu li a').click(   //����¼�
function() {
var checkElement = $(this).next();   //��ñ�����Ĳ˵�
if((checkElement.is('ul')) && (checkElement.is(':visible'))) {  //�ж��Ƿ�Ϊ�ɼ��˵�
return false;
}
if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
$('#menu ul:visible').slideUp('normal'); //������ʾ�˵�����
checkElement.slideDown('normal');
return false;
}
}
);
}
$(document).ready(function() {initMenu();});
