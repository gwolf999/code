//������
jQuery.jPrintArea=function(el)
{
//����iframe
var iframe=document.createElement('IFRAME');
var doc=null;
//����iframe���ԣ�����ӵ�ҳ����
$(iframe).attr('style','position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
document.body.appendChild(iframe);
doc=iframe.contentWindow.document;
var links=window.document.getElementsByTagName('link');
for(var i=0;i<links.length;i++)
//ָ����ʽ
if(links[i].rel.toLowerCase()=='stylesheet')
doc.write('<link type="text/css" rel="stylesheet" href="'+links[i].href+'"></link>');
doc.write('<div class="'+$(el).attr("class")+'">'+$(el).html()+'</div>');
doc.close();
//iframe��ȡ���㣬����ӡ
iframe.contentWindow.focus();
iframe.contentWindow.print();
alert('Printing');
//ɾ��iframe
document.body.removeChild(iframe);
}
