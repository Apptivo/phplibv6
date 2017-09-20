<?php
function updateOrAddCustomAttribute($inputObj,$customAttributeArray) {
	//We loop through the current attirbutes to see if the new one exists, and update if found, or insert if not found
	logIt('Starting function updateorAddCustomAttribute to check for updates on attributeId='.$customAttributeArray['customAttributeId']);
	$resultCode = 0;
	foreach($inputObj->customAttributes as $curAttribute) {	
		if($curAttribute->customAttributeId == $customAttributeArray['customAttributeId']) { 
			if($curAttribute->customAttributeValue == $customAttributeArray['customAttributeValue']) {
				$resultCode = 2;
			}else{
				switch (strtolower($customAttributeArray['customAttributeType'])) {
					case 'select':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						$inputObj->customAttributes[$count]->customAttributeValueId = $customAttributeArray['customAttributeValueId'];
					break;
					case 'date':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue'];
						$inputObj->customAttributes[$count]->customAttributeValueId = $customAttributeArray['customAttributeValueId'];
					break;
					case 'number':
						$inputObj->customAttributes[$count]->customAttributeValue = $customAttributeArray['customAttributeValue']."";
						$inputObj->customAttributes[$count]->numberValue = $customAttributeArray['customAttributeValue'];
					break;
				}
				$resultCode = 1;
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

function logIt($logText,$output = false) {
	global $debugMode;
	if ($debugMode == 'print') {
		$GLOBALS['allLogText'] = $GLOBALS['allLogText'].date('Y-m-d h:i:s').': '.$logText.'<br>';
		print date('Y-m-d h:i:s').': '.$logText.'<br>';
	}else{
		//assume log
		$GLOBALS['allLogText'] = $GLOBALS['allLogText'].date('Y-m-d h:i:s').': '.$logText.PHP_EOL;
		if($output) {
			$logfile = file_put_contents ('padEmailNew.log.txt',$GLOBALS['allLogText'],FILE_APPEND);
			print $GLOBALS['allLogText'];
			$GLOBALS['allLogText'] = '';
		}
	}
}
?>