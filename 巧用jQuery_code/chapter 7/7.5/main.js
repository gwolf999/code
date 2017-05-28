$(document).ready(function() {
	// simple call
    $("#callMe").hrzAccordion();
    //advanced call 
    $("#callMe").hrzAccordion({   //通过调用hrzAccordion方法实现效果
    		containerClass     : "hrzContainer",
			listItemClass      : "listItem",					
			contentStartClass  : "contentStart",
			contentEndClass    : "contentEnd",
			contentWrapper     : "contentWrapper",
			handleClass        : "handle",
			handleClassOver    : "handleOver",
			handleClassSelected: "handleSelected",
			handlePosition     : "left",
			closeEaseAction    : "backin",
			closeEaseSpeed     : 500,
			openEaseAction     : "easein",
			openEaseSpeed      : 500,
			openOnLoad		   : 1
            });
});
