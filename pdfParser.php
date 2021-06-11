<?php

include "vendor/autoload.php";

$parser = new \Smalot\PdfParser\Parser();
$file = "/Users/james/Desktop/remotex.pdf";
$pdf = $parser->parseFile($file);

$text = $pdf->getText();
$pages = $pdf->getPages();

foreach ($pages as $page) {
	echo $page->getText();
	echo "\n\nlength of page: " . strlen($page->getText()) . "\n";

}

echo "\n\nlength of text: " . strlen($text) . "\n";
//parse the darn thing
$arr_text = str_split($text);
echo "length of array: " . count($arr_text) . "\n";
sleep(5);

$block = [];
$phrase = '';
$regex = "/\.|\,|\?|\:|\;|\!/";

foreach($arr_text as $char) {
	//stop at normal punctuation marks TODO
	//need to set context of each phrase and paragraph TODO
	//need to build library of keywords, nouns, etc TODO
	if(preg_match($regex,$char)) {
		echo "\n";
		//store phrase
		$block[] = $phrase;
		echo "\n" . $phrase . "\n";
		$phrase = '';
		echo "finished phrase\n";

	}
	//need to deal with whitespace TODO
	echo $char ;
	//dump quotes
	$char = preg_replace("/\"|\'/", '', $char);
	$phrase .= $char;
}

//clean block
foreach($block as $index => $line) {
	$line = trim($line);
	$line = preg_replace_callback("/$line/",mygregexhandler,$line);
	$block[$index] = $line;
}
system('clear');

foreach($block as $line) {
	echo $line . "\n";
	sleep(1);
}

function myregexhandler($matches) : string {

    $line = $matches[0];
    $regex = "/\.|\,|\?|\:|\;|\!/";
	$line = preg_replace($regex, '',$line);
	$line = preg_replace("/\"|\'|\t|\-|^\s|^com|etc|^Page|^2021.*|^\)/", '',$line);
	$line = preg_replace("/\s\s/", " " , $line);
	$line = preg_replace("/reemotex/i", 'Reemotex.com',$line);
	$line = preg_replace("/^\s|^\s\s/", "" , $line);
	return $line;

}
