//����ajax����
$.ajax({
  type: "GET",
  url: "/xml/simple.xml", //��ȡsimple.xml
  dataType: "xml", //����Ϊxml����
  complete: function(data) {
   var json = $.xmlToJSON(data.responseXML); //��XML����ת��ΪJSON����
   alert(json.node[1].Text); //��ȡJSON����
}
});
