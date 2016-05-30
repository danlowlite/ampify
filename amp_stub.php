<?php 
	session_start();
//call with the filename included as the project, but we'll start with a known one.
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$fileToAmp = substr(basename(__FILE__), 4);
	include_once 'ampify.php';
?>