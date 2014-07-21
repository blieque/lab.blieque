$(function(){
	$("form").on("submit",function(event){
		// Prevent forms actually submitting.
		event.preventDefault();
	});

	// Value deletion
	$("li div").on("click",function(){
		$(this).parents("li").slideUp(400,function(){
			console.log("Hai");
			$(this).remove()
		});
	});
});
$(document).keydown(function(e){
	switch (e.keyCode) {
		case 82:
			console.log("r key");
			break;
		case 82:
			console.log("r key");
			break;
		case 82:
			console.log("r key");
			break;
		case 82:
			console.log("r key");
			break;
		case 82:
			console.log("r key");
			break;
	}
});
// r: 82, space: 32, enter: 13, a: 65, s: 83,