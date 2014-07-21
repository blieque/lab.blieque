<!DOCTYPE html>
<html>
	<head>
		<title>lab.Mirror</title>
		<link rel="shortcut icon" href="../images/favicon.ico" />
		<link rel="stylesheet" href="../common.css" />
		<style type="text/css">
			body>div {
				width:300px;
				text-align:left;
			}
			#opsies {
				font-size:11pt;
			}
			#options {
				margin:2px 0 10px;
			}
			form a {
				margin:0 0 0 6px;
			}
			form a:hover {
				color:#fff;
				text-decoration:none;
			}
			input[type="text"] {
				border:0;
			}
			ul {
				padding:0;
			}
			li {
				list-style:none;
			}
			#input {
				margin:0 0 8px;
				width:91%;
			}
			h2 {
				margin:0 0 3px;
			}
			#error {
				border:1px solid red;
				padding:2px;
				margin:5px 0 15px 0;
				background:#eee;
			}
			.center {
				margin:8px 0 0;
				text-align:center;
			}
			#tooltip{ 
				margin:0;
				padding:20px;
				width:20em;
				background:#fff;
				box-shadow:0 1px 0 2px rgba(0,0,0,.04);
			}
		</style>
		<?=injectionJS();
				?>
	</head>
	<body>
		<div id="wrapper">
			<h1><span id="fade">lab.</span><span id="bold">Mirror</span></h1>
			<h2>Enter URL</h2>
			<form action="includes/process.php?action=update" method="post" onsubmit="return updateLocation(this);" id="form">
				<input type="text" name="u" id="input" size="40">
            	<a href="javascript:{}" onclick="document.getElementById('form').submit();">GO</a>
				<span id="opsies">Options</span>
				<ul id="options">
					<?php foreach ($toShow as $option) echo '<li><input type="checkbox" name="'.$option['name'].'" id="'.$option['name'].'"'.$option['checked'].'><label for="'.$option['name'].'" class="tooltip" onmouseover="tooltip(\''.$option['escaped_desc'].'\')" onmouseout="exit();
						">'.$option['title'].'</label></li>';
					?>
				</ul>
			</form>
			<p class="center">Powered by <a href="http://www.glype.com/">Glype</a>&reg; <!--[version]-->.</p>  
	</div>
	</body>
</html>