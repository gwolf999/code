$(function() {
     //��ȡ��xml�ĵ�
	$.ajax({
		url: 'xslt-test.xml',
		dataType: 'html',
		success: function(data) {
			$('#xmldata').text(data);
		}
	});
    //��ȡ��xsl�ĵ�
	$.ajax({
		url: 'xslt-test.xsl',
		dataType: 'html',
		success: function(data) {
			$('#xsldata').text(data);
		}
	});
	// �û�ȡ��xsl�ĵ���xml�ĵ�����ת��
	$('#output').xslt({xmlUrl: 'xslt-test.xml', xslUrl: 'xslt-test.xsl'});
});
