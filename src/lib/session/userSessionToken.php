<?php
namespace YodleeSDK;
class userSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/login";	
	private $username;
	private $password;
    	private  $cobSessionToken;
	private $token = null;

 	public function __construct($username,$password,$cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
 		$this->username = $username;
 		$this->password = $password;
 		$this->cobSessionToken = $cobSessionToken;
 		$this->refresh();
 	}

 	public function getToken(){
 		return $this->token;
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