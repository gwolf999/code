//定义插件
jQuery.jPrintArea=function(el)
{
//创建iframe
var iframe=document.createElement('IFRAME');
var doc=null;
//定义iframe属性，并添加到页面上
$(iframe).attr('style','position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
document.body.appendChild(iframe);
doc=iframe.contentWindow.document;
var links=window.document.getElementsByTagName('link');
for(var i=0;i<links.length;i++)
//指定样式
if(links[i].rel.toLowerCase()=='stylesheet')
doc.write('<link type="text/css" rel="stylesheet" href="'+links[i].href+'"></link>');
doc.write('<div class="'+$(el).attr("class")+'">'+$(el).html()+'</div>');
doc.close();
//iframe获取焦点，并打印
iframe.contentWindow.focus();
iframe.contentWindow.print();
alert('Printing');
//删除iframe
document.body.removeChild(iframe);
}
