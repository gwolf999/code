$(function() {
	$('tr.parent')   //�ҵ���Ԫ��
		.css("cursor","pointer")   //������ʽ
		.attr("title","Click to expand/collapse")  //�������
		.click(function(){   //����¼���Ӧ
			//������ʾ��Ԫ��
			$(this).siblings('.child-'+this.id).toggle();
		});
     //������Ԫ��
	$('tr[@class^=child-]').hide().children('td');
});
