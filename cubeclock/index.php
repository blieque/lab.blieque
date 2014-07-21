<?php define('included',1);include'../_cgi-bin/builder.php';lab_open('Cubeclock',"520x2",1,1,'cc','A friend of mine became interested in Rubik\'s cubes and solving them in the quickest times.','<!--[if IE]><script type="text/javascript">isIE = 1</script><![endif]--><script src="../js/fc.min.js\"></script>'); ?>

<div id="t">00:00.00</div>
<span id="b" class="btn">Start</span>

</div>
<div>

<h3>Records</h3>
<ul class="no-b tb-set">
	<!-- stuck together because of whitespace tricksiness -->
	<li>2 &times; 2 &times; 2</li><li class="tb-sel">3 &times; 3 &times; 3</li><li>4 &times; 4 &times; 4</li>
</ul>
<table id="r" class="striped">
	<thead>
		<tr>
			<td>Single</td>
			<td>Average</td>
		</tr>
	</thead>
	<tbody>
		<!-- tr*10>td{-}*2 -->
		<tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr><tr><td>-</td><td>-</td></tr>
	</tbody>
</table>
<div>
	<span id="c1">clear records</span>
	<span>are you sure?<span id="c3">yes</span><span id="c2">no</span></span>
</div>

</div>
</div><div class="c">
<div>

<h3>Options</h3>
<form>
	<label><input type="checkbox" name="cd">15-second plan countdown</label>
	<label class="indent"><input type="checkbox" name="cd-as">Audible sound</label>
</form>

</div><div>

<h3>Algorithms</h3>
<h4>Scramble Sequence</h4>
<div id="s"><span>click to generate</span><div>generate new</div></div>
<h4>Reference</h4>
<form>
	<select id="as">
		<option selected disabled>Choose an algorithm</option>
		<option value="0">Chequerboard Pattern</option>
		<option value="1">Superflip</option>
	</select>
</form>
<div id="a"></div>

</div><div>

<h3>Calculate Averages</h3>
<p>Will allow calculation of best-of-three and best-of-five averages.</p>
<form>
	<label><input type="radio" name="a" value="3">Best of three</label>
	<label><input type="radio" name="a" value="5">Best of five</label>
</form>

<?php lab_close()?>