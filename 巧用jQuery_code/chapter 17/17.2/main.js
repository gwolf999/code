function lookup(inputString) {
    if(inputString.length == 0) {
        //在没有输入的时候隐藏提示框
        $(‘#suggestions’).hide();
    } else {
        //否则调用post方法调用服务器端的方法，获取以此打头的字符串集合
        $.post("rpc.php", {queryString: ""+inputString+""}, function(data){
            //返回不为空的话，则将内容放置到提示框中
            if(data.length >0) {
                $(‘#suggestions’).show();
                $(‘#autoSuggestionsList’).html(data);
            }
        });
    }
} // lookup
function fill(thisValue) {
    $(‘#inputString’).val(thisValue);
   $(‘#suggestions’).hide();
}
