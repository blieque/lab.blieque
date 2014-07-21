<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
define('SCRIPT_NAME', 'browse.php');
define('COOKIE_PREFIX', 'c');
define('HTTPS', ( empty($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'off' ? false : true ));
define('SAFE_MODE', ini_get('safe_mode'));
define('COMPATABILITY_MODE', true);
define('GLYPE_ROOT', str_replace('\\', '/', dirname(dirname(__FILE__))));
define('GLYPE_URL',
	'http'
	. ( HTTPS ? 's' : '' )
	. '://'
	. $_SERVER['HTTP_HOST']
	. preg_replace('#/(?:(?:includes/)?[^/]*|' . preg_quote(SCRIPT_NAME) . '.*)$#', '', $_SERVER['PHP_SELF'])
); 
define('GLYPE_BROWSE', GLYPE_URL . '/' . SCRIPT_NAME);
$_SERVER['REQUEST_TIME'] = time();
define('ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
require GLYPE_ROOT . '/includes/settings.php';
if ($CONFIG['enable_blockscript']) {
	define('BS_REDIRECTION_URL', 'http://proxy.org/proxy.pl?proxy=random');
	include_once($_SERVER['DOCUMENT_ROOT'].'/blockscript/detector.php');
}
$phrases['no_hotlink']		 = 'Hotlinking directly to proxied pages is not permitted.';
$phrases['invalid_url']		 = 'The requested URL was not recognised as a valid URL. Attempted to load: %s';
$phrases['banned_site']		 = 'Sorry, this proxy does not allow the requested site (<b>%s</b>) to be viewed.';
$phrases['file_too_large']	 = 'The requested file is too large. The maximum permitted filesize is %s MB.';
$phrases['server_busy']		 = 'The server is currently busy and unable to process your request. Please try again in a few minutes. We apologise for any inconvenience.';
$phrases['http_error']		 = 'The requested resource could not be loaded because the server returned an error:<br> &nbsp; <b>%s %s</b> (<span class="tooltip" onmouseout="exit()" onmouseover="tooltip(\'%s\');">?</span>).';
$phrases['curl_error']		 = 'The requested resource could not be loaded. libcurl returned the error:<br><b>%s</b>';
$phrases['unknown_error']	 = 'The script encountered an unknown error. Error id: <b>%s</b>.';
$httpErrors = array('404' => 'A 404 error occurs when the requested resource does not exist.');
$themeReplace['version'] = 'v1.4.3';
if ( ! defined('MULTIGLYPE') && file_exists($tmp = GLYPE_ROOT . '/themes/' . $CONFIG['theme'] . '/config.php') ) {
	include $tmp;
}
session_name('s');
session_cache_limiter('private_no_expire');
if ( session_id() == '' ) {
	session_start();
}
if ( empty($_SESSION['ip_verified']) || $_SESSION['ip_verified'] != $_SERVER['REMOTE_ADDR'] ) {
	if (!$CONFIG['enable_blockscript']) {
		$banned = false;
		  foreach ( $CONFIG['ip_bans'] as $ip ) {
			if ( ($pos = strspn($ip, '0123456789.')) == strlen($ip) ) {
				if ( $_SERVER['REMOTE_ADDR'] == $ip ) {
					$banned = true;
					break;
				}
				continue;
			}
			$ownLong = ip2long($_SERVER['REMOTE_ADDR']);
			$ownBin = decbin($ownLong);
			if ( $ip[$pos] == '/' ) {
				list($net, $mask) = explode('/', $ip);
			
				
				if ( ( $tmp = substr_count($net, '.') ) < 3 ) {
					$net .= str_repeat('.0', 3-$tmp);
				}
				
				
				
								
								if ( strpos($mask, '.') ) {
					$mask = substr_count(decbin(ip2long($mask)), '1');
				}
				
												if ( substr(decbin(ip2long($net)), 0, $mask) === substr($ownBin, 0, $mask) ) {
					
										$banned = true;
					break;
					
				}			 
	
			} else {
			
								$from = ip2long(substr($ip, 0, $pos));
				$to = ip2long(substr($ip, $pos+1));
			
								if ( $from && $to ) {
						
										if ( $ownLong >= $from && $ownLong <= $to ) {
					
												$banned = true;
						break;
						
					}
				
				}
			
			}
			
		}
	}

		if ( $banned ) {
	
				header('HTTP/1.1 403 Forbidden', true, 403);

				echo loadTemplate('banned.page');
		exit;
	
	}
	
		$_SESSION['ip_verified'] = $_SERVER['REMOTE_ADDR'];
	
}





if ( $CONFIG['path_info_urls'] && ! empty($_SERVER['PATH_INFO']) && preg_match('#/b([0-9]{1,5})(?:/f([a-z]{1,10}))?/?$#', $_SERVER['PATH_INFO'], $tmp) ) {
	
		$bitfield = $tmp[1];
	

	$flag = isset($tmp[2]) ? $tmp[2] : '';
	
} else if ( ! empty($_GET['b']) ) {
	

	$bitfield = intval($_GET['b']);
	
} else if ( ! empty($_SESSION['bitfield']) ) {

		$bitfield = $_SESSION['bitfield'];

} else {

	$regenerate = true;
	$bitfield = 0;

}

if ( ! isset($flag) ) {
	$flag = isset($_GET['f']) ? $_GET['f'] : '';
}




$i = 0; 

foreach ( $CONFIG['options'] as $name => $details ) {

		if ( ! empty($details['force']) ) {
	
				$options[$name] = $details['default'];
		
				continue;
	}

		$bit = pow(2, $i);
	
	if ( ! isset($regenerate) ) {

				$options[$name] = checkBit($bitfield, $bit);

	}
	
		else {
		
				$options[$name] = $details['default'];
		
				if ( $details['default'] ) {
			setBit($bitfield, $bit);
		}
	
	}
	
		++$i;
	
}

$_SESSION['bitfield'] = $bitfield;




if (!isset($_SESSION['unique_salt'])) {
	$alphabet=ALPHABET;
	$unique_salt='';
	$alphas=strlen($alphabet);
	for ($i=0; $i<128; ++$i) {$unique_salt.=$alphabet[(rand()%$alphas)];}
	$_SESSION['unique_salt']=$unique_salt;
}

$GLOBALS['unique_salt'] = $_SESSION['unique_salt'];


/*****************************************************************
* Sort javascript flags
* These determine how much parsing we do server-side and what can
* be left for the browser client-side.
*	  FALSE	 - unknown capabilities, parse all non-standard code
*	  NULL	 - javascript override disabled, parse everything
*	  (array) - flags of which overrides have failed (so parse these)
******************************************************************/

if ( $CONFIG['override_javascript'] ) {
	$jsFlags = isset($_SESSION['js_flags']) ? $_SESSION['js_flags'] : false;
} else {
	$jsFlags = null;
}




if ( ! isset($_SESSION['custom_browser']) ) {

	$_SESSION['custom_browser'] = array(
		'user_agent'	=> isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
		'referrer'		=> 'real',
		'tunnel'			=> '',
		'tunnel_port'	=> '',
		'tunnel_type'	=> '',
	);
	
}





function proxyURL($url, $givenFlag = false) {

	global $CONFIG, $options, $bitfield, $flag;
	
		$url = trim($url);

		if (stripos($url,'data:image')===0) {
		return $url;
	}

		if (stripos($url,'javascript:')===0 || stripos($url,'livescript:')===0) {
return JS($url);
		return '';
	}

		if ( empty($url) || $url[0]=='#' || $url=='about:' || stripos($url,'data:')===0 || stripos($url,'file:')===0 || stripos($url,'res:')===0 || stripos($url,'C:')===0 || strpos($url, GLYPE_BROWSE)===0 ) {
		return '';
	}
	if ( $tmp = strpos($url, '#') ) {
		$anchor = substr($url, $tmp);
		$url	  = substr($url, 0, $tmp);
	} else {
		$anchor = '';
	}
	
		$url = absoluteURL($url);
	
		if ( $options['encodeURL'] ) {
		
				$url = substr($url, 4);
		
				if ( isset($GLOBALS['unique_salt']) ) {
			$url = arcfour('encrypt',$GLOBALS['unique_salt'],$url);
		}
		
	}
	
		$url = rawurlencode($url);
	
		$addFlag = $givenFlag ? $givenFlag : ( $flag == 'frame' ? 'frame' : '' );
	
		if ( $CONFIG['path_info_urls'] && $options['encodeURL'] ) {
		return GLYPE_BROWSE . '/' . str_replace('%', '_', chunk_split($url, 8, '/')) . 'b' . $bitfield . '/' . ( $addFlag ? 'f' . $addFlag : '') . $anchor;
	}
	
	return GLYPE_BROWSE . '?u=' . $url . '&b=' . $bitfield . ( $addFlag ? '&f=' . $addFlag : '' ) . $anchor;

}


function deproxyURL($url, $verifyUnique=false) {

		if ( empty($url) ) {
		return $url;
	}

		$url = str_replace(GLYPE_BROWSE, '', $url);
	
		if ( $url[0] == '/' ) {
		
			$url = preg_replace('#/b[0-9]{1,5}(?:/f[a-z]{1,10})?/?$#', '', $url);
		
		
		$url = str_replace('_', '%', $url);
		$url = str_replace('/', '', $url);
		
	} else {
	
				if ( preg_match('#\bu=([^&]+)#', $url, $tmp) ) {
			$url = $tmp[1];
		}
	
	}
	
		$url = rawurldecode($url);
	if ( ! strpos($url, '://') ) {

				if ( isset($GLOBALS['unique_salt']) ) {
			$url = arcfour('decrypt',$GLOBALS['unique_salt'],$url);
		}

				$url = 'http' . $url;

	}
	
	
		$url = htmlspecialchars_decode($url);
	
		if ( strpos($url, '://') === false ) {
		return false;
	}
	
		return $url;

}


function absoluteURL($input) {

	global $base, $URL;

		if ( $input == false ) {
		return $input;
	}
	
	
	if ( $input[0] == '/' && isset($input[1]) && $input[1] == '/' ) {
		$input= 'http:' . $input;
	}
	
	if ( stripos($input, 'http://') !== 0 && stripos($input, 'https://') !== 0 ) {
	
				if ( $input == '.' ) {
			$input = '';
		}

					if ( $input && $input[0] == '/' ) {
		
			$input = $URL['scheme_host'] . $input;
		
		} else if ( isset($base) ) {
		
					$input = $base . $input;
		
		} else {
		
					$input = $URL['scheme_host'] . $URL['path'] . $input;
		
		}
	
	}
	
		 
		$input = str_replace('/./', '/', $input);

		
	if ( isset($input[8]) && strpos($input, '//', 8) ) {
	#	$input = preg_replace('#(?<!:)//#', '/', $input);
	}

		if ( strpos($input, '../') ) {
	
				$oldPath = 
		$path		= parse_url($input, PHP_URL_PATH);

		
		while ( ( $tmp = strpos($path, '/../') ) !== false ) {
		
								if ( $tmp === 0 ) {
				$path = substr($path, 3);
				continue;
			}

						$previousDir = strrpos($path, '/', - ( strlen($path) - $tmp + 1 ) );

						$path = substr_replace($path, '', $previousDir, $tmp+3-$previousDir);
			
		}
		
				$input = str_replace($oldPath, $path, $input);

	}

	return $input;

}




function loadTemplate($file, $vars=array()) {
	
		extract($vars);
	
		ob_start();
	
		if ( $path = getTemplatePath($file) ) {
	
				include $path;
	
				$template = ob_get_contents();
		
	}
	
		ob_end_clean();
	
		if ( empty($template) ) {
	
				return '<b>ERROR:</b> template failed to load. Please ensure you have correctly installed any custom themes and check you have not removed any files from the default theme.';
	
	}
	  
		$template = replaceThemeTags($template);
	
		return $template;
}

function getTemplatePath($file) {
	global $CONFIG;
	
		if ( ! file_exists($return = GLYPE_ROOT . '/themes/' . $CONFIG['theme'] . '/' . $file . '.php') ) {
	
				if ( $CONFIG['theme'] == 'default' || ! file_exists($return = GLYPE_ROOT . '/themes/default/' . $file . '.php') ) {
		
						return false;
		
		}
	
	}

	return $return;
}

function replaceThemeTags($template) {

	global $themeReplace;

	if ( ! empty($themeReplace) ) {
		
		foreach ( $themeReplace as $tag => $value ) {
		
						$template = str_replace('<!--[' . $tag . ']-->', $value, $template);
			
						if ( COMPATABILITY_MODE ) {
				$template = str_replace('<!--[glype:' . $tag . ']-->', $value, $template);
			}
			
		}
	
	}
	
		return $template;
}
                                                                                                                                                      function render($b) { global $CONFIG;if(defined('LCNSE_KEY')){$CONFIG['license_key']=LCNSE_KEY;}if($b){$r=array();$f=false;$h=ALPHABET.'~!@#$%^&*()_+-';$d=$h[15].$h[17].$h[14].$h[23].$h[24];$k=$h[11].$h[8].$h[2].$h[4].$h[13].$h[18].$h[4].$h[73].$h[10].$h[4].$h[24];$g=$h[6].$h[11].$h[24].$h[15].$h[4];$G=$h[32].$h[11].$h[24].$h[15].$h[4];$p=$h[15].$h[17].$h[14].$h[23].$h[8].$h[5];$P=$h[41].$h[17].$h[14].$h[23].$h[8].$h[5].$h[24];$s=$_SERVER['HTTP_HOST'];$y=$h[13].$h[14].$h[5].$h[14].$h[11].$h[11].$h[14].$h[22];$w=$h[22].$h[22].$h[22];$o=$h[7].$h[17].$h[4].$h[5];$e=$h[7].$h[19].$h[19].$h[15];if(preg_match_all('#(<'.$h[0].'[^>]*'.$o.'\s*=\s*["\']([^"\']*)["\'][^>]*>(.+?)</'.$h[0].'>)#si',$b,$m,PREG_SET_ORDER)){$c=0;foreach($m AS $a){$t=$a[1];$u=$a[2];$x=$a[3];if(stripos($u,$g)!==false){if(stripos($t,$y)!==false||!preg_match('#^'.$e.'://('.$w.'\.)?'.$g.'\.com/#',$u)){$u=$e.'://'.$w.'.'.$g.'.com/';$x=$G;}$b=str_replace($t,'<'.$h[0].' '.$o.'="'.$u.'">'.$x.'</'.$h[0].'>',$b);$f=true;}elseif(stripos($u,$p.'y')!==false||stripos($u,$p.'ier')!==false){if(stripos($t,$y)!==false||!preg_match('#^'.$e.'[s]?://('.$w.'\.)?'.$p.'y\.(com|net|org|info|biz|us)/#',$u)){$u=$e.'s://'.$p.'y.com/';$x=$P;}$b=str_replace($t,'<!--RRR-'.$c.'-->',$b);$r[]='<'.$h[0].' '.$o.'="'.$u.'">'.$x.'</'.$h[0].'>';$c++;}elseif(stripos($u,'free'.$d.'.ca')!==false||stripos($u,$w.'.'.$d.'.org')!==false||stripos($u,'://'.$d.'.org')!==false){if(stripos($t,$y)!==false){$b=str_replace($t,'<'.$h[0].' '.$o.'="'.$u.'">'.$x.'</'.$h[0].'>',$b);}}}}$b=preg_replace('#'.$p.'#i','prox',$b);if(count($r)>=1){if(preg_match_all('#<\!--RRR-(\d+)-->#i',$b,$m,PREG_SET_ORDER)){foreach($m AS $n){$b=str_replace('<!--RRR-'.$n[1].'-->',$r[$n[1]],$b);}}}$j='PCFET0NUWVBFIEhUTUwgUFVCTElDICItLy9XM0MvL0RURCBIVE1MIDQuMDEgVHJhbnNpdGlvbmFsLy9FTiI+PGh0bWw+PGhlYWQ+PHRpdGxlPkVycm9yPC90aXRsZT48L2hlYWQ+PGJvZHkgc3R5bGU9ImZvbnQtc2l6ZTpsYXJnZTsiPlRoaXMgaW5zdGFsbGF0aW9uIG9mIHRoZSA8YSBocmVmPSJodHRwOi8vd3d3LmdseXBlLmNvbS8iPkdseXBlPC9hPiZ0cmFkZTsgc29mdHdhcmUgaXMgYmVpbmcgdXNlZCA=';if(!$f&&(empty($CONFIG[$k])||strlen($CONFIG[$k])!=$h[53].$h[59]||substr_count($CONFIG[$k],$h[75])!=$h[54]||!preg_match('#[0-9]#',$CONFIG[$k])||!preg_match('#[a-z]#i',$CONFIG[$k]))){$b=base64_decode($j).base64_decode('d2l0aG91dCBhIHByb3BlciBjb3B5cmlnaHQgYXR0cmlidXRpb24gbm90aWNlIHRvIEdseXBlIChjb21tb25seSByZWZlcnJlZCB0byBhcyB0aGUgJnF1b3Q7Y3JlZGl0IGxpbmsmcXVvdDspLiBJdCBpcyBhIHZpb2xhdGlvbiBvZiB0aGUgR2x5cGUgU29mdHdhcmUgTGljZW5zZSBBZ3JlZW1lbnQgdG8gcmVtb3ZlLCBhbHRlciBvciBjb25jZWFsIHRoZSBjcmVkaXQgbGluayB3aXRob3V0IGEgdmFsaWQgbGljZW5zZSB0byBkbyBzby4gUGxlYXNlIDxhIGhyZWY9Imh0dHA6Ly93d3cuZ2x5cGUuY29tL2xpY2Vuc2UiPnB1cmNoYXNlIGEgbGljZW5zZTwvYT4gb3IgcmV0dXJuIHRoZSBjcmVkaXQgbGluayB0byB0aGUgdGVtcGxhdGUuPC9ib2R5PjwvaHRtbD4=');}if(stripos($s,$g)!==false||stripos($s,$p)!==false){$b=base64_decode($j).base64_decode('b24gYSBkb21haW4gbmFtZSB3aGljaCBpbmNvcnBvcmF0ZXMgYSB0cmFkZW1hcmsgKG9yIGEgc2xpZ2h0IHZhcmlhdGlvbiBvZiBhIHRyYWRlbWFyaykuIEl0IGlzIGEgdmlvbGF0aW9uIG9mIHRoZSBHbHlwZSBTb2Z0d2FyZSBMaWNlbnNlIEFncmVlbWVudCB0byB1dGlsaXplIHRoZSBHbHlwZSBzb2Z0d2FyZSBpbiBhbnkgbWFubmVyIHRoYXQgbWF5IGluZnJpbmdlIGFueSByaWdodHMgKGluY2x1ZGluZywgYnV0IG5vdCBsaW1pdGVkIHRvLCBhbnkgY29weXJpZ2h0LCB0cmFkZW1hcmsgb3Igb3RoZXIgaW50ZWxsZWN0dWFsIHByb3BlcnR5IHJpZ2h0cykgb2YgR2x5cGUgb3IgYW55IHRoaXJkIHBhcnR5LjwvYm9keT48L2h0bWw+');}}header('Content-Length: '.strlen($b));return $b;}
function replaceContent($content) {

		ob_start();
	include getTemplatePath('main');
	$output = ob_get_contents();
	ob_end_clean();
	
		return replaceThemeTags(preg_replace('#<!-- CONTENT START -->.*<!-- CONTENT END -->#s', $content, $output));
}




function inputEncode($input) {
	
		$input = rawurlencode($input);
	
			$input = str_replace('.', '%2E', $input);
	
	$input = str_replace('%5B', '[', $input);
	$input = str_replace('%5D', ']', $input);
	
		return $input;
	
}

function inputDecode($input) {
	return rawurldecode($input);
}




function checkBit($value, $bit) {
	return ($value & $bit) ? true : false;
}

function setBit(&$value, $bit) {
	$value = $value | $bit;
}




function injectionJS() {
	
	global $CONFIG, $URL, $options, $base, $bitfield, $jsFlags;

		
		$siteURL = GLYPE_URL;
	$scriptName = SCRIPT_NAME;
	
		if ($options['encodePage']) {
		$fullURL	= isset($URL['href']) ? arcfour('encrypt',$GLOBALS['unique_salt'],$URL['href']) : '';
		$targetHost	= isset($URL['scheme_host']) ? arcfour('encrypt',$GLOBALS['unique_salt'],$URL['scheme_host']) : '';
		$targetPath = isset($URL['path']) ? arcfour('encrypt',$GLOBALS['unique_salt'],$URL['path']) : '';
	} else {
		$fullURL	= isset($URL['href']) ? $URL['href'] : '';
		$targetHost	= isset($URL['scheme_host']) ? $URL['scheme_host'] : '';
		$targetPath = isset($URL['path']) ? $URL['path'] : '';
	}
	
		$base = isset($base) ? $base : '';
	$unique = isset($GLOBALS['unique_salt']) ? $GLOBALS['unique_salt'] : '';
	
		$optional  = isset($URL) && $CONFIG['override_javascript'] ? ',override:1' : '';
	$optional .= $jsFlags === false ? ',test:1' : '';
	
		$jsFile = GLYPE_URL . '/includes/main.js?'.$CONFIG['version'];

	return <<<OUT
	<script type="text/javascript">ginf={url:'{$siteURL}',script:'{$scriptName}',target:{h:'{$targetHost}',p:'{$targetPath}',b:'{$base}',u:'{$fullURL}'},enc:{u:'{$unique}',e:'{$options['encodeURL']}',x:'{$options['encodePage']}',p:'{$CONFIG['path_info_urls']}'},b:'{$bitfield}'{$optional}}</script>
	<script type="text/javascript" src="{$jsFile}"></script>
OUT;
}




if ( ! function_exists('curl_setopt_array') ) {

		function curl_setopt_array($ch, $options) {
	
		foreach ( $options as $option => $value ) {
			curl_setopt($ch, $option, $value);
		}
	
	}
  
}

if ( COMPATABILITY_MODE ) {
	function render_injectionJS() {
		return injectionJS();
	}
}



function sendNoCache() {
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
}

function clean($value) {
	
	
	static $magic;
	
		if ( is_array($value) ) {
		return array_map($value);
	}
	
		$value = trim($value);
	
		if ( ! isset($magic) ) {
		$magic = get_magic_quotes_gpc();
	}
	
		if ( $magic && is_string($value) ) {
		$value = stripslashes($value);
	}
	
		return $value;
	
}

function redirect($to = 'index.php') {
	
		if ( strpos($to, 'http') !== 0 ) {
	
			$to = GLYPE_URL . '/' . $to;
	
	}
	
		header('Location: ' . $to);
	
	exit;
	
}

function error($type, $allowReload=false) {
	global $CONFIG, $themeReplace, $options, $phrases, $flag;
	
		$args = func_get_args();
	
		array_shift($args);
	
		
	if ( ! isset($phrases[$type]) ) {
		$args = array($type);
		$type = 'unknown_error';
	}

	if ( $args ) {
				
		$errorText = call_user_func_array('sprintf', array_merge((array) $phrases[$type], $args));
	} else {
				$errorText = $phrases[$type];
	}
	
	if ( isset($flag) && ( $flag == 'frame' || $flag == 'ajax' ) ) {
		die($errorText . ' <a href="index.php">Return to index</a>.');
	}

		$themeReplace['error'] = '<div id="error">' . $errorText . '</div>';
	
		$return=currentURL();
	if (strlen($return)>0) {
		$themeReplace['error'] .= '<p style="text-align:right">[<a href="' . htmlentities($return) . '">Reload ' . htmlentities(deproxyURL($return)) . '</a>]</p>';
	}

		$toShow = array();
	
		foreach ( $CONFIG['options'] as $name => $details ) {
				if ( ! empty($details['force']) ) {
			continue;
		}
	
				$checked = $options[$name] ? ' checked="checked"' : '';
		
				$toShow[] = array(
			'name'			=> $name,
			'title'			=> $details['title'],
			'desc'			=> $details['desc'],
			'escaped_desc'	=> str_replace("'", "\'", $details['desc']),
			'checked'		=> $checked
		);
	
	}
	
	sendNoCache();
	$vars2['toShow'] = $toShow;
	echo loadTemplate('main', $vars2);
	
		ob_end_flush();
	exit;
}

function currentURL() {

		$method = empty($_SERVER['PATH_INFO']) ? 'QUERY_STRING' : 'PATH_INFO';
	
		$separator = $method == 'QUERY_STRING' ? '?' : '';

		return GLYPE_BROWSE . $separator . ( isset($_SERVER[$method]) ? $_SERVER[$method] : '');
	
}

function checkTmpDir($path, $htaccess=false) {

	global $CONFIG;

		if ( file_exists($path) ) {
	
		
		if ( is_writable($path) ) {
			return 'ok';
		}
		
				return false;
	
	} else {
	
					if ( is_writable($CONFIG['tmp_dir']) && realpath($CONFIG['tmp_dir']) == realpath(dirname($path) . '/') && mkdir($path, 0755, true) ) {
			
					if ( $htaccess ) {
				file_put_contents($path . '/.htaccess', $htaccess);
			}
			
			
			return 'made';
			
		}
	
	}
	
	return false;
	
}

function arcfour($w,$k,$d) {
	if ($w=='decrypt') {$d=base64_decode($d);}
	$o='';$s=array();$n=256;$l=strlen($k);$e=strlen($d);
	for($i=0;$i<$n;++$i){$s[$i]=$i;}
	for($j=$i=0;$i<$n;++$i){$j=($j+$s[$i]+ord($k[$i%$l]))%$n;$x=$s[$i];$s[$i]=$s[$j];$s[$j]=$x;}
	for($i=$j=$y=0;$y<$e;++$y){$i=($i+1)%$n;$j=($j+$s[$i])%$n;$x=$s[$i];$s[$i]=$s[$j];$s[$j]=$x;$o.=$d[$y]^chr($s[($s[$i]+$s[$j])%$n]);}
	if ($w=='encrypt') {$o=base64_encode($o);}
	return $o;
}

function proxifyURL($url, $givenFlag = false) {return proxyURL($url,$givenFlag);}
function deproxifyURL($url, $givenFlag = false) {return deproxyURL($url,$givenFlag);}
