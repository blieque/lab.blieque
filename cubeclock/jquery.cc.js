// temp
var bC	= {firstTime:true,time:null,list:[],go:function(){
	if (bC.firstTime) {
		bC.firstTime = false;
		return "basic clock function. call again to start, and once more to stop. can be repeated. scores logged to bC.list[].";
	} else {
		if (bC.time == null) {
			bC.time = Date.now();
			return "started";
		} else {
			var time = (Date.now() - bC.time) / 1000;
			bC.list.push(time);
			bC.time = null;
			return time + " seconds";
		}
	}
}};

// cubeclock
// creation of @blieque's

// variable declaration
var moves = [["F","F&prime;","F2"],["R","R&prime;","R2"],["U","U&prime;","U2"],["B","B&prime;","B2"],["L","L&prime;","L2"],["D","D&prime;","D2"]],	// ordering is important for good scrambles
	algs = ["F2 B2 L2 R2 U2 D2","U R2 F B R B2 R U2 L B2 R U&prime; D&prime; R2 F R&prime; L B2 U2 F2"], // algorithms for the alg. lookup tool
	avg			= {iP:0,run:0,runCount:0,times:[]},	// averages management variable
	bingSound	= new Audio("b.ogg"),				// sound file for binging
	timer		= [],								// clock
	mode		= 0,								// record mode; 0 = single, 1 = average
	inpForbid	= 0,								// used in keyboard and mouse binding
	inpLast		= 0,								// track last input (up or down)
	resetReady	= 0,								// preventing accidental reset after stop
	timerState	= 0,								// track timer's state
	fiftyOpen	= 0,								// keep track of how many records to show
	cubeSize	= 3,								// which size cube to track, 3x3 default
	records,										// holds previous times in milliseconds
	timerUpdateInterval,							// interval container for timer updater
	uA = navigator.userAgent;
if ((uA.indexOf('Safari') > -1 && uA.indexOf("Chrome") < 0) || // if is safari
	uA.indexOf("MSIE") > -1) {						// if user is a liability; determined by html conditional
	bingSound	= new Audio("b.mp3");				// alternative sound file for binging
}

// functions, then voids
String.prototype.repeat = function(n) {			// from Peter Bailey (8815) on StackOverflow
    return new Array(n + 1).join(this);
}
function rand(l) {								// limit
	return Math.floor(Math.random() * l);			// easier random integer generation
}
function millisecToMixed(m) {
	var min = Math.floor(m / 60000),
		sec = (m / 1000 - min * 60).toFixed(2);
	if (min < 10) {
		min = "0" + min;
	}
	if (sec < 10) {
		sec = "0" + sec;
	}
	return min + ":" + sec;
}
function recordsArr() {
	var index = "c" + cubeSize + "";
	return records[index];
}

function clockCtl() {
	if (!timerState) {							// (== 0) if timer stopped and reset
		$("span.btn").html("Stop");					// change button text
		resetReady = 00;									// for delaying reset during stop

		timer[0] = Date.now();							// set base time
		timerUpdateInterval = setInterval(function(){ 	// interval for updating the clock visually
			timer[1] = Date.now() - timer[0];				// update timer variable with latest time
			$("#t").html(millisecToMixed(timer[1]));		// update second counter element
		},40);											// update delay

		timerState = 1;									// update timer state
	} else if (timerState == 1) { 						// if timer not running
		clearInterval(timerUpdateInterval);				// clear update interval
		timer[1] = Date.now() - timer[0];				// update timer variable with latest time

		$("#t").html(millisecToMixed(timer[1]));		// update second counter element
		updateRecords(timer[1]);						// send time to records

		$("span.btn").html("Reset");					// change button text
		scramble();

		setTimeout(function(){							// wait one second to prevent accidental resets
			resetReady = 1;
		},1000);

		timerState = 2;									// update timer state
	} else {										// if timer stopped and reset
		if (resetReady) {
			$("span.btn").html("Start");					// change button text
			timer[0] = timer[1] = null;						// set start and end times to 0 (reset)
			$("#t").html("00:00.00");						// reset time visually
			
			timerState = 0;									// update timer state
		}
	}
}
function avgCtl(m) {							// milliseconds
	if (avg.iP) {
	}
}
function algCtl(a) {							// alg
	$("#a").html(algs[a]);
}
function bing() {
	if ($("[name='cd-as']")[0].checked) {
		bingSound.play();
	}
}
function updateRecords(n) { 					// new (potential) record
	if (n == "c") {
		localStorage.removeItem("records");			// remove localStorage entry. does not err if entry does not exist
		records = { 								// generate new records variable
			c2:[[],[]],
			c3:[[],[]],
			c4:[[],[]]
		};
	} else {
		if (avg.iP) {									// if average set inProgress
			avgCtl(n);										// pass time to averages function
		} else {
			recordsArr()[mode].push(n);						// add new time to records array
			recordsArr()[mode].sort(function(a,b){return (a-b)});	// sort the records array
			recordsArr()[mode] = recordsArr()[mode].slice(0,50);	// truncate array to fifty elements
			recordTable();
		}
	}
}
function recordTable(a) {						// all? (both singles and averages)
	var c = fiftyOpen ? 50 : 10,					// count
		m = a ? (mode ? 0 : 1) : mode;				// modded mode if both columns requested
	for (var i = 0; i < c; i++) {					// 10- or 50-time loop for each row
		recordTableHtml(i,m);							// update cell value
	}
	if (a) {										// if all requested
		recordTable();									// run again for the other column
	}
}
function recordTableHtml(i,m) {					// index, all?
	if (recordsArr()[m][i] != null) {			// if times have been recorded
		var fol = !m ? "fir" : "la";				// first-child if singles, last-child if averages
		$("#r>tbody tr").eq(i).children("td:" + fol + "st-child").html(millisecToMixed(recordsArr()[m][i]));
	}
}
function scramble() {
	var p = [rand(3)],
		r = [],
		scrambleSeq = [],
		widenCount = 0;								// counts X2 and X' moves
	for (var i = 0; i < 25; i++) {					// iterate nineteen times
		p[3] = p[0];									// temporary copy before modulo
		p[0] %= 3;										// modulo previous result
		r = [rand(3),[0,0,1,2][rand(4)]];				// new random numbers. r[1] is biased to 0
		
		if (r[1] == 2) {								// for ensuring the sequence fits visually
			widenCount++;
		} else if (r[1] == 1) {
			widenCount += .3;
		}

		var a = [
			[0,1,2,3,4,5],									// all sides array
			[]												// allowed sides array
		];
		for (var j = 0; j < 6; j++) {					// constructs array of allowed sides
			if (a[0][j] != p[0] &&
				a[0][j] != p[0] + 3 &&
				a[0][j] != p[1]) {
				a[1].push(a[0][j]);
			}
		}
		r[0] = a[1][rand(3)];							// pick a random side

		p[0] = r[0],								// update 'previous' vars
		p[1] = p[3];

		scrambleSeq.push(moves[r[0]][r[1]]);		// push result to list, with array driven witchcraft
	}
	if (widenCount > 8) {								// if more than twelve 180 deg. turns:
		$("#s span").css("font-size","13pt");			// shrink to fourteen points
	} else {										// otherwise
		$("#s span").css("font-size","14pt");			// keep at/grow to sixteen points
	}
	$("#s span").html(								// update text on page
		JSON.stringify(scrambleSeq).replace(/[\[\]\"]/g,"").replace(/,/g," ")
	);
}
function inpHandler(e) {						// event
	if (											// dis confuze, k
		(
			e.type == "mousedown" &&						// if keydown/mousedown
			timerState > 0									// and timer running/stopped
		) || (											// or
			e.type == "mouseup" &&							// if keyup/mouseup...
			!timerState &&									// and timer ready
			!inpForbid										// and not forbidden
		)
	) {
		clockCtl();										// start/stop/reset clock
	} else if (inpForbid) {							// otherwise, if up events forbidden
		inpForbid = 0;									// allow them next time
	}
}
function init() {								// initalisation
	scramble();										// generate first scramble sequence
	try {
		records = JSON.parse(localStorage["records"]);	// parse records from previous session
	} catch(e) {
		updateRecords("c");								// generate new records variable
	}
	recordTable(1);									// load *all* records to list
}

// jquery call (technically onload)
$(function(){
	init();											// some initiation things

	new ncd($("#b")[0]);							// disable 300ms click delay (mobile webkit), uses ../js/fc.min.js

	// bindings
	$(window).on("unload",function(){					// browser/page close or refresh
		localStorage["records"] = JSON.stringify(records); // convert array to string for storage
	});
	$("#as").on("change",function(){
		algCtl(this.value);
	});

	$("#b").on("mousedown",function(e){
		if (!inpLast){
			if (timerState == 2) {
				inpForbid = 1;
			}
			inpHandler(e);
		}
		inpLast = 1;
	}).on("mouseup",function(e){
		if (inpLast) {
			inpHandler(e);
		}
		inpLast = 0;
	});
	$("body").on("keydown",function(e){
		if (e.keyCode == 32 || e.keyCode == 13) {		// identify pressed key as spacebar or return
			$("#b").trigger("mousedown");
			return false;									// prevent scrolling with space
		}
	}).on("keyup",function(e){
		if (e.keyCode == 32 || e.keyCode == 13) {		// identify pressed key as spacebar or return
			$("#b").trigger("mouseup");
			return false;									// prevent scrolling with space
		}
	});

	$("h3+ul li").on("click",function(){			// cube size tab click
		var lis		= $("h3+ul li");					// array of li elements
			index	= lis.index($(this));				// index of the clicked li in array
		cubeSize = index + 2;							// updates cubesize variable, by adding two to the index (0 --> 2)
		lis.filter(".tb-sel").removeClass("tb-sel");	// deselect old tab
		lis.eq(index).addClass("tb-sel");				// select new tab
		$("#r tbody td").html("-");							// clean record table (html)
		recordTable(1);									// load corresponding records
	});
	$("#s").on("click",function(){
		scramble();										// scramble when new sequence requested
	});
	$("#c1").on("click",function(){
		$("#c1+span").css("display","inline-block").animate({opacity:1});
		setTimeout(function(){
			$("#c1+span").animate({opacity:0},1500,function(){
				$("#c1+span").css("display","none");
			});
		},3000);
	});
	$("#c2").on("click",function(){
		$("#c1+span").animate({opacity:0},250,function(){
			$("#c1+span").css("display","none");
		});
	});
	$("#c3").on("click",function(){
		updateRecords("c");
		$("#c1+span").animate({opacity:0},250,function(){
			$("#c1+span").css("display","none");
		});
	});
});
