$(window).ready(function () {
  //��ȡsliderGallery�е�ÿ��ͼƬ�������ǽ��в���
  $('div.sliderGallery').each(function () {
    var ul = $('ul', this);
    //��ȡsliderGallery�е�ulԪ��
    var productWidth = ul.innerWidth() - $(this).outerWidth();  //��ÿ��
    //����ui.slider.js�е�slider()������ʵ�����Ч��
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
