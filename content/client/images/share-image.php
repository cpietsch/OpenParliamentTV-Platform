<?php
header("Content-type: image/png;");
require_once(__DIR__."/../../../modules/media/include.media.php");
require_once(__DIR__."/../../../modules/image-quote/functions.php");

$quote = '';
if (isset($_REQUEST['t']) && isset($_REQUEST['f'])) {
	$quote = getQuoteFromRequestParams($_REQUEST['t'], $_REQUEST['f'], $textContentsHTML);
}
$author = $mainSpeaker['attributes']['label'];
if (isset($mainFaction['attributes']['labelAlternative'])) {
	$authorSecondary = $mainFaction['attributes']['labelAlternative'].' | '.$formattedDate;
} else {
	$authorSecondary = $formattedDate;
}
renderImageQuote($_REQUEST['c'], $quote, $author, $authorSecondary);

?>