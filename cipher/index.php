<?php define('included',1);include'../_cgi-bin/builder.php';lab_open("Cipher",360,1,3,"cipher","This experiment allows you to encode words using a simple Caesar shift, and then decode them given the correct offset.") ?>

<div id="modify">
	<span class="btn" onClick="changeShiftSub()">-</a>
	<span id="current">0</span>
	<span class="btn" onClick="changeShiftAdd()">+</a>
</div>
<div class="alpha"></div>
<div class="alpha" id="ticker"></div>
<form name="form1">
	<h3>Decrypted</h3>
	<textarea class="textstyle de" name="plaintext" rows="4"></textarea>
	<span class="btn" name="decrypt" onClick="checkDecryption()">Decrypt</span>
	<span class="btn" name="encrypt" onClick="checkEncryption()">Encrypt</span>
	<h3>Encrypted</h3>
	<textarea class="textstyle en" name="ciphertext" rows="4" spellcheck="false"></textarea>
</form>

<?php lab_close(); ?>