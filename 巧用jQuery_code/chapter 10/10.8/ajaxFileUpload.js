function ajaxFileUpload()
    {
        // ajaxFileUpload()方法用于在传输开始和结束的时候显示或隐藏元素。在ajaxFileUpload中调用了Ajax方法
        $("#loading")
        .ajaxStart(function(){
            $(this).show();
        })
        .ajaxComplete(function(){
            $(this).hide();
        });
    $.ajaxFileUpload
        (
            {
                url:'doajaxfileupload.php', //定义了后台接收文件上传的URL
                secureuri:false,    //定义了是否使用加密的URI
                fileElementId:'fileToUpload',   //指明元素的ID
                dataType: 'json',            //指明数据传输方式为JSON
			  //在传输完成后的事件响应
                success: function (data, status)
                {
                    if(typeof(data.error) != 'undefined')
                    {
                        if(data.error != '')
                        {
                            alert(data.error);
                        }else
                        {
                            alert(data.msg);
                        }
                    }
                },
               //定义出错时的处理
                error: function (data, status, e)
                {
                    alert(e);
                }
            }
        )
        return false;
}
