<?php

$f = "counter.txt";
$counter_point = fopen($f,"a+");
$counter = fread($counter_point,600);

if ($_GET["count"]==true) {
	fwrite($counter_point,"1");
} else {
	$count = substr_count($counter,"1");
	echo'<!DOCTYPE html><html><head><title>'.$count.'/600 Frames</title><style>div{font-size:80pt;font-family:Helvetica Neue,Helvetica LT Std,Helvetica,Lucida Sans,Arial,sans-serif;line-height:.1;text-align:center}div:before{content:"";line-height:.8}span{font-size:25%}</style><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/></head><body><div>'.$count.'/600<br><span>FRAMES RENDERED</span></div></body></html>';
}

fclose($counter_point);

?>