<?php 
	session_start();
	if (!isset($fileToAmp)) {
		$htmlOut = 'This is an error. There was no referenced file. Please return to the page you came from and try again.';
		echo $htmlOut; 
		exit;
	}
	$serverName = "http://" . $_SERVER['SERVER_NAME'] . "/";
	$imageServerName = "http://" . $_SERVER['SERVER_NAME'] . "/";
// get schema data
	$tags = get_meta_tags($fileToAmp);
	$pageDescription = $tags['description'];
	$pageGenre = $tags['genre'];	
	$pageTitle = $tags['title'];
	$pageAuthor = $tags['author'];
	//there is no creation time in a unix file system, but I think creation time should be the modified time, ideally. Could pass this if we were willing to tag every page.
	$pageUnixTimeModified = filectime($fileToAmp);
	$pageDateCreated = date('Y-m-d\TH:i:sP', $pageUnixTimeModified); //"2016-06-01T00:00:00+00:00"; 24 hour, ISO 8601 time.
	if (isset($tags['image'])) { //if we have an image
		$pageImage = $imageServerName . $tags['image'];	
	} else {
		$pageImage = $imageServerName . "images/about_me1.png";
	}	
	list($width, $height) = getimagesize($pageImage);

	$pageKeywords = str_Replace(' ', ', ', $tags['keywords']); // original has only spaces, no commas...schema requires commas
// start to make the template for our amp page. This is more of the HEAD section
	$htmlOut = '<!doctype html>'."\n".'<html amp lang="en">'."\n".'<head>'."\n".'<meta charset="utf-8">'."\n";
	$htmlOut .= '<title>' . $pageTitle . '</title>'."\n";
	$htmlOut .= '<link rel="canonical" href="' . $serverName . $fileToAmp . '" >'."\n";
    $htmlOut .= '<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">'."\n";
    $htmlOut .= '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>'."\n";
// output schema data 
    $htmlOut .= '<script type="application/ld+json">'."\n";
    $htmlOut .= '{"@context": "http://schema.org",'."\n";
	    $htmlOut .= '"@type": "Article",'."\n";
	    $htmlOut .= '"headline": "' . $pageTitle . '",' . "\n";
	    $htmlOut .= '"datePublished": "' . $pageDateCreated . '",'."\n";
	    $htmlOut .= '"author": ["' . $pageAuthor . '"],'."\n";
	    $htmlOut .= '"image":{'."\n"; //start image thing for the story
	    	$htmlOut .= '"@type": "ImageObject",'."\n";
	    	$htmlOut .= '"url": "' . $pageImage . '",'."\n";
	    	$htmlOut .= '"width": "' . $width . '",'."\n";
			$htmlOut .= '"height": "' . $height . '"},'."\n";
	    $htmlOut .= '"description": "' . $pageDescription . '", '."\n";
	    $htmlOut .= '"publisher":{'."\n";
	    $htmlOut .= '"@type":"Organization",'."\n";
	    $htmlOut .= '"name":"mxdrm",'."\n";
	    $htmlOut .= '"logo":{'."\n";
	    	$htmlOut .= '"@type":"ImageObject",'."\n";
	    	$htmlOut .= '"url":"http://www.danielrobertmaurer.com/images/logo.png",'."\n"; // hardcoded for now
	    	$htmlOut .= '"width":114,'."\n";
	    	$htmlOut .= '"height":60}'."\n";
    $htmlOut .= '},'."\n";
    $htmlOut .= '"url": "' . $serverName . $fileToAmp . '"'."\n";
 	$htmlOut .= '}</script>'."\n"; 
// any custom style goes here. work on getting an include here?
	$htmlOut .= '<link href="https://fonts.googleapis.com/css?family=Special+Elite" rel="stylesheet" type="text/css">'."\n"; //this is allowed
    $htmlOut .= '<style amp-custom>'."\n";
    $htmlOut .= 'p {font-family: Helvetica, Arial, sans-serif;}'."\n";
    $htmlOut .= 'h1, h2, h3, .firstpara:first-letter {font-family: "Special Elite", cursive;}'."\n";
    $htmlOut .= 'h2, h3 {text-align: center;}'."\n";
    $htmlOut .= 'amp-img {background-color: none;}'."\n";
    $htmlOut .= '.caption {display: none;}'."\n"; //I have image captions I am not going to display.
    $htmlOut .= '.poemEpigraph, .authornote {text-align: center;font-style: italic;}'."\n";
    $htmlOut .= '.breakimage {text-align: center; max-width: 150px;}';
    $htmlOut .= '</style>'."\n";
// required AMP scripting 
    $htmlOut .= '<script async src="https://cdn.ampproject.org/v0.js"></script></head>'."\n";
// start the body and start the body content shenannigans
    $htmlOut .= '<body>';
	$htmlBody = file_get_contents($fileToAmp);	
	// title tag is unaffected by strip_tags, so remove the title here
		$titleEnd = strpos($htmlBody, '</title>') + 8; 
		$htmlBody = substr($htmlBody, $titleEnd);
		$htmlBody = trim($htmlBody); // title removed. 
	// remove useless tags, leave ones we'll be using/converting
		$htmlBody = strip_tags($htmlBody, '<img><p><br><span><em><strong><h1><h2><h3><h4><blockquote><sup>');
	//replace in-use tags this way because it's a simple swap.
		$htmlBody = str_replace('<br />', '<br>', $htmlBody);
		$htmlBody = str_replace('<img', '<amp-img layout="responsive" ', $htmlBody);
		$htmlBody = str_replace(' />', '></amp-img>', $htmlBody);
		//likely should need to put in the youtube thing here, too.
//add the body content to the string and finish
		$htmlOut .= $htmlBody."\n";
		$htmlOut .= '</body>';
  		$htmlOut .= '</html>'; 
//all done!  
  	echo $htmlOut; //and we're out.
?>