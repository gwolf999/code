// �ҵ���ǰ��λ��
    function findPos(obj) {
      var curleft = curtop = 0;
      if (obj.offsetParent) {
        curleft = obj.offsetLeft  //��ǰ��λ��
        curtop = obj.offsetTop
        while (obj = obj.offsetParent) {
          curleft += obj.offsetLeft //���¼��㵱ǰλ��
          curtop += obj.offsetTop
        }
      }
      return [curleft,curtop];
    }
     function magicpuff() {
      $("img").mousedown(function() {
        pos = findPos(this)  //��ȡ��ǰλ��
        left_pos = pos[0];
        top_pos = pos[1];
 
        puffer = this.cloneNode(true);
        puffer.style.left = left_pos + "px";  //�����µ�λ��
        puffer.style.top = top_pos + "px";
        puffer.style.position = "absolute";
        $(document.body).append(puffer);
        $(puffer).Puff(1000, function() { $(puffer).remove() });  //����һ����͸��������ͼ��
         return false;
      })
    }
    $(document).ready(magicpuff);
