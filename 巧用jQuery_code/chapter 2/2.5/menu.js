 var obj = null;
   function checkHover() {
      if (obj) {
         obj.find('ul').fadeOut('fast');
      } 
   } 
   $(document).ready(function() {
   // hover�¼�����껬����ЩliԪ��ʱ������hover�¼��а���������������һ���ǵ��������Ԫ��ʱ���õķ������ڶ���������뿪���Ԫ��ʱ���õķ���
      $('#Nav > li').hover(function() {  //ѡȡ������IDΪNavigator��LIԪ��
         if (obj) {
     //���obj������ڣ���Ԫ���ǿɼ��ģ�����ô�ҵ���UL�ڵ㣬Ȼ�����fadeOut������fadeOut��fadeIn����jQuery���õ�Ч�����������ڶ�̬���غ���ʾҳ��Ԫ�أ����Ĳ���������fast��normal��slow������ָ����ʾ�����ص��ٶ�
            obj.find('ul').fadeOut('fast');  //�����Ԫ��ԭ�ȱ���ʾ������ô������ͽ�������
            obj = null;
         } 
         $(this).find('ul').fadeIn('fast');
      }, function() {  //hover�����ĵڶ�������
         obj = $(this);  //��obj����Ϊ��ǰԪ��
         setTimeout(
            "checkHover()",
           400);
      });
   });
