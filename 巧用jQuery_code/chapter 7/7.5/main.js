$(document).ready(function() {
	// simple call
    $("#callMe").hrzAccordion();
    //advanced call 
    $("#callMe").hrzAccordion({   //ͨ������hrzAccordion����ʵ��Ч��
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
