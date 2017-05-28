// 找到当前的位置
    function findPos(obj) {
      var curleft = curtop = 0;
      if (obj.offsetParent) {
        curleft = obj.offsetLeft  //当前的位移
        curtop = obj.offsetTop
        while (obj = obj.offsetParent) {
          curleft += obj.offsetLeft //重新计算当前位置
          curtop += obj.offsetTop
        }
      }
      return [curleft,curtop];
    }
     function magicpuff() {
      $("img").mousedown(function() {
        pos = findPos(this)  //获取当前位置
        left_pos = pos[0];
        top_pos = pos[1];
 
        puffer = this.cloneNode(true);
        puffer.style.left = left_pos + "px";  //计算新的位置
        puffer.style.top = top_pos + "px";
        puffer.style.position = "absolute";
        $(document.body).append(puffer);
        $(puffer).Puff(1000, function() { $(puffer).remove() });  //弹出一个半透明的扩大图像
         return false;
      })
    }
    $(document).ready(magicpuff);
