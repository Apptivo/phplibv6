<?php
// *****START CONFIGURATION*****
	include(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'glocialtech.config.php');
	$configData = getConfig();
	$GLOBALS['debugMode'] = 'print'; //log or print
	//Apptivo API credentials, sample employee provided who we'll make API calls on behalf of
	$api_key = $configData['api_key'];
	$access_key = $configData['access_key'];
	$user_name = $configData['user_name'];
	$logFile = 'getAllEstimates.log.txt';
	$GLOBALS['allLogText'] = '';
	$GLOBALS['allLogTextHtml'] = '';
// *****END CONFIGURATION*****
// Initialize the apptivoApi object
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.apptivo.php');
$apptivoApi = new apptivoApi($api_key, $access_key, $user_name);
//Load common functions
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'commonFunctions.php');

//We can retrieve all estimates in a few ways.  The easiest is just to use GetAllBySearchText, but do not pass in any search parameters.  You can also use getAllByAdvancedSearch
$response = $apptivoApi->getAllBySearchText('estimates','');


logIt('We just retrieved '.$response->countOfRecords.' estimates',true,$logFile);
logIt('The JSON for the first estimate is: '.json_encode($response->data[0]),true,$logFile);


?>