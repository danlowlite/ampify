<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		include_once 'i_meta.html';
		include_once 'i_styles.html';
		$serverName = "http://" . $_SERVER['SERVER_NAME'] . "/";
		$currentFileToAmp = "amp_" . basename(__FILE__);
		echo "\n".'<link rel="amphtml" href="' . $serverName . $currentFileToAmp . '">'."\n";
	?>
	<meta name="author" content="Author Name" />
	<meta name="title" content="Title Of Item" />
	<meta name="genre" content="Genre" />
	<meta name="keywords" content="Keywords" />
	<meta name="image" content="images/small_flag.png" />
	<meta name="description" content="A short description." />
	<title>Title of Item</title>
</head>
<body id="foo" class="bar">
	<div id="content">
	<?php
		include_once 'i_storynav.html';
	?>
		<div id="words">
		
		</div>
	</div>
	<?php
		include_once 'i_storyfooter.html';
	?>
</body>
</html>