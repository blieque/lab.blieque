var WheelShift = 0;
var Ticker = 0;

function changeShiftSub()
{
	if(WheelShift>0 && WheelShift<27)
	{
		WheelShift -= 1;
		document.getElementById("current").innerHTML=WheelShift;
	}
	else if(WheelShift==0)
	{
		WheelShift = 25;
		document.getElementById("current").innerHTML=WheelShift;
	}
	Ticker += 16;
	document.getElementById("ticker").style.backgroundPosition=Ticker+"px"+" 0"
}
function changeShiftAdd()
{
	if(WheelShift>=0 && WheelShift<25)
	{
		WheelShift += 1;
		document.getElementById("current").innerHTML=WheelShift;
	}
	else if(WheelShift==25)
	{
		WheelShift = 0;
		document.getElementById("current").innerHTML=WheelShift;
	}
	Ticker -= 16;
	document.getElementById("ticker").style.backgroundPosition=Ticker+"px"+" 0"
}
function encryptIt(Uppertext)
{	
		var letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
//		Uppertext = document.form1.plaintext.value
		var cipher = ""
		for (var i = 0; i < Uppertext.length; i ++) {
			var chart = Uppertext.charAt(i)
			var cipherindex = letters.indexOf(chart)
			if( cipherindex != -1) {
				cipherindex += WheelShift
				cipherindex = cipherindex % 26
				cipher += letters.charAt(cipherindex)
			}
			else cipher += chart
		}
//		document.form1.ciphertext.value = cipher
		return cipher
}

function checkEncryption()
{
	var plain = document.form1.plaintext.value
	var cipher = clean(encryptIt (plain))
	var answer = document.form1.ciphertext.value
	answer = clean(answer)
	cipher = encryptIt(plain)
	document.form1.ciphertext.value = cipher
}

function checkDecryption()
{
	document.form1.ciphertext.value=document.form1.ciphertext.value;
	var cipher = document.form1.ciphertext.value.toLowerCase()
	var plain = clean(decryptIt (cipher))
	var answer = document.form1.plaintext.value.toLowerCase()
		answer = clean(answer)		
//document.form1.ciphertext.value = plain
		if (plain == answer)
		{
//			document.CW.src = "caesarwheel/cw" + WheelShift + ".gif"
			cipher = document.form1.ciphertext.value.toLowerCase()
			plain = decryptIt(cipher)
			document.form1.plaintext.value = plain
		}
}

function decryptIt(Lowertext)
{	var letters = "abcdefghijklmnopqrstuvwxyz"
//	var Lowertext = document.form1.plaintext.value.toLowerCase()
	var decipher = ""
	for (var i = 0; i < Lowertext.length; i ++) {
		var chart = Lowertext.charAt(i)
		var decipherindex = letters.indexOf(chart)
		if( decipherindex != -1) {
			decipherindex += (26 - WheelShift)
			decipherindex = decipherindex % 26
			decipher += letters.charAt(decipherindex)
		}
		else decipher += chart
	}
//	document.form1.ciphertext.value = Lowertext
	return decipher
}

//This function cleans off all non-alpha characters in a string and return the substring(0,3) of it
function clean(txt) {
	var alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
	var result = ''
	
	for (var i = 0; i < txt.length; i++)
	{
		var thischar = txt.charAt(i)
		var pos = alpha.indexOf(thischar)
		if (pos != -1)
			result += thischar;
	}
	
	var first3 = '';
	if (result.length > 3)  first3 = result.substr(0,3);
	else 	first3 = result;

	return  first3;
}