<?php 
	session_start();
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$fileToAmp = substr(basename(__FILE__), 4);
	include_once 'ampify.php';
?>
