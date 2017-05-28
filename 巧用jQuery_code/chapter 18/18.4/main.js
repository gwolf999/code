/* global variables */
var descList = new Array();
var itemList = "";
$(document).ready(function() {
  var content = "";
  $.getJSON(script_path,
   function(json){
     if(json.count > 0) {
       content = output_feed_items(json);
     } else {
       content = "The request did not return results.";
     }
     $("#pipes-feed-content").html(content);
   }
  );
});
function output_feed_items(json) {
  document.title = json.value.title;
  var heading = '<h3>' + json.value.title + '</h3>';
  for (i=0;i<json.count;i++) {
    itemList += make_feed_item(json.value.items[i], i);
    descList.push(make_feed_desc(json.value.items[i], i));
  }
  return heading + itemList;
}
function make_feed_item(item, item_id) {
  return '<h4 id="heading-' + item_id + '">' +
      '<a href="#heading-' + item_id +
      '" onclick="toggle_feed_desc(' + item_id + ');">' +
      item.title + '</a></h4>';
}
function make_feed_desc(item, item_id) {
  var desc_info = '<span="item-submitted">Published: ' +
    item.pubDate + '</span>';
  desc_info += ' - <a href="' + item.link + '">Link to Article</a>';
  var desc_info = '<div class="item-info">' + desc_info + '</div>';
  return '<div id="desc-' + item_id + '">' +
    desc_info + item.description + '</div>';
}
function toggle_feed_desc(item_id) {
  var heading = '#heading-' + item_id;
  var item_div = 'div#desc-' + item_id;
  if ($(item_div).html()) {
    $(item_div).remove();
  } else {
    $(heading).after(descList[item_id]);
  }
}
