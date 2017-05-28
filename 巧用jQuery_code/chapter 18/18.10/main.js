$(function() {
     //获取了xml文档
	$.ajax({
		url: 'xslt-test.xml',
		dataType: 'html',
		success: function(data) {
			$('#xmldata').text(data);
		}
	});
    //获取了xsl文档
	$.ajax({
		url: 'xslt-test.xsl',
		dataType: 'html',
		success: function(data) {
			$('#xsldata').text(data);
		}
	});
	// 用获取的xsl文档对xml文档进行转换
	$('#output').xslt({xmlUrl: 'xslt-test.xml', xslUrl: 'xslt-test.xsl'});
});
