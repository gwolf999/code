function ajaxFileUpload()
    {
        // ajaxFileUpload()���������ڴ��俪ʼ�ͽ�����ʱ����ʾ������Ԫ�ء���ajaxFileUpload�е�����Ajax����
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
                url:'doajaxfileupload.php', //�����˺�̨�����ļ��ϴ���URL
                secureuri:false,    //�������Ƿ�ʹ�ü��ܵ�URI
                fileElementId:'fileToUpload',   //ָ��Ԫ�ص�ID
                dataType: 'json',            //ָ�����ݴ��䷽ʽΪJSON
			  //�ڴ�����ɺ���¼���Ӧ
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
               //�������ʱ�Ĵ���
                error: function (data, status, e)
                {
                    alert(e);
                }
            }
        )
        return false;
}
