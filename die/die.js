function rand() {
	console.log("randed");
	document.getElementById("r").innerHTML=Math.floor((Math.random()*6)+1);
}

function disableSelection(target) {
	if (typeof target.onselectstart!="undefined")
	target.onselectstart=function(){return false}
	else if (typeof target.style.MozUserSelect!="undefined")
		target.style.MozUserSelect="none"
	else
		target.onmousedown=function(){return false}
	target.style.cursor = "default"
}