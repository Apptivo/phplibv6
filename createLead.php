<?php
// *****START CONFIGURATION*****
	//You'll need to go into this file and modify it to change your API keys
	include(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'glocialtech.config.php');
	$configData = getConfig();
	$GLOBALS['debugMode'] = 'print'; //log or print
	//Apptivo API credentials, sample employee provided who we'll make API calls on behalf of
	$api_key = $configData['api_key'];
	$access_key = $configData['access_key'];
	$user_name = $configData['user_name'];
	$logFile = 'createLead.log.txt';
	$GLOBALS['allLogText'] = '';
	$GLOBALS['allLogTextHtml'] = '';
// *****END CONFIGURATION*****
// Initialize the apptivo_toolset object
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.apptivo.php');
$apptivoApi = new apptivoApi($api_key, $access_key, $user_name);
//Load common functions
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'commonFunctions.php');

//We'll manually build the data.  This would come from a web form, or some other data source you plug in.
$companyName = 'Level Construction';
logIt('Starting script to generate lead for companyName:'.$companyName,true,$logFile);
$firstName = 'Ginny';
$lastName = 'Lee';
$description = 'I would be interested in learning more about your services.  Could you please give me a call as soon as you get this?';
//assigneeToObjectRefName & assigneeToObjectRefId must be replaced with the employee details from your firm.  
$assigneeToObjectRefName = 'Kenny Clark';
$assigneeToObjectRefId = 57882;
//leadStatus and leadStatusMeaning must be replaced with the values configured in your firm
$leadStatus = '1';
$leadStatusMeaning = 'New';
//statusName and statusId must be replaced with the values configured in your firm
$leadSource = '9';
$leadSourceMeaning = 'Web Site';
//Phone numbers are passed in an array.  You'll need to get phoneType & phoneType code from your configuration.  Common to just hard-code the type values.
$phoneNumber = '(721) 685-2342';
$phoneType = 'Business';
$phoneTypeCode = 'PHONE_BUSINESS';

//Note that we're building the array with an empty customAttributes array.  We can use a common method below to insert the JSON for specific attributes.  This makes it easier when dealing with a lot of dynamic ids/values
//Another note, this does not include all standard fields.  For example you might want to include the territory or address of a lead.
$objectData = Array(
	'companyName' => urlencode($companyName),
	'firstName' => urlencode($firstName),
	'lastName' => urlencode($lastName),
	'assigneeObjectRefName' => urlencode($assigneeToObjectRefName),
	'assigneeObjectRefId' => $assigneeToObjectRefId,
	'assigneeObjectId' => 8,
	'leadStatus' => $leadStatus,
	'leadStatusMeaning' => urlencode($leadStatusMeaning),
	'leadSource' => $leadSource,
	'leadSourceMeaning' => urlencode($leadSourceMeaning),
	'phoneNumbers' => Array (
		Array (
			'phoneNumber' => urlencode($phoneNumber),
			'phoneTye' => urlencode($phoneType),
			'phoneTypeCode' => $phoneTypeCode,
			'id' => 'cust_phone_input'
		)
	),
	'customAttributes' => Array (
	)
);
//We can call this function as many times as we need to continue adding to the objectData.  This function also works to ensure the value doesn't already exist, and would replace the existing value is found.
$customAttributeArray = Array (
	'customAttributeId' => 'select_1511810620601_665_725061511810620601_627',
	'customAttributeValue' => 'Warm',
	'customAttributeType' => 'select',
	'customAttributeTagName' => 'select_1511810620601_263_678051511810620601_464',
	'customAttributeName' => 'select_1511810620601_263_678051511810620601_464',
	'fieldType' => 'NUMBER',
	'select_1511810620601_263_678051511810620601_464' => '',
	'customAttributeValueId' => 'VALUE_1511810781707_571'
);
//Return value here is just the updated array for us to use
$updateResponse = updateOrAddCustomAttribute($objectData,$customAttributeArray);
$objectData = $updateResponse['returnObj'];

//Now call the API method to save the lead
$newLead = $apptivoApi->save('leads',json_encode($objectData));

if($newLead) {
	logIt('Successfully created a new lead for company'.$newLead->companyName,true,$logFile);
}else{
	logIt('Something went wrong, we failed to create the new lead',true,$logFile);
}

?>