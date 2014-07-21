<?php define('included',1);include'../_cgi-bin/builder.php';lab_open("Gobble",300,1,3,"gobble","A simple game in which the player controls a small square. The objective is to gain points at a faster rate than they deplete. The points drain more and more quickly, but so do the opportunites to regain them.") ?>

<div id="board">
	<p id="help" onClick="switchHelp()" style="top:0px">
		<strong id="title">Help</strong><br /><br />
		Use the up, down, left and right arrow keys to move <span id="gobble">Gobble monster</span>.<br />
		Collect the <span id="pink">pink</span> raspberries to gain points.<br />
		Collect any <span id="red">red</span> strawberries to gain two points.<br />
		If you're lucky, collect <span id="gold">golden</span> apples to gain five points.<br /><br />
		<strong>Click to dismiss.</strong>
		<span id="ver">gobble v.0.1<br /><span>created by blieque, distribute and modify as you wish</span></span>
	</p>
	<div id="game"><!-- This is a div to contain all actual game components, to make positioning easier and make them show behind the help panel. -->
		<div class="entity" id="rasp0"><!-- This is the main game character. --></div>
		<div class="entity" id="rasp1"><!-- This is the main game character. --></div>
		<div class="entity" id="straw"><!-- This is the main game character. --></div>
		<div class="entity" id="golden"><!-- This is the main game character. --></div>
		<div class="entity" id="gm" style="opacity:0"><!-- This is the main game character. --></div>
	</div>
</div>
<footer>
	<div class="stat">
		Score: <span id="score">0</span>
	</div>
	<div class="stat" id="toggle-help" onClick="switchHelp()">
		Hide help
	</div>
</footer>

<?php lab_close(); ?>