<?php define('included',1);include'../include/builder.php';lab_open("Die",220,1,3,"die","The experiment that started the lab to begin with, lab.Die simply emulates a six-sides game die. Each click generates a new random number. If the number appears not to change, you've just rolled the same number twice.") ?>

<div class="os" id="r">n</div>
<span class="btn" onclick="rand()">Generate Number</span>

<?php lab_close("<script type=\"text/javascript\">disableSelection(document.body)</script>")?>