$(window).ready(function () {
  //获取sliderGallery中的每个图片，对它们进行操作
  $('div.sliderGallery').each(function () {
    var ul = $('ul', this);
    //获取sliderGallery中的ul元素
    var productWidth = ul.innerWidth() - $(this).outerWidth();  //获得宽度
    //利用ui.slider.js中的slider()方法，实现相册效果
    var slider = $('.slider', this).slider({ 
      handle: '.handle',
      minValue: 0, 
      maxValue: productWidth, 
      slide: function (ev, ui) {
        ul.css('left', '-' + ui.value + 'px');
      }, 
      stop: function (ev, ui) {
        ul.animate({ 'left' : '-' + ui.value + 'px' }, 500, 'linear');
      }
    });
  });
});
