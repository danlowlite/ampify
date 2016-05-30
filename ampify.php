<?php 
	session_start();
	if (!isset($fileToAmp)) {
		$htmlOut = 'This is an error. There was no referenced file. Please return to the page you came from and try again.';
		echo $htmlOut; 
		exit;
	}
	$serverName = "http://" . $_SERVER['SERVER_NAME'] . "/";
// get schema data
	$tags = get_meta_tags($fileToAmp);
	$pageDescription = $tags['description'];
	$pageGenre = $tags['genre'];	
	$pageTitle = $tags['title'];
	$pageAuthor = $tags['author'];
	$pageKeywords = str_Replace(' ', ', ', $tags['keywords']); // original has only spaces, no commas...schema requires commas
// start to make the template for our amp page. This is more of the HEAD section
	$htmlOut = '<!doctype html>'."\n".'<html amp lang="en">'."\n".'<head>'."\n".'<meta charset="utf-8">'."\n";
	$htmlOut .= '<title>' . $pageTitle . '</title>'."\n";
	$htmlOut .= '<link rel="canonical" href="' . $serverName . $fileToAmp . '" >'."\n";
    $htmlOut .= '<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">'."\n";
    $htmlOut .= '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>'."\n";
// output schema data 
    $htmlOut .=  '<script type="application/ld+json">{"@context": "http://schema.org",'."\n";
    $htmlOut .= '"@type": "CreativeWork",'."\n";
    $htmlOut .= '"creator": "' . $pageAuthor . '",'."\n";    
    $htmlOut .= '"name": "' . $pageTitle . '", '."\n";
    $htmlOut .= '"headline": "' . $pageTitle . '", '."\n";
    $htmlOut .= '"description": "' . $pageDescription . '", '."\n";
    $htmlOut .= '"keywords": "' . $pageKeywords . '", '."\n";
    $htmlOut .= '"genre": "' . $pageGenre . '"'."\n"; 	
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