$( document ).ready(function() {

	// Set carousel options
	$('.carousel').carousel({
	  interval: 8000 // 8 seconds vs. default 5
	});

	//Enable swiping...
	$(".carousel-inner").swipe( {
		//Generic swipe handler for all directions
		swipeRight:function(event, direction, distance, duration, fingerCount) {
			$(this).parent().carousel('prev');
		},
		swipeLeft: function() {
			$(this).parent().carousel('next');
		},
		//Default is 75px, set to 0 so any distance triggers swipe
		threshold:0
	});

});