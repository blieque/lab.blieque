<?php
require 'includes/init.php';
sendNoCache();
ob_start('render');
if (!isset($_GET['e']) || $_GET['e']!='no_hotlink') {
	$_SESSION['no_hotlink'] = true;
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
if ( isset($_GET['e']) && isset($phrases[$_GET['e']]) ) {
	$args = isset($_GET['p']) ? @unserialize(base64_decode($_GET['p'])) : array();
	if ( ! is_array($args) ) {
		$args = array();
	}
	if ( $args ) {
		$args = array_merge( (array) $phrases[$_GET['e']], $args);
		$error = call_user_func_array('sprintf',$args);
	} else {
		$error = $phrases[$_GET['e']];
	}
	$themeReplace['error'] = '<div id="error">' . $error . '</div>';
	if ( ! empty($_GET['return']) ) {
		$themeReplace['error'] .= '<p style="text-align:right">[<a href="' . htmlentities($_GET['return']) . '">Reload ' . htmlentities(deproxyURL($_GET['return'])) . '</a>]</p>';
	}
}
if ( version_compare(PHP_VERSION, 5) < 0 ) {
	$themeReplace['error'] = '<div id="error">You need PHP 5 to run this script. You are currently running ' . PHP_VERSION . '</div>';
}
if (count($adminDetails)===0) {
	header("HTTP/1.1 302 Found"); header("Location: admin.php"); exit;
}
if ( $CONFIG['tmp_cleanup_interval'] ) {
	if ( file_exists($file = $CONFIG['tmp_dir'] . 'cron.php') ) {
		include $file;
		$runCleanup = $nextRun <= $_SERVER['REQUEST_TIME'];
	} else {
		$runCleanup = true;
	}
	if ( ! empty($runCleanup) ) {
		header('Connection: Close');
	}
}
$vars['toShow'] = $toShow;
echo loadTemplate('main', $vars);
ob_end_flush();
if ( ! empty($runCleanup) ) {
	ignore_user_abort(true);
	file_put_contents($file, '<?php $nextRun = ' . ( $_SERVER['REQUEST_TIME'] + round(3600 * $CONFIG['tmp_cleanup_interval']) ) . ';');
	if ( is_dir($CONFIG['cookies_folder']) && ( $handle = opendir($CONFIG['cookies_folder']) ) ) {
		$cutOff = $_SERVER['REQUEST_TIME']-86400;
		while ( ( $file = readdir($handle) ) !== false ) {
			if ( $file[0] == '.' ) {
				continue;
			}
			$path = $CONFIG['cookies_folder'] . $file;
			if ( filemtime($path) > $cutOff ) {
				continue;
			}
			unlink($path);
		}
		closedir($handle);
	}
	if ( $CONFIG['tmp_cleanup_logs'] && is_dir($CONFIG['logging_destination']) && ( $handle = opendir($CONFIG['logging_destination']) ) ) {
		$cutOff = $_SERVER['REQUEST_TIME'] - ($CONFIG['tmp_cleanup_logs'] * 86400);
		while ( ( $file = readdir($handle) ) !== false ) {
			if ( $file[0] == '.' ) {
				continue;
			}
			$path = $CONFIG['logging_destination'] . $file;
			if ( filemtime($path) > $cutOff ) {
				continue;
			}
			unlink($path);
		}
		closedir($handle);
	}
}