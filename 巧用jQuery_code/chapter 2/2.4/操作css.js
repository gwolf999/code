$(document).ready(function(){
  // 重设文字大小的初始值
  ///获取当前的html元素的font-size, 保存在originalFontSize中，这就是默认页面的文字大小
  var originalFontSize = $('html').css('font-size');  / 
$(".resetFont").click(function(){
//当restFont被点击的时候，调用html元素的css方法，调整整个页面的文字大小，设置为之前保存的originalFontSize，即文字大小的初始值。
    $('html').css('font-size', originalFontSize);
  });
  // 用于增加页面上的文字大小
  $(".increaseFont").click(function(){
//当increaseFont被点击的时候，首先获取当前的文字大小，同样是通过css方法，但是由于只设置了属性值，所以这里的作用是读取此CSS属性的值
var currentFontSize = $('html').css('font-size');
//将此文字大小值转化为数字
var currentFontSizeNum = parseFloat(currentFontSize, 10);
//将这个数字乘以1.2，以增大文字尺寸
var newFontSize = currentFontSizeNum*1.2;
//利用css方法重新设置页面的文字尺寸
    $('html').css('font-size', newFontSize);
    return false;
  });
  // 减小页面文字的大小
  $(".decreaseFont").click(function(){
    var currentFontSize = $('html').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSize = currentFontSizeNum*0.8;
    $('html').css('font-size', newFontSize);
    return false;
  });
});
