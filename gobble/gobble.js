// Variables
var GM = false;
var score = 0;
var raspNum = 0;
var switchedRaspNum = "1"; 

// Randomise raspberry location function
function raspRand() {
	if (raspNum == 0) {
		raspCoords["00"] = Math.floor(Math.random() * 15) * 20;
		raspCoords["01"] = Math.floor(Math.random() * 15) * 20;
	}
	else if (raspNum == 1) {
		raspCoords["10"] = Math.floor(Math.random() * 15) * 20;
		raspCoords["11"] = Math.floor(Math.random() * 15) * 20;
	}
	if ((raspCoords["00"] == GMCoords[0] && raspCoords["01"] == GMCoords[1]) || (raspCoords["10"] == GMCoords[0] && raspCoords["11"] == GMCoords[1])) {
		raspRand();
	}
}

// Coordinates
var GMCoords = new Array();
	GMCoords[0] = 140;
	GMCoords[1] = 140;
var raspCoords = new Array();
	raspCoords["00"] = Math.floor(Math.random() * 15) * 20;
	raspCoords["01"] = Math.floor(Math.random() * 15) * 20;
	raspCoords["10"] = 0;
	raspCoords["11"] = -20;
var strawCoords = new Array();
	strawCoords[0] = 0;
	strawCoords[1] = -20;
var goldenCoords = new Array();
	goldenCoords[0] = 0;
	goldenCoords[1] = -20;

// Help, opacity of gobble montster at the beginning of the game, functions.
function GMOpacity() {
	document.getElementById("gm").style.opacity = 1
}
function switchHelp() {
	var top = document.getElementById('help').style.top;
	if (top == "0px") {
		document.getElementById("help").style.top = -300 + "px";
		document.getElementById("toggle-help").innerHTML = "Show help";
		if (GM == false) {
			window.setTimeout(function(){GMOpacity()},300);
			GM = true;
			document.getElementById("rasp" + raspNum).style.left = raspCoords[raspNum + "0"] + "px";
			document.getElementById("rasp" + raspNum).style.top = raspCoords[raspNum + "1"] + "px";
		}
	}
	else {
		document.getElementById("help").style.top=0;
		document.getElementById("toggle-help").innerHTML = "Hide help";
	}
}
function moveGM(direct) {
	if (direct == "l" && GMCoords[0] > 0) {
		document.getElementById("gm").style.left = GMCoords[0] - 20 + "px";
		GMCoords[0] -= 20; 
	}
	else if (direct == "u" && GMCoords[1] > 0) {
		document.getElementById("gm").style.top = GMCoords[1] - 20 + "px";
		GMCoords[1] -= 20; 
	}
	else if (direct == "r" && GMCoords[0] < 261) {
		document.getElementById("gm").style.left = GMCoords[0] + 20 + "px";
		GMCoords[0] += 20; 
	}
	else if (direct == "d" && GMCoords[1] < 261) {
		document.getElementById("gm").style.top = GMCoords[1] + 20 + "px";
		GMCoords[1] += 20; 
	}
}

// Fud (fʊd; edible items used as source of points) functions
function zeroFud(rasp) {
	document.getElementById("rasp" + rasp).style.left = 0 + "px";
	document.getElementById("rasp" + rasp).style.top = -20 + "px";
}
function switchRaspNum() {
	if (raspNum == 0) {
		raspNum = 1;
	}
	else {
		raspNum = 0;
	}
}
function switchedRaspNum() {
	if (raspNum == 0) {
		switchedRaspNum = 1;
	}
	else {
		switchedRaspNum = 0;
	}
	return switchedRaspNum;
}
function resetShadow() {
	switchedRaspNum = "poo";
	document.getElementById("rasp" + switchedRaspNum).style.boxShadow = "0 0 0 0px rgba(255,0,136,1)";
	document.getElementById("rasp" + switchedRaspNum).style.backgroundColor = "#ff0088";
}
function refreshFud(fud) {
	if (fud == "r") {
		//document.getElementById("rasp" + raspNum).style.left = 0 + "px";
		document.getElementById("rasp" + raspNum).style.backgroundColor = "#141414";
		document.getElementById("rasp" + raspNum).style.boxShadow = "0 0 0 10px rgba(255,0,136,0)";
		setTimeout(function(){resetShadow()},250);
		switchRaspNum();
		//setTimeout(function(){switchRaspNum()},300);
		//setTimeout(function(){zeroFud(switchedRaspNum())},300);
		raspCoords["00"] = 0;
		raspCoords["01"] = -20;
		raspCoords["10"] = 0;
		raspCoords["11"] = -20;
		raspRand();
		document.getElementById("rasp" + raspNum).style.left = raspCoords[raspNum + "0"] + "px";
		document.getElementById("rasp" + raspNum).style.top = raspCoords[raspNum + "1"] + "px";
		document.getElementById("rasp" + raspNum).style.opacity = 1;
		document.getElementById("rasp" + raspNum).style.boxShadow = "0 0 0 0px rgba(255,0,136,1)";
	}
	else if (fud =="s") {
	}
	else if (fud =="g") {
	}
}
function eatFud() {
	if ((GMCoords[0] == raspCoords["00"] && GMCoords[1] == raspCoords["01"]) || (GMCoords[0] == raspCoords["10"] && GMCoords[1] == raspCoords["11"])) {
		score += 1;
		document.getElementById("score").innerHTML = score;
		refreshFud("r");
	}
	else if (GMCoords[0] == strawCoords[0] && GMCoords[1] == strawCoords[1]) {
		score += 2;
		document.getElementById("score").innerHTML = score;
		refreshFud("s");
	}
	else if (GMCoords[0] == goldenCoords[0] && GMCoords[1] == goldenCoords[1]) {
		score += 5;
		document.getElementById("score").innerHTML = score;
		refreshFud("g");
	}
}

// Movement
function moveKey(e) {
    e = e || window.event;
	if (e.keyCode == 37) { // Left
		moveGM("l");
		eatFud();
	} else if (e.keyCode == 38) { // Up
		moveGM("u");
		eatFud();
	} else if (e.keyCode == 39) { // Right
		moveGM("r");
		eatFud();
	} else if (e.keyCode == 40) { // Down
		moveGM("d");
		eatFud();
	}
}
document.onkeydown = moveKey;