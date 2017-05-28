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
	var now = new Date();  //获取当前时间
    //获取当前的小时、分钟和秒数
    //如果当前的小时、分钟和秒数小于10，那么在时间数值之前添加0
	var hour = now.getHours();
	if(hour < 10) hour = "0" + hour;
    var mins = now.getMinutes();
	if(mins < 10) mins = "0" + mins;
    var secs = now.getSeconds();
	if(secs < 10) secs = "0" + secs;
	//在时钟区域显示当前时间
	clock.html(hour + " : " + mins + " : " + secs);
	//循环调用此函数，每秒钟调用一次，所以时钟将会每秒钟更新一次
    setTimeout("updateClock()", 1000);
}


	$(document).bind("contextmenu",function(e){  //取消掉鼠标邮件的上下文菜单事件
		return false;
	});
	//在页面加载的时候，为页面上的一些元素增加一些动态效果
	trash.css({'top':(main.height()) - (128 + taskbar.height()), 'left':main.width() - 128});
	icons.fadeIn(1500);
	taskbar.slideDown();
	//更新桌面时钟
	updateClock();


//对每一个桌面元素都定义了点击事件响应	
icons.mousedown(function(e){
              //用于判断是否为左键点击
			if(e.button <= 1){
              //计算鼠标位移
			mouseDiffY = e.pageY - this.offsetTop;
			mouseDiffX = e.pageX - this.offsetLeft;
              //如果用户在点击之后没有放松鼠标，那么将会一直保存一个当前桌面图标的副本 
			if(mouseActiveIcon !=0){
				mouseActiveIcon.removeClass("active");
			}
			mouseActiveIcon = $(this);
			mouseActiveCloneIcon = mouseActiveIcon.clone(false).insertBefore(mouseActiveIcon);
		}
	});
	//根据当前鼠标的移动位置显示这个副本
	$(document).mousemove(function(e){
		if(mouseActiveIcon){
			//更新未知
			mouseActiveIcon.css({"top":e.pageY - mouseDiffY, "left":e.pageX - mouseDiffX, "opacity":0.35});
			var restaY = e.pageY - $(this).css("top");
			var restaX = e.pageX - $(this).css("left");
		}
	});
	//放开鼠标
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

