$(document).ready(function(){
  // �������ִ�С�ĳ�ʼֵ
  ///��ȡ��ǰ��htmlԪ�ص�font-size, ������originalFontSize�У������Ĭ��ҳ������ִ�С
  var originalFontSize = $('html').css('font-size');  / 
$(".resetFont").click(function(){
//��restFont�������ʱ�򣬵���htmlԪ�ص�css��������������ҳ������ִ�С������Ϊ֮ǰ�����originalFontSize�������ִ�С�ĳ�ʼֵ��
    $('html').css('font-size', originalFontSize);
  });
  // ��������ҳ���ϵ����ִ�С
  $(".increaseFont").click(function(){
//��increaseFont�������ʱ�����Ȼ�ȡ��ǰ�����ִ�С��ͬ����ͨ��css��������������ֻ����������ֵ����������������Ƕ�ȡ��CSS���Ե�ֵ
var currentFontSize = $('html').css('font-size');
//�������ִ�Сֵת��Ϊ����
var currentFontSizeNum = parseFloat(currentFontSize, 10);
//��������ֳ���1.2�����������ֳߴ�
var newFontSize = currentFontSizeNum*1.2;
//����css������������ҳ������ֳߴ�
    $('html').css('font-size', newFontSize);
    return false;
  });
  // ��Сҳ�����ֵĴ�С
  $(".decreaseFont").click(function(){
    var currentFontSize = $('html').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSize = currentFontSizeNum*0.8;
    $('html').css('font-size', newFontSize);
    return false;
  });
});
