<?php
function logIt($logText,$output = false, $filePath = 'general.log.txt') {
	global $debugMode;
	$GLOBALS['allLogText'] = $GLOBALS['allLogText'].date('Y-m-d h:i:s').': '.$logText.PHP_EOL;
	$GLOBALS['allLogTextHtml'] = $GLOBALS['allLogTextHtml'].date('Y-m-d h:i:s').': '.$logText.'<br>';
	if($output) {
		$logfile = file_put_contents ($filePath,$GLOBALS['allLogText'],FILE_APPEND);
		$GLOBALS['allLogText'] = '';
	}
	if ($debugMode != 'logOnly') {
		print $GLOBALS['allLogTextHtml'];
		$GLOBALS['allLogTextHtml'] = '';
	}
}

?>