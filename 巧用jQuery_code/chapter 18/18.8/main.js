//调用ajax方法
$.ajax({
  type: "GET",
  url: "/xml/simple.xml", //获取simple.xml
  dataType: "xml", //返回为xml类型
  complete: function(data) {
   var json = $.xmlToJSON(data.responseXML); //将XML数据转化为JSON数据
   alert(json.node[1].Text); //获取JSON数据
}
});
