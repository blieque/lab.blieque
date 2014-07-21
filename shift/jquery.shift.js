// Shift, a JavaScript (+jQuery) tile sliding puzzle.
var coord,doneCoord,untaken,timerStarted,timerCount,timerCountMin,moves,timer,lastDir=0,invDir=[2,3,0,1];

function set() {
	coord = [[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0]];
	doneCoord = [[0,0],[51,0],[102,0],[153,0],[0,51],[51,51],[102,51],[153,51],[0,102],[51,102],[102,102],[153,102],[0,153],[51,153],[102,153],[153,153]];
	timerStarted = timerCount = timerCountMin = moves = 0;
}
set();

function rand(l) {
	return Math.floor(Math.random()*l)
}

function tile(child) {
	if (child!==0) {
		return $("div>div>div:nth-child("+child+")");
	} else {
		return $("div>div>div");
	}
}

function testBlurt() {
	for (var i=1;i<16;i++) {
		console.log("coord "+i+": "+coord[i]);
		console.log("doneCoord "+i+": "+doneCoord[i]);
	}
}

function move(tileNo,dir) {
	moves += 1;
	var swap = coord[tileNo];
	coord[tileNo] = coord[15];
	coord[15] = swap;
	tileNo += 1;
	switch (dir) {
		case 0: // Up
			tile(tileNo).css("top","-=51");
			break;
		case 1: // Right
			tile(tileNo).css("left","+=51");
			break;
		case 2: // Down
			tile(tileNo).css("top","+=51");
			break;
		case 3: // Left
			tile(tileNo).css("left","-=51");
			break;
	}
	if (coord[0] == doneCoord[0] &&
		coord[1] == doneCoord[1] &&
		coord[2] == doneCoord[2] &&
		coord[3] == doneCoord[3] &&
		coord[4] == doneCoord[4] &&
		coord[5] == doneCoord[5] &&
		coord[6] == doneCoord[6] &&
		coord[7] == doneCoord[7] &&
		coord[8] == doneCoord[8] &&
		coord[9] == doneCoord[9] &&
		coord[10] == doneCoord[10] &&
		coord[11] == doneCoord[11] &&
		coord[12] == doneCoord[12] &&
		coord[13] == doneCoord[13] &&
		coord[14] == doneCoord[14]) {
		alert("Done!");
		timerCountMin = 0;
		clearInterval(timer);
		while (timerCount>60) {
			timerCount -= 60;
			timerCountMin += 1;
		}
		if (timerCountMin>1) {
			alert("Congratulations! You're a nerd!\n Also, you took "+timerCountMin+":"+timerCount);
		} else {
			alert("Congratulations! You're a nerd!\n Also, you took "+timerCount+" seconds.");
		}
	}
}

function isValidTile(dir) {
	switch (dir) {
		case 0: // Up
			return $(this).css('left') == coord[15][0]+"px" && $(this).css('top') == coord[15][1]+15+"px";
			break;
		case 1: // Right
			return $(this).css('left') == coord[15][0]-51+"px" && $(this).css('top') == coord[15][1]+"px";
			break;
		case 2: // Down
			return $(this).css('left') == coord[15][0]+"px" && $(this).css('top') == coord[15][1]-51+"px";
			break;
		case 3: // Left
			return $(this).css('left') == coord[15][0]+51+"px" && $(this).css('top') == coord[15][1]+"px";
			break;
	}
}

function randMove() {
	var dir = rand(4);
	while ((dir == invDir[lastDir]) ||
			(dir == 0 && coord[15][1] == 152) ||
			(dir == 1 && coord[15][0] == 0) ||
			(dir == 2 && coord[15][1] == 0) ||
			(dir == 3 && coord[15][0] == 152)) {
		dir = rand(4);
	}
	lastDir = dir;
	switch (dir) {
		case 0: // Up
			tile(0).filter(function(){
				return $(this).css('left') == coord[15][0]+"px" && $(this).css('top') == coord[15][1]+51+"px";
			}).trigger("click");
			break;
		case 1: // Right
			tile(0).filter(function(){
				return $(this).css('left') == coord[15][0]-51+"px" && $(this).css('top') == coord[15][1]+"px";
			}).trigger("click");
			break;
		case 2: // Down
			tile(0).filter(function(){
				return $(this).css('left') == coord[15][0]+"px" && $(this).css('top') == coord[15][1]-51+"px";
			}).trigger("click");
			break;
		case 3: // Left
			tile(0).filter(function(){
				return $(this).css('left') == coord[15][0]+51+"px" && $(this).css('top') == coord[15][1]+"px";
			}).trigger("click");
			break;
	}
}

function boot() {
	console.log("Booting");
	coord = [[51,0],[102,51],[0,51],[102,0],[102,153],[0,0],[0,102],[102,102],[153,102],[153,153],[153,0],[51,51],[153,51],[0,153],[51,153],[51,102]];
	for (var i=0;i<16;i++) {
		tile(i+1).css({"left":coord[i][0],"top":coord[i][1]});
	}
}

function shuffle() {
	// (Valid) tile randomisation
	for (var i=1;i<50;i++) {
		randMove();
	}
}

function shuffleOld() {
	// Tile randomisation
	untaken = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
	for (var i=1;i<16;i++) {
		var placingIndex = rand(untaken.length);
		var shiftTo = doneCoord[untaken[placingIndex]];
		coord[i-1] = shiftTo;
		tile(i).css({"left":shiftTo[0],"top":shiftTo[1]});
		untaken.splice(placingIndex,1);
	}
	coord[15] = doneCoord[untaken];
}

function reset(timer,moves) {
	highscore.push(timer)
	timerStarted = timerCount = 0;
	shuffle();
}

$(function(){
	boot();
	shuffle();

	// Moving of tiles
	tile(0).click(function(){
		if (timerStarted == 0) {
			timer = setInterval(function(){
				while (timerCount>60) {
					timerCount -= 60;
					timerCountMin += 1;
				}
				timerCountMin += 1;
			},1000);
			timerStarted = 1;
		}
		var tileIndex = tile(0).index(this);
		
		var blank = coord[15];
		if ((coord[tileIndex][0] == blank[0]) && (coord[tileIndex][1] == blank[1]+51)) {
			move(tileIndex,0);
		} else if ((coord[tileIndex][1] == blank[1]) && (coord[tileIndex][0] == blank[0]-51)) {
			move(tileIndex,1);
		} else if ((coord[tileIndex][0] == blank[0]) && (coord[tileIndex][1] == blank[1]-51)) {
			move(tileIndex,2);
		} else if ((coord[tileIndex][1] == blank[1]) && (coord[tileIndex][0] == blank[0]+51)) {
			move(tileIndex,3);
		}
	});
	$("span#reset").click(function(){
		reset()
	});
});