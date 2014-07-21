var slide = 0;
var slides = false;
function slidy(direction) {
	if (direction == "r" && slide < slides) {
		slide += 1;
		$('div#container div:nth-child(' + (slide - 1) + ')').animate({left:'-1016px'},500);
		$('div#container div:nth-child(' + slide + ')').animate({left:'0px'},500);
	}
	else if (direction == "l" && slide > 1) {
		slide -= 1;
		$('div#container div:nth-child(' + (slide + 1) + ')').animate({left:'1016px'},500);
		$('div#container div:nth-child(' + slide + ')').animate({left:'0px'},500);
	}
}
$(document).ready(function(e) {
	if (slides == false) {
		slides = $('div#container div').length;
	}
	$('.nav.r').click(function() {
		slidy("r");
	})
	$('.nav.l').click(function() {
		slidy("l");
	})
	slidy("r");
});
$(document).keydown(function(e){
    if (e.keyCode == 37) {
		slidy("l");
    }
    else if (e.keyCode == 39 || e.keyCode == 32) {
		slidy("r");
    }
});