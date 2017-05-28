// DOM ready
$(function () {
			//定义了需要显示的图像URL
			var images = new Array();
				images[0] = 'http://farm4.static.flickr.com/3293/2805001285_4164179461_m.jpg';
				images[1] = 'http://farm4.static.flickr.com/3103/2801920553_656406f2dd_m.jpg';
				images[2] = 'http://farm4.static.flickr.com/3248/2802705514_b7a0ba55c9_m.jpg';
			//循环遍历页面上的每个li
			$("ul#portfolio li").each(function(index,el){
					//对于每个li，为其增加一个图像元素。
			        var img = new Image();
					// image onload
				//图像加载的时候首先将其隐藏，然后去除li的loading样式，最后将图像用淡入的方式显示出来。
			        $(img).load(function () {
						// hide first
			            $(this).css('display','none'); 
					   $(el).removeClass('loading').append(this);
			            $(this).fadeIn();
					//出错的时候，去除元素
			        }).error(function () {
						$(el).remove();
			        }).attr('src', images[index]);
			});
		});
以上这种图像加载方式，图像几乎都是同时被加载的，那么如何实现图像的顺序加载，即在前一张图片加载完成之后再加载下一张图片。
（4）先看一下页面代码：
<div id="wrapper"></div>
（5）页面元素的创建工作都将在jQuery中实现。下面是jQuery代码：
		// DOM Ready
		$(function () {
			var images = new Array();
				images[0] = 'http://farm4.static.flickr.com/3293/2805001285_4164179461_m.jpg';
				images[1] = 'http://farm4.static.flickr.com/3103/2801920553_656406f2dd_m.jpg';
				images[2] = 'http://farm4.static.flickr.com/3248/2802705514_b7a0ba55c9_m.jpg';
			//获取了图像的数量
			var max = $(images).length;
			//如果包含1张以上的图像，那么创建对应数量的UL元素加入到wrapper div中，并且调用LoadImage方法。
			if(max>0)
			{
				// create the UL element
				var ul = $('<ul id="portfolio"></ul>');
				// append to div#wrapper
				$(ul).appendTo($('#wrapper'));
				// load the first image
				LoadImage(0,max);
			}
			//在LoadImage方法中，循环遍历所有的图像，对每个图像创建li元素
			function LoadImage(index,max)
				{
					if(index<max)
				{
				//利用attr方法为li元素增加了CSS样式，即加上了loading的gif背景。
					var list = $('<li id="portfolio_'+index+'"></li>').attr('class','loading');
				// 把li添加到ul元素中
					$('ul#portfolio').append(list);
				//获取当前的li元素
					var curr = $("ul#portfolio li#portfolio_"+index);
				//创建图像元素
					var img = new Image();
				// 加载图像
					$(img).load(function () {
					  $(this).css('display','none'); 
					  $(curr).removeClass('loading').append(this);
					  $(this).fadeIn('slow',function(){
		//采用回调函数的方法，在当前元素成功执行fadeIn方法之后再去调用下一个元素的LoadImage方法，这样就能实现多个图像的顺序加载了。
					LoadImage(index+1,max);
					});
				}).error(function () {
					$(curr).remove();
					LoadImage(index+1,max);
				}).attr('src', images[index]);
		}
}
});
