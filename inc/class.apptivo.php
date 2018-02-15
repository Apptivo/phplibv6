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
	function save($app,$objectData,$extraParams = '') {
		//Set the standard paramters to be used in the URL below
		switch($app) {
			case 'customers':
				$objectUrlName = 'customers';
				$objectDataName = 'customerData';
			break;
			default:
			
			break;
		}
		
		$api_url = 'https://www.apptivo.com/app/dao/v6/'.$objectUrlName.'?a=save&'.$objectDataName.'='.$objectData.$extraParams.'&apiKey='.$this->api_key.'&accessKey='.$this->access_key.$this->user_name_str;
		$logfile = file_put_contents ('log.txt',date('Y-m-d h:i:s').': '.$api_url.PHP_EOL,FILE_APPEND);
		curl_setopt($this->ch,CURLOPT_URL, $api_url);
		$api_result = curl_exec($this->ch);
		$api_response = json_decode($api_result);	
		return $api_response;
	}
}
?>