<?php

if (defined('included')) {
	//legend:lab_open(   str,	str,		  bin,		   int,	   str,	    str,	   str,		  str)
	function lab_open($title,$width,$unique_style,$script_mode,$script,$tooltip,$extrab="",$extrae="") {

		$open = '<!DOCTYPE html><html><head><title>lab.' . $title . '</title><link rel="shortcut icon" href="../img/f.ico"><meta name="viewport" content="width=device-width,initial-scale=1">' . $extrab . '<link rel="stylesheet" type="text/css" href="../common.css">';

		$width_col = explode("x",$width);
		if (count($width_col) > 1) {
			$width = $width_col[0] . "px";

			$width_col[0] *= $width_col[1];
			$width_col = "calc(" . $width_col[0] . "px + " . ($width_col[1] - 1) . "em)";
		} else {
			$width = $width_col[0] . "px";
		}
		$open .= '<style type="text/css">body>div{width:' . $width . '}.c{width:' . $width . '}</style>';

		if ($unique_style == 1) {
			$open .= '<link rel="stylesheet" type="text/css" href="main.css">';
		}

		switch ($script_mode) {
			case 0: // No JavaScript
				break;
			case 1: // jQuery and named script (relative)
				//$open .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script><script src="jquery.'.$script.'.js"></script>';
				$open .= '<script src="/js/jquery.min.js"></script><script src="jquery.'.$script.'.js"></script>';
				break;
			case 2: // jQuery and named script (common)
				$open .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script><script src="../js/jquery.'.$script.'.js"></script>';
				break;
			case 3: // Named script (relative)
				$open .= '<script src="'.$script.'.js"></script>';
				break;
			case 4: // Named script (common)
				$open .= '<script src="../js/'.$script.'.js"></script>';
				break;
		}

		$open .= $extrae.'</head><body><div><div class="c"><div><h1><span>lab.</span><span>'.$title.'</span><a id="tltp">?<div></div><div>'.$tooltip.'</div></a></h1>';
		echo $open;

	}

	function lab_close($extra=null) {

		echo "</div></div></div>".$extra."</body></html>";

	}

} else {

	echo '<!DOCTYPE><html><body><p style="font-family:source sans pro,lucida sans,helvetica neue,helvetica,sans-serif">This file is used to build the basis of each lab.Blieque experiment.<br>It\'s only basic PHP, but contact me if you\'re desperately curious.</p></body></html>';

}

?>
