<?php
namespace YodleeSDK;
class userSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/login";	
	private $username;
	private $password;
    	private  $cobSessionToken;
    	private  $expires;

 	public function __construct($username,$password,$cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
 		$this->token = null;
 		$this->expires = null;
 		$this->username = $username;
 		$this->password = $password;
 		$this->cobSessionToken = $cobSessionToken;
 	}

 	public function getToken(){
 		return ($this->is_expired())? $this->refresh() : $this->token;
 	}


	private function is_expired(){
		return ((date("U")-3600000) > $this->expires || $this->token == null)? true : false;//1 hour/3600000MS
	}

	private function refresh(){
    		$data = array();
			$data['login'] = $this->username;
			$data['password'] = $this->password;				
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();				

		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$this->endpoint, $data);
		try {
			if(isset($response['userContext']['conversationCredentials']['sessionToken'])){
				$this->token = $response['userContext']['conversationCredentials']['sessionToken'];
				$this->expires= date("U");
				return $this->token;	
			}
			else {
				throw new Exception($response['Error'][0]['errorDetail']);
			}
		}catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	} 
}