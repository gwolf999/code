$(function()
{
   //���û����һ��idΪimageOption��aԪ��ʱ
    $("#imageOptions a").click(function()
        {
	  //�ҵ����е�img����Ԫ�أ����һ�ȡ��src���ԣ�������԰�����ͼ���λ��
        var imageSource = $(this).children("img").attr("src");
       //�ҵ�idΪloader��Ԫ�أ�����������CSS��Ϊloading��
        $("#loader").addClass("loading");
	  //��ʽ������һ��������һ����̬gif�����û�����loading
        $("h3").remove();
      // showImage���Լ���д��һ��������������ʾѡ�е�ͼƬ����������Ĳ���Ϊѡ��ͼƬ��URL
          showImage(imageSource);
          return false;
        });
});
function showImage(src)
{
//�����а�װloader��ͼ��Ԫ�أ����Ƚ����ǵ�����Ȼ��ȥ��
$("#loader img").fadeOut("slow").remove();
//Ȼ���ȡ������ͼ��Ԫ��
var largeImage = new Image();
//���ô�ͼ���ͼ��Ϊ�����ͼ��URL
$(largeImage).attr("src", src)
             .load(function()  //��̬����ͼ��
             	{
			//ʵ�ֶ�̬����Ч�������Ƚ�ͼƬ��ȥ
                $(largeImage).hide();
               //ȥ��loader��gif��Ȼ��largeImage���뵽loaderԪ����
                $("#loader").removeClass("loading").append(largeImage);
                //��fadeIn����ƽ���ؽ�ͼƬ������ʾ����
                $(largeImage).fadeIn("slow");                      
                });                                                                       
}
