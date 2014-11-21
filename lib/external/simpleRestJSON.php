<?php
namespace Yodlee;
class SimpleRestJSON{
	private $ch;
	public function __construct(){
		$this->ch = curl_init();
	}

	public function post($endpoint, $data){		
		curl_setopt($this->ch, CURLOPT_URL, $endpoint);		
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($this->ch);
		curl_close($this->ch);
		return json_decode($response,true);
	}

	public function get($endpoint, $data){		
		curl_setopt($this->ch, CURLOPT_URL, $endpoint.'?' .http_build_query($data));		
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Accept: application/json')                                                                       
		);  		
		$response = curl_exec($this->ch);
		curl_close($this->ch);
		return json_decode($response,true);
	}
}