<?php

/**
 *	ReadMe.php
 *	for Dropleps
 */
 
$html = file_get_contents( dirname(__FILE__)."/readme.html" );
$html = str_replace(
	"{{ url }}",
	$_GET['url'],
	$html
);

echo $html;
?>