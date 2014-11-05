<?php define('included',1);include'../include/builder.php';lab_open("Random",300,1,1,"random","<p>An advancement of lab.Die, this application allows you to enter values of your own, and let JavaScript pick one by random. You can also load presets, and save your own using the HTML5 <code>localstorage</code> API.</p><h3>Keyboard Shortcuts</h3><table class=\"micro\"><tr><td>r/space</td><td>run</td></tr><tr><td>return/enter</td><td>confirm</td></tr><tr><td>a</td><td>add value</td></tr><tr><td>s</td><td>save preset</td></tr></table>"); ?>

<form>
	<input type="text" placeholder="Value to add" class="submit-inline">
	<input type="submit" value="Add">
</form>
<form>
	<input type="submit" value="Run">
</form>
<div class="line-h"></div>
<ul class="no-b striped">
	<li>1<div></div></li>
	<li>2<div></div></li>
	<li>3<div></div></li>
	<li>4<div></div></li>
	<li>5<div></div></li>
	<li>6<div></div></li>
</ul>

<h4>Presets</h4>
<form>
	<select name="select">
		<option value="1">Die</option>
		<option value="2">Hex Characters</option>
		<option value="3">Alphabet</option>
	</select>
</form>
<p>Use the drop-down menu below to select a preset list.</p>
<form>
	<input type="text" placeholder="Preset name" class="submit-inline">
	<input type="submit" value="Save">
</form>
<p>Save the current list as a preset, which will be added to the drop-down list above. The presets will not be lost when refreshing.</p>
<?php lab_close(); ?>