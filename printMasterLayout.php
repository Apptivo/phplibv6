<?php
// *****START CONFIGURATION*****
	include(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'th.config.php');
	$configData = getConfig();
	$GLOBALS['debugMode'] = 'print'; //log or print
	//Apptivo API credentials, sample employee provided who we'll make API calls on behalf of
	$api_key = $configData['api_key'];
	$access_key = $configData['access_key'];
	$user_name = $configData['user_name'];
	$logFile = 'printMasterLayout.log.txt';
	$GLOBALS['allLogText'] = '';
	$GLOBALS['allLogTextHtml'] = '';
	$app = 'customers'; //This determines which app we want to output.
// *****END CONFIGURATION*****
// Initialize the apptivo_toolset object
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.apptivo.php');
$apptivoApi = new apptivoApi($api_key, $access_key, $user_name);
//Load common functions
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'commonFunctions.php');

$configJson = $apptivoApi->getConfigData($app);
$configArr = json_decode($configJson);
$webLayout = $configArr->webLayout;
$layoutSegment = json_decode($webLayout);
$sectionsArr = $layoutSegment->sections;
foreach($sectionsArr as $curSection) {
	print '<br>';
	print '<Strong>Section Name:</strong> '.$curSection->label;
	print '<br>';
	$sectionAttributes = $curSection->attributes;
	foreach($sectionAttributes as $curAttribute) {
		if(isset($curAttribute->addresstagClass)) {
			print '--<strong>Attribute Name:</strong> Address<br>';
		}elseif($curAttribute->label->modifiedLabel) {
			if(isset($curAttribute->isEnabled) && $curAttribute->isEnabled == 'true') {
				$removedString = '';
			}else{
				$removedString = ' <strong>DELETED</strong>';
			}
	
			//If we have a dropdown/toggle switch we need to print values
			if(isset($curAttribute->right[0]->optionValueList)) {
				//We need to print the main attribute + all values
				print '--<strong>Attribute Name:</strong> '.$curAttribute->label->modifiedLabel.$removedString.' | <strong>Attribute ID:</strong> '.$curAttribute->attributeId.' | <strong>Attribute Tag Name: </strong> '.$curAttribute->right[0]->tagName.'<br>';
				foreach($curAttribute->right[0]->optionValueList as $curValue) {
					$valueName = $curValue->optionObject;
					$valueId = $curValue->optionId;
					print '&nbsp;&nbsp;&nbsp;--<strong>Value Name:</strong> '.$valueName.' | <strong>Value ID:</strong> '.$valueId.'<br>';	
				}
			}else{
				//No values needed, print single line
				print '--<strong>Attribute Name:</strong> '.$curAttribute->label->modifiedLabel.$removedString.' | <strong>Attribute ID:</strong> '.$curAttribute->attributeId.'<br>';
			}
			//optionValueList->optionObject / optionId
		}
	}
	print '<br>';
}


/*
Goal of this script is to create a simple text output of the master layout for an app in this format:
Section Name
--Field Name : Attribute ID
----Value Name : Value ID
<br>
Section Name
--Field Name : Attribute ID
----Value Name : Value ID
*/



//Todo items
//Address support
//Display deleted status on values not just attributes
//Display values for standard attributes like customer category

?>