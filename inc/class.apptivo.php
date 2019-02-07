<?php
/* ABOUT THIS FILE 
   This is a general class that contains methods commonly used when interacting with the Apptivo API.
*/
class apptivoApi
{
	public $api_key = 'null';
	public $access_key = 'null';
	public $user_name_str = 'null';
	public $ch;
	//Constructor sets the api/access keypair.  Also constructs the curl object so we can start making API requests.  Will destroy curl object on destruct.
	function __construct($input_apikey, $input_accesskey, $user_name) {
		$this->api_key = $input_apikey;
		$this->access_key = $input_accesskey;
		if($user_name) {
			$this->user_name_str = '&userName='.$user_name;
		}
		// Basic curl implementation.  This can be further secured in future.
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_HEADER, 0);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER,   0);
		curl_setopt($this->ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	}
	function __destruct()
	{
		curl_close($this->ch);
	}
	function getConfigData($app)
	{
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://api.apptivo.com/app/dao/v6/'.$objParams['objectUrlName'].'?a=getConfigData&objectId='.$objParams['objectId'].'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		return $api_result;
	}
	//Use save for creating new records, and update for changing existing records
	function save($app,$objectData,$extraParams = '') {
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://www.apptivo.com/app/dao/v6/'.$objParams['objectUrlName'].'?a=save&'.$objParams['objectDataName'].'='.$objectData.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function update($app, $objectId, $attributeName, $objectData, $extraParams) {
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://www.apptivo.com/app/dao/v6/'.$objParams['objectUrlName'].'?a=update&'.$objParams['objectIdName'].'='.$objectId.'&attributeName='.$attributeName.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url.'    with post data: '.http_build_query(array($objParams['objectDataName'] => $objectData)),true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		curl_setopt($this->ch,CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($this->ch,CURLOPT_POSTFIELDS, http_build_query(array($objParams['objectDataName'] => $objectData)));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getById($app,$objectId) {
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://www.apptivo.com/app/dao/v6/'.$objParams['objectUrlName'].'?a=getById&'.$objParams['objectIdName'].'='.$objectId.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key;
		$logfile = file_put_contents ('api.log.txt',date('Y-m-d h:i:s').': '.$api_url.PHP_EOL,FILE_APPEND);
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getAllBySearchText($app,$searchText,$extraParams = '') {
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://www.apptivo.com/app/dao/v6/'.$objParams['objectUrlName'].'?a=getAllBySearchText&searchText='.urlencode($searchText).$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		$logfile = file_put_contents ('api.log.txt',date('Y-m-d h:i:s').': '.$api_url.PHP_EOL,FILE_APPEND);
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getAllByAdvancedSearch($app,$searchData,$extraParams = '') {
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://www.apptivo.com/app/dao/v6/'.$objParams['objectUrlName'].'?a=getAllByAdvancedSearch&searchData='.$searchData.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key;
		$logfile = file_put_contents ('api.log.txt',date('Y-m-d h:i:s').': '.$api_url.PHP_EOL,FILE_APPEND);
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getCustomerContacts($customerId,$extraParams = '') {
		$api_url = 'https://www.apptivo.com/app/dao/v6/customers?a=getCustomerContacts&customerId='.$customerId.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getActivitiesByAdvancedSearch($activityType,$searchData,$isFromApp = 'home',$startIndex = 0,$numRecords = 50,$extraParams = '') {
		$api_url = 'https://www.apptivo.com/app/dao/activities?a=getActivitiesByAdvancedSearch&activityType='.$activityType.'&searchData='.$searchData.'&isFromApp='.$isFromApp.'&startIndex='.$startIndex.'&numRecords='.$numRecords.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function createActivity($activityType,$activityData, $actType = 'home', $extraParams = '') {
		//Set the standard parameters to be used in the URL below
		switch($activityType) {
			case 'Follow Up':
				$methodName = 'createFollowUpActivity';
				$dataStr = '&followUpData='.$activityData;
			break;
		}
		$api_url = 'https://www.apptivo.com/app/dao/activities?a='.$methodName.$dataStr.'&actType='.$actType.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function deleteActivity($activityId,$objectId) {
		$api_url = 'https://www.apptivo.com/app/dao/activities?a=deleteActivity&activityId='.$activityId.'&objectId='.$objectId.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getAllByCustomView($app,$viewCode,$startIndex=0,$numRecords=0,$extraParams = '') {
		$objParams = $this->getAppParamters($app);
		$api_url = 'https://api.apptivo.com/app/dao/v5/appsettings?a=getAllByCustomView&objectId='.$objParams['objectId'].'&startIndex='.$startIndex.'&numRecords='.$numRecords.$extraParams.'&viewCode='.$viewCode.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function createNote($noteDetails) {
		$api_url = 'https://api.apptivo.com/app/dao/note?a=createNote&noteDetails='.json_encode($noteDetails).'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		logIt($api_url,true,'api.log.txt');
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
	function getIdFromLeadSourceName($leadSourceName) {
		$leadsConfig = json_decode($this->getConfigData('leads'));
		foreach($leadsConfig->leadSources as $curSource) {
			if(strtolower($leadSourceName) == strtolower($curSource->name)) {
				$leadSourceId = $curSource->id;
			}	
		}
		return $leadSourceId;
	}
	
	function getAppParamters($app) {
		//Set the standard parameters to be used in the URL below
		switch(strtolower($app)) {
			case 'cases':
				$objParams = Array(
					'objectUrlName' => 'cases',
					'objectDataName' => 'caseData',
					'objectIdName' => 'caseId'
				);
			break;
			case 'contacts':
				$objParams = Array(
					'objectUrlName' => 'contacts',
					'objectDataName' => 'contactData',
					'objectIdName' => 'contactId',
					'objectId' => 2
				);
			break;
			case 'customers':
				$objParams = Array(
					'objectUrlName' => 'customers',
					'objectDataName' => 'customerData',
					'objectIdName' => 'customerId',
					'objectId' => 3
				);
			break;
			case 'estimates':
				$objParams = Array(
					'objectUrlName' => 'estimates',
					'objectDataName' => 'estimateData',
					'objectIdName' => 'estimateId',
					'objectId' => 155
				);
			break;
			case 'invoices':
				$objParams = Array(
					'objectUrlName' => 'invoice',
					'objectDataName' => 'invoiceData',
					'objectIdName' => 'invoiceId',
					'objectId' => 33
				);
			break;
			case 'leads':
				$objParams = Array(
					'objectUrlName' => 'leads',
					'objectDataName' => 'leadData',
					'objectIdName' => 'leadId',
					'objectId' => 4
				);
			break;
			case 'opportunities':
				$objParams = Array(
					'objectUrlName' => 'opportunities',
					'objectDataName' => 'opportunityData',
					'objectIdName' => 'opportunityId',
					'objectId' => 11
				);
			break;
			case 'orders':
				$objParams = Array(
					'objectUrlName' => 'orders',
					'objectDataName' => 'orderData',
					'objectIdName' => 'orderId',
					'objectId' => 12
				);
			break;
			case 'projects':
				$objParams = Array(
					'objectUrlName' => 'projects',
					'objectDataName' => 'projectData',
					'objectIdName' => 'projectId',
					'objectId' => 88
				);
			break;
		}
		return $objParams;
	}
}

function updateOrAddCustomAttribute($inputObj,$customAttributeArray, $mode = 1) {
	//We loop through the current attributes to see if the new one exists, and update if found, or insert if not found
	//Mode is either 1 or 2.
	//Mode 1 indicates that we want to replace any value that is not exactly the same. 
	//Mode 2 indicates that we only want to update the value if nothing previously exists
	//The resultCodes are 1 or 2.
	//Result 1 means we should update the attribute
	//Result 2 means we should not update the attribute
	logIt('Starting function updateorAddCustomAttribute to check for updates on attributeId='.$customAttributeArray['customAttributeId']);
	$resultCode = 0;
	$count = 0;
	//For backwards compatibility we'll check if the inputObj is an array or object, then convert if to and object if not.  Old examples passed this in as an array, producing warnings.
	if(!is_object($inputObj)) {
		$inputObj = (object) $inputObj;
	}
	foreach($inputObj->customAttributes as $curAttribute) {	
		if($curAttribute->customAttributeId == $customAttributeArray['customAttributeId']) { 
			logIt('customAttributeId='.$curAttribute->customAttributeId.'   and    customAttributeValue='.$curAttribute->customAttributeValue,true);
			if($mode == 2 && strlen($curAttribute->customAttributeValue) > 1) {
				$resultCode = 2;
			}elseif($mode == 2 && strlen($curAttribute->customAttributeValue) < 3) {
				$resultCode = 1;
			}elseif($curAttribute->customAttributeValue == $customAttributeArray['customAttributeValue'] || urlencode($curAttribute->customAttributeValue) == $customAttributeArray['customAttributeValue']) {
				$resultCode = 2;
				logIt('existing value matches the new value, resultCode 2',true);
			}else{
				$resultCode = 1;
				logIt('No matching exceptions, this is a new value.  resultCode 1',true);
			}
			if($resultCode == 1) {
				logIt('resultCode is 1, setting attribute values',true);
				switch (strtolower($customAttributeArray['customAttributeType'])) {
					case 'select':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						$inputObj->customAttributes[$count]->customAttributeValueId = $customAttributeArray['customAttributeValueId'];
					break;
					case 'date':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						if($inputObj->customAttributes[$count]->customAttributeValueId) {
							unset($inputObj->customAttributes[$count]->customAttributeValueId);
						}
					break;
					case 'number':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						$inputObj->customAttributes[$count]->numberValue = $customAttributeArray['customAttributeValue'];
					break;
					case 'currency':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						$inputObj->customAttributes[$count]->numberValue = $customAttributeArray['customAttributeValue'];
						$inputObj->customAttributes[$count]->currencyCode = $customAttributeArray['currencyCode'];
					break;
					case 'reference':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						if($customAttributeArray['employeeId']) {
							$inputObj->customAttributes[$count]->employeeId = $customAttributeArray['employeeId'];
							$inputObj->customAttributes[$count]->employeeName = $customAttributeArray['employeeName'];
						}
					break;
					case 'input':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
					break;
					default:
						logIt('ERROR: function updateOrAddCustomAttribute found an attribute type that was not supported.',true);
						die();
				}
			}
		}
		$count = $count + 1;
	}
	if($resultCode == 0) {
		array_push($inputObj->customAttributes,$customAttributeArray);
		$resultCode = 1;
	}
	return array(
		'returnObj' => $inputObj,
		'resultCode' => $resultCode
	);
} 
?>