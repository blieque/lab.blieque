<?php define('included',1);include'../_cgi-bin/builder.php';lab_open("Echo",240,0,0,"","I built this to test the keywords my school's filtering system looked out for. Interesting <em>poop</em> was deemed too explicit. All the app does is return the text you enter."); ?>

<form method="post" action=".">
	<label for="s">Give me a string to echo:</label>
	<input name="s">
	<input type="submit">
</form>

<?php if ($s=$_GET["s"] || $s=$_POST["s"]) { echo"<h4>Here:</h4><p>".$s."</p>"; } lab_close();?>