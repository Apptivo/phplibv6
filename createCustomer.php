<?php
// *****START CONFIGURATION*****
	include(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'glocialtech.config.php');
	$configData = getConfig();
	$GLOBALS['debugMode'] = 'print'; //log or print
	//Apptivo API credentials, sample employee provided who we'll make API calls on behalf of
	$api_key = $configData['api_key'];
	$access_key = $configData['access_key'];
	$user_name = $configData['user_name'];
	$logFile = 'createCustomer.log.txt';
	$GLOBALS['allLogText'] = '';
	$GLOBALS['allLogTextHtml'] = '';
// *****END CONFIGURATION*****
// Initialize the apptivo_toolset object
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.apptivo.php');
$apptivoApi = new apptivoApi($api_key, $access_key, $user_name);
//Load common functions
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'commonFunctions.php');

//We'll manually build the data.  This would come from a web form, or some other data source you plug in.
$customerName = 'Audio Pros2';
logIt('Starting script to generate customer for customerName:'.$customerName,true,$logFile);

//assigneeToObjectRefName & assigneeToObjectRefId must be replaced with the employee details from your firm.  
$assigneeToObjectRefName = 'API User';
$assigneeToObjectRefId = 78290;
//customerCategoryName and customerCategoryId must be replaced with the values configured in your firm
$customerCategory = 'Retail';
$customerCategoryId = 33726;
//statusName and statusId must be replaced with the values configured in your firm
$statusName = 'Prospect';
$statusId = 10002;
//Phone numbers are passed in an array.  You'll need to get phoneType & phoneType code from your configuration.  Common to just hard-code the type values.
$phoneNumber = '(855) 444-2342';
$phoneType = 'Business';
$phoneTypeCode = 'PHONE_BUSINESS';

//Note that we're building the array with an empty customAttributes array.  We can use a common method below to insert the JSON for specific attributes.  This makes it easier when dealing with a lot of dynamic ids/values
//Another note, this does not include all standard fields.  For example you might want to include the territory or address of a customer.
$objectData = Array(
	'customerName' => urlencode($customerName),
	'assigneeObjectRefName' => urlencode($assigneeToObjectRefName),
	'assigneeObjectRefId' => $assigneeToObjectRefId,
	'assigneeObjectId' => 8,
	'customerCategory' => urlencode($customerCategory),
	'customerCategoryId' => $customerCategoryId,
	'statusName' => urlencode($statusName),
	'statusId' => $statusId,
	'phoneNumber' => Array (
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
	"customAttributeId" => "check_1503034023492_552_1255031503034023492_857",
	"customAttributeValue" => "",
	"customAttributeType" => "check",
	"customAttributeTagName" => "right_check_1503034896243_777_1163071503034896243_204",
	"customAttributeName" => "right_check_1503034896243_777_1163071503034896243_204",
	"fieldType" => "NUMBER",
	"right_check_1503034896243_777_1163071503034896243_204" => "",
	"attributeValues" => Array(
		Array (
			"attributeId" => "right_check_1503034896243_992_1166451503034896243_484",
			"attributeValue" => urlencode("Web Design"),
			"shape" => "",
			"color" => "",
		),
		Array(
			"attributeId" => "right_check_1503034897640_897_1156921503034897640_479",
			"attributeValue" => urlencode("IT Services"),
			"shape" => "",
			"color" => "",
		),
		Array(
			"attributeId" => "right_check_1503034900642_490_1014151503034900642_315",
			"attributeValue" => urlencode("Graphic Design"),
			"shape" => "",
			"color" => "",
		),
	)
);
//Return value here is just the updated array for us to use
$updateResponse = updateOrAddCustomAttribute($objectData,$customAttributeArray);
$objectData = $updateResponse['returnObj'];

//Now call the API method to save the customer
$newCustomer = $apptivoApi->save('customers',json_encode($objectData));

if($newCustomer) {
	logIt('Successfully created a customer with customerId:'.$newCustomer->customer->customerId,true,$logFile);
}else{
	logIt('Something went wrong, we failed to create the new customer',true,$logFile);
}

?>