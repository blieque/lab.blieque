/*
	TIP: Remove .min from URL for non-minified version.

	Hextring, by Blieque Mariguan

	JavaScript (with jQuery) to fetch a string from an input box, and convert it
	to a valid six-digit hexadecimal colour code.

	I encourage you to use it, modify it, re-distribute it, learn from it, play
	with it or do else as you please with it.
*/

function c() { // Main function; "convert"

	/* Get string, and its length */
	s = $('[name="string"]').val(); // String
	l = s.length // String length

	/* Step 1: replace all non-hex characters with zeros. */
	s = s.split("");
	for (var i = 0;i<l;i++) {
		if (/[a-f0-9]/i.test(s[i]) == 0) {
			s[i] = 0;
		}
	}
	s = s.join("");

	/* Step 2: if the number of characters is not devisible by three, append
	   zeros so that it is. */
	if ((l + 1) % 3 == 0) {
		s += "0";
	} else if ((l + 2) % 3 == 0) {
		s += "00";
	}
	l = s.length;

	/* Convert 3 character codes to six characters ones. */
	if (l == 3) {
		s = s.replace(/([a-f0-9])/ig,"$1$1");
	}

	/* Step 3: Split the string into three equally sized sub-strings, taking
	   the first two characters of each. If it's six characters long already,
	   return the string as it is. */
	sl = s.length / 3;
	if (l > 3) {
		ss = [ // Sub-strings
			s.substring(0,2),
			s.substring(sl,sl + 2),
			s.substring(sl * 2,sl * 2 + 2)
		];
	} else {
		return s;
	}

	return ss.join(""); // Our final output
}

/* Thanks to Dave and Pavlo from Stack Overflow (for this function):
   http://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb */
function h(h) { // Hex to RGB
	bi = parseInt(h, 16);
	return [
		(bi >> 16) & 255,
		(bi >> 8) & 255,
		bi & 255
	];
}

function u() { // Update
	v = c($("#string").val());
	$(".classy").css("background-color","#" + v).html(v);

	/* Merge the three substrings to create a six-character colour code. */
	rgb = h(v);
	if (rgb[0] < 128 && rgb[1] < 128 && rgb[2] < 128) {
		$(".classy").css("color","rgba(255,255,255,.4)");
	} else {
		$(".classy").css("color","rgba(0,0,0,.2)");
	}
}

$(function(){
	u();

	$(".button").click(function(){
		u();
	});

	$(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			u();
			return false;
		}
	});
});