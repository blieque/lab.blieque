// Shift, a JavaScript (+jQuery) tile sliding puzzle.

function set() {
	var coord = [[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0]],
		bootCoord = [[0,0],[51,0],[102,0],[153,0],[0,51],[51,51],[102,51],[153,51],[0,102],[51,102],[102,102],[153,102],[0,153],[51,153],[102,153],[153,153]],
		untaken = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15],
		timerStarted = 0,
		timerCount = 0,
		timer,
		moves = 0;
}
set();

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
		console.log("bootCoord "+i+": "+bootCoord[i]);
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
	if (coord[0] == bootCoord[0]) {
		var timerCountMin = 0;
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

$(function(){
	// Randomisation on load
	
	for (var i=1;i<16;i++) {
		var placingIndex = Math.floor(Math.random()*untaken.length);
		var shiftTo = bootCoord[untaken[placingIndex]];
		coord[i-1] = shiftTo;
		tile(i).css({"left":shiftTo[0],"top":shiftTo[1]});
		untaken.splice(placingIndex,1);
	}
	coord[15] = bootCoord[untaken];

	// Moving of tiles

	tile(0).click(function(){
		if (timerStarted == 0) {
			timer = setInterval(function(){
				timerCount += 1;
			},1000);
			timerStarted = 1;
		}
		var tileIndex = tile(0).index(this);
		if ((coord[tileIndex][0] == coord[15][0]) && (coord[tileIndex][1] == coord[15][1]+51)) {
			move(tileIndex,0);
		} else if ((coord[tileIndex][1] == coord[15][1]) && (coord[tileIndex][0] == coord[15][0]-51)) {
			move(tileIndex,1);
		} else if ((coord[tileIndex][0] == coord[15][0]) && (coord[tileIndex][1] == coord[15][1]-51)) {
			move(tileIndex,2);
		} else if ((coord[tileIndex][1] == coord[15][1]) && (coord[tileIndex][0] == coord[15][0]+51)) {
			move(tileIndex,3);
		}
	});
});