<?php 
	session_start();
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
	//call with the filename included as the project, but we'll start with a known one.
	//celia.php
	$fileToAmp = 'celia.php'; //The included function in the file looks for a KEY phrase and doesn't allow for infinite loops. Hopefully. maybe it should be a session variable instead? should also call for entire URL so that this file can be cached elsewhere
	
/* get schema data */
	$tags = get_meta_tags($fileToAmp);
	$pageDescription = $tags['description'];
	$pageGenre = $tags['genre'];	
	$pageTitle = $tags['title'];
	$pageKeywords = str_Replace(' ', ', ', $tags['keywords']); // original has only spaces, no commas...schema requires commas

/* start to make the template for our amp page. */
	$htmlOut = '<!doctype html>'."\n".'<html amp lang="en">'."\n".'<head>'."\n".'<meta charset="utf-8">'."\n";
	$htmlOut .= '<title>' . $pageTitle . '</title>'."\n";
	$htmlOut .= '<link rel="canonical" href="' . $fileToAmp . '" >'."\n";
    $htmlOut .= '<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">'."\n";
    $htmlOut .= '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>'."\n";

/* output schema data */
    $htmlOut .=  '<script type="application/ld+json">{"@context": "http://schema.org","@type": "CreativeWork","creator": "Daniel Robert Maurer",';    
    $htmlOut .= '"name": "' . $pageTitle . '", ';
    $htmlOut .= '"headline": "' . $pageTitle . '", ';
    $htmlOut .= '"description": "' . $pageDescription . '", ';
    $htmlOut .= '"keywords": "' . $pageKeywords . '", ';
    $htmlOut .= '"genre": "' . $pageGenre . '"'; 	
 	$htmlOut .= '}</script>'."\n"; 
    
/* any custom style goes here. work on getting an include here?*/
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
/* required AMP scripting */
    $htmlOut .= '<script async src="https://cdn.ampproject.org/v0.js"></script></head>'."\n";
    $htmlOut .= '<body>';

	$htmlBody = file_get_contents($fileToAmp);

	//remove the title
	$titleEnd = strpos($htmlBody, '</title>') + 8;
	$htmlBody = substr($htmlBody, $titleEnd);
	$htmlBody = trim($htmlBody);

	//remove tags, leave the good ones
	$htmlBody = strip_tags($htmlBody, '<img><p><br><span><em><strong><h1><h2><h3><h4><blockquote>');

	//replace some tags this way because it's a simple swap.
	$htmlBody = str_replace('<br />', '<br>', $htmlBody);
	$htmlBody = str_replace('<img', '<amp-img layout="responsive" ', $htmlBody);
	$htmlBody = str_replace(' />', '></amp-img>', $htmlBody);

    //export the body stuff

	$htmlOut .= $htmlBody."\n";

	$htmlOut .= '</body>';
  	$htmlOut .= '</html>'; //all done!  
  	echo $htmlOut; //and we're out.
?>
