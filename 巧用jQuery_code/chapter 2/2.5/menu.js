 var obj = null;
   function checkHover() {
      if (obj) {
         obj.find('ul').fadeOut('fast');
      } 
   } 
   $(document).ready(function() {
   // hover事件在鼠标滑过这些li元素时发生。hover事件中包含两个参数，第一个是当鼠标进入此元素时调用的方法，第二个是鼠标离开这个元素时调用的方法
      $('#Nav > li').hover(function() {  //选取了所有ID为Navigator的LI元素
         if (obj) {
     //如果obj对象存在（即元素是可见的），那么找到子UL节点，然后调用fadeOut方法，fadeOut和fadeIn都是jQuery内置的效果方法，用于动态隐藏和显示页面元素，它的参数可以是fast、normal或slow，用来指定显示或隐藏的速度
            obj.find('ul').fadeOut('fast');  //如果此元素原先被显示过，那么在这里就将其隐藏
            obj = null;
         } 
         $(this).find('ul').fadeIn('fast');
      }, function() {  //hover方法的第二个参数
         obj = $(this);  //将obj设置为当前元素
         setTimeout(
            "checkHover()",
           400);
      });
   });
