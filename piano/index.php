<?php define('included',1);include'../include/builder.php';lab_open("Piano",468,1,1,"piano","I spied a friend playing a game on an Android tablet that I looked fairly easy to replicate in JavaScript and HTML. I'll probably be proved wrong in this assumption.") ?>

<div id="board">

	<div class="row">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>
	<div class="row">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>
	<div class="row">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>
	<div class="row">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>
	<div class="row">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>
	<div class="row">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>
	<div class="row start">
		<div class="cell"></div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
	</div>

	<div id="failbox">
		<h2>:(</h2>
		<p>You failed. You scored <span id="failscore"></span> points, and your <span id="new"></span>high score is <span id="highscore"></span>.<span id="comment"></span></p>
	</div>

	<div class="id">0</div>

</div>

<?php lab_close(); ?>
