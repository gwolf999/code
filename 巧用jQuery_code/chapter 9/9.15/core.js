//OS elements  
var main = $("#main");  
var taskbar = $("#taskbar");  
var clock = $("#clock");  
var trash = $("#trash");  
var icons = $(".icon");

//Mouse status
var mouseDiffY = 0;
var mouseDiffX = 0;
var mouseActiveIcon = 0;
var mouseActiveCloneIcon = 0;

//update clock function
function updateClock(){
	var now = new Date();  //��ȡ��ǰʱ��
    //��ȡ��ǰ��Сʱ�����Ӻ�����
    //�����ǰ��Сʱ�����Ӻ�����С��10����ô��ʱ����ֵ֮ǰ���0
	var hour = now.getHours();
	if(hour < 10) hour = "0" + hour;
    var mins = now.getMinutes();
	if(mins < 10) mins = "0" + mins;
    var secs = now.getSeconds();
	if(secs < 10) secs = "0" + secs;
	//��ʱ��������ʾ��ǰʱ��
	clock.html(hour + " : " + mins + " : " + secs);
	//ѭ�����ô˺�����ÿ���ӵ���һ�Σ�����ʱ�ӽ���ÿ���Ӹ���һ��
    setTimeout("updateClock()", 1000);
}


	$(document).bind("contextmenu",function(e){  //ȡ��������ʼ��������Ĳ˵��¼�
		return false;
	});
	//��ҳ����ص�ʱ��Ϊҳ���ϵ�һЩԪ������һЩ��̬Ч��
	trash.css({'top':(main.height()) - (128 + taskbar.height()), 'left':main.width() - 128});
	icons.fadeIn(1500);
	taskbar.slideDown();
	//��������ʱ��
	updateClock();


//��ÿһ������Ԫ�ض������˵���¼���Ӧ	
icons.mousedown(function(e){
              //�����ж��Ƿ�Ϊ������
			if(e.button <= 1){
              //�������λ��
			mouseDiffY = e.pageY - this.offsetTop;
			mouseDiffX = e.pageX - this.offsetLeft;
              //����û��ڵ��֮��û�з�����꣬��ô����һֱ����һ����ǰ����ͼ��ĸ��� 
			if(mouseActiveIcon !=0){
				mouseActiveIcon.removeClass("active");
			}
			mouseActiveIcon = $(this);
			mouseActiveCloneIcon = mouseActiveIcon.clone(false).insertBefore(mouseActiveIcon);
		}
	});
	//���ݵ�ǰ�����ƶ�λ����ʾ�������
	$(document).mousemove(function(e){
		if(mouseActiveIcon){
			//����δ֪
			mouseActiveIcon.css({"top":e.pageY - mouseDiffY, "left":e.pageX - mouseDiffX, "opacity":0.35});
			var restaY = e.pageY - $(this).css("top");
			var restaX = e.pageX - $(this).css("left");
		}
	});
	//�ſ����
	$(document).mouseup(function(){
		if(mouseActiveIcon != 0){
			mouseActiveIcon.css({"opacity":1.0});
			mouseActiveIcon = 0;
			mouseActiveCloneIcon.remove();
			mouseActiveCloneIcon = 0;
		}
	});

/mouse double click
	icons.dblclick(function(){
		alert(this.id);
	});
//custom context menu on right click
main.mousedown(function(e){
		if(e.button == 2){
			alert("context menu");
		}
	});

