// DOM ready
$(function () {
			//��������Ҫ��ʾ��ͼ��URL
			var images = new Array();
				images[0] = 'http://farm4.static.flickr.com/3293/2805001285_4164179461_m.jpg';
				images[1] = 'http://farm4.static.flickr.com/3103/2801920553_656406f2dd_m.jpg';
				images[2] = 'http://farm4.static.flickr.com/3248/2802705514_b7a0ba55c9_m.jpg';
			//ѭ������ҳ���ϵ�ÿ��li
			$("ul#portfolio li").each(function(index,el){
					//����ÿ��li��Ϊ������һ��ͼ��Ԫ�ء�
			        var img = new Image();
					// image onload
				//ͼ����ص�ʱ�����Ƚ������أ�Ȼ��ȥ��li��loading��ʽ�����ͼ���õ���ķ�ʽ��ʾ������
			        $(img).load(function () {
						// hide first
			            $(this).css('display','none'); 
					   $(el).removeClass('loading').append(this);
			            $(this).fadeIn();
					//�����ʱ��ȥ��Ԫ��
			        }).error(function () {
						$(el).remove();
			        }).attr('src', images[index]);
			});
		});
��������ͼ����ط�ʽ��ͼ�񼸺�����ͬʱ�����صģ���ô���ʵ��ͼ���˳����أ�����ǰһ��ͼƬ�������֮���ټ�����һ��ͼƬ��
��4���ȿ�һ��ҳ����룺
<div id="wrapper"></div>
��5��ҳ��Ԫ�صĴ�������������jQuery��ʵ�֡�������jQuery���룺
		// DOM Ready
		$(function () {
			var images = new Array();
				images[0] = 'http://farm4.static.flickr.com/3293/2805001285_4164179461_m.jpg';
				images[1] = 'http://farm4.static.flickr.com/3103/2801920553_656406f2dd_m.jpg';
				images[2] = 'http://farm4.static.flickr.com/3248/2802705514_b7a0ba55c9_m.jpg';
			//��ȡ��ͼ�������
			var max = $(images).length;
			//�������1�����ϵ�ͼ����ô������Ӧ������ULԪ�ؼ��뵽wrapper div�У����ҵ���LoadImage������
			if(max>0)
			{
				// create the UL element
				var ul = $('<ul id="portfolio"></ul>');
				// append to div#wrapper
				$(ul).appendTo($('#wrapper'));
				// load the first image
				LoadImage(0,max);
			}
			//��LoadImage�����У�ѭ���������е�ͼ�񣬶�ÿ��ͼ�񴴽�liԪ��
			function LoadImage(index,max)
				{
					if(index<max)
				{
				//����attr����ΪliԪ��������CSS��ʽ����������loading��gif������
					var list = $('<li id="portfolio_'+index+'"></li>').attr('class','loading');
				// ��li��ӵ�ulԪ����
					$('ul#portfolio').append(list);
				//��ȡ��ǰ��liԪ��
					var curr = $("ul#portfolio li#portfolio_"+index);
				//����ͼ��Ԫ��
					var img = new Image();
				// ����ͼ��
					$(img).load(function () {
					  $(this).css('display','none'); 
					  $(curr).removeClass('loading').append(this);
					  $(this).fadeIn('slow',function(){
		//���ûص������ķ������ڵ�ǰԪ�سɹ�ִ��fadeIn����֮����ȥ������һ��Ԫ�ص�LoadImage��������������ʵ�ֶ��ͼ���˳������ˡ�
					LoadImage(index+1,max);
					});
				}).error(function () {
					$(curr).remove();
					LoadImage(index+1,max);
				}).attr('src', images[index]);
		}
}
});
