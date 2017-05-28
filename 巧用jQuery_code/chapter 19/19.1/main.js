 $(document).ready(function(){
      //定义当reset按钮被按下的时候的行为，val函数用于定义输入字段的值，这里置为空，说明将uname和upwd两个字段都置为空
            $("#resetbt").click(function(){
                $("#uname").val("");
                $("#upwd").val("");
                });
            $("#loginbt").click(function(){     //用于定义当loginbt按钮被按下时的行为
                $.ajax({
                type: "POST",  //采用HTTP POST方式
                url:"/LoginTest/ajlogin.do", //服务器端所在
               //包含了一些hash table，用key-value的形式来封装数据，这里包含了两组数据，第一组名为user，包含的值为$("#uname").val()，即用户在uname的输入值；第二组名为pwd，包含的值为$("#upwd").val()，即用户在upwd的输入值
                data:{user:$("#uname").val(),pwd:$("#upwd").val()},    
            //定义了两个回调函数，当响应成功的时候，将返回的消息显示在#result中。而当响应失败的时候就提示错误。
                success: function(msg){
                    $("#result").html(msg);
                 },   
                error: function(){
                    alert("error");
                }
                }); 
            }); 
            });     
