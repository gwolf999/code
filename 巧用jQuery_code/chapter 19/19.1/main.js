 $(document).ready(function(){
      //���嵱reset��ť�����µ�ʱ�����Ϊ��val�������ڶ��������ֶε�ֵ��������Ϊ�գ�˵����uname��upwd�����ֶζ���Ϊ��
            $("#resetbt").click(function(){
                $("#uname").val("");
                $("#upwd").val("");
                });
            $("#loginbt").click(function(){     //���ڶ��嵱loginbt��ť������ʱ����Ϊ
                $.ajax({
                type: "POST",  //����HTTP POST��ʽ
                url:"/LoginTest/ajlogin.do", //������������
               //������һЩhash table����key-value����ʽ����װ���ݣ�����������������ݣ���һ����Ϊuser��������ֵΪ$("#uname").val()�����û���uname������ֵ���ڶ�����Ϊpwd��������ֵΪ$("#upwd").val()�����û���upwd������ֵ
                data:{user:$("#uname").val(),pwd:$("#upwd").val()},    
            //�����������ص�����������Ӧ�ɹ���ʱ�򣬽����ص���Ϣ��ʾ��#result�С�������Ӧʧ�ܵ�ʱ�����ʾ����
                success: function(msg){
                    $("#result").html(msg);
                 },   
                error: function(){
                    alert("error");
                }
                }); 
            }); 
            });     
