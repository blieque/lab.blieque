// piano, https://blieque.github.io/piano/
// a total shameless, bad clone of piano tiles on iOS and Android
// GPL v3 applies


// variables

var keybind	= [65, 88, 77, 76, 72, 74, 75, 76]	// axml, hjkl (ermahgerd derplercerts!)
	started	= false,
	tiles	= [],
	score	= 0,
	failed	= 0,
	highScore	= localStorage["highScore"] == null ? 0 : localStorage["highScore"];


// functions

function move(column) {

	if (failed) {
		return;
	}

	if (!started) {
		started	= true;
	}

	var rowDiv	= $("[style='top: 360px;']");		// nasty, but workey

	if (tiles[0] == column) {

		// correct
		var animEndCount = 0;

		$(".row").animate({top:"+=120px"},75,function(){

			animEndCount++;

			if (animEndCount == 7) {					// seventh call

				// var top	= $("[style='top:600px;']");
				var top	= $('[style="top: 600px;"]');

				generateRow($(".row").index(top));
				top.css("top","-240px");

			}

		});

		rowDiv.children(".black").addClass("grey");
		rowDiv.children(".black").removeClass("black");

		$(".start").removeClass("start");			// get rid of yellowness

		score += 1 + rand(Math.sqrt(score)) * rand(10);
		comedy();									// it is a game after all

		$("#score").html(score);

	} else {

		fail();

	}

}

function generateRow(index) {

	var cells	= $(".row").eq(index).children(),
		tile	= rand(4);

	cells.removeClass("grey");
	cells.eq(tile).addClass("black");

	tiles.push(tile);
	if (started) {
		tiles.shift();
	};

}

function rand(max) {

	return Math.floor(Math.random() * max);

}

function fail() {

	started	= false;
	failed	= true;

	$("#failscore").addClass("redscore");
	$("#failscore").addClass("redscore");

	setTimeout(function(){
		if (confirm("You failed with a score of " + score + ". Could be worse..\n\nClick OK to refresh.")) {
			window.location.reload();
		}
	},500);

	// save highscore if highscore
	if (score > highScore) {
		highScore = localStorage["highScore"] = score;
	}

	$("#score,#failscore").html(score);					// two birds with a lone stone
	$("#highscore").html(highScore);
	$("#failbox").animate({display:"block",opacity:1},500);

}

function restart() {

	$("#failbox").animate({opacity:0},300,function(){
		css("display", "none")
	});
	$("#failbox").css("display", "none");

	score	= 0;
	$("#score").html(0);
		
}

function init() {

	$("#failbox").css("display", "none");

	for (var i = 5; i >= 0; i--) {

		$(".row").eq(i).css("top",i * 120 - 240);
		generateRow(i);

	};

}

function comedy() {
	
}


// jquery call

$(function(){

	init();

	$("body").on("keydown",function(e){

		if (keybind.indexOf(e.keyCode) >= 0) {		// identify pressed key's value as once listed in keybind[]

			var column	= keybind.indexOf(e.keyCode) % 4;			// modulo converts 4,5,6,7 to 0,1,2,3
			move(column);

		}

	});

	$(".cell").on("click",function(){

		if ($(this).parent().attr("style") == "top: 360px;") {
			move($("[style='top: 360px;'] .cell").index(this));
		};

	})

});
