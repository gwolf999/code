$(function() {
	$('tr.parent')   //找到父元素
		.css("cursor","pointer")   //定义样式
		.attr("title","Click to expand/collapse")  //添加属性
		.click(function(){   //点击事件响应
			//下拉显示子元素
			$(this).siblings('.child-'+this.id).toggle();
		});
     //隐藏子元素
	$('tr[@class^=child-]').hide().children('td');
});
