<?php
namespace YodleeSDK;
class userSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/login";	
	private $username;
	private $password;
    	private  $cobSessionToken;

 	public function __construct($username,$password,$cobSessionToken){
 		$_SESSION['YodleeSDK_userSessionToken_expire'] = (!isset($_SESSION['YodleeSDK_userSessionToken_expire']))? null : $_SESSION['YodleeSDK_userSessionToken_expire'];
 		$_SESSION['YodleeSDK_userSessionToken'] = (!isset($_SESSION['YodleeSDK_userSessionToken']))? null : $_SESSION['YodleeSDK_userSessionToken'];
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
 		$this->username = $username;
 		$this->password = $password;
 		$this->cobSessionToken = $cobSessionToken;
 	}

 	public function getToken(){
 		return ($this->is_expired())? $this->refresh() : $_SESSION['YodleeSDK_userSessionToken'];
 	}


	private function is_expired(){
		return ((date("U")-3600000) > $_SESSION['YodleeSDK_userSessionToken_expire'] || $_SESSION['YodleeSDK_userSessionToken'] == null)? true : false;//1 hour/3600000MS
	}

	private function refresh(){
    		$data = array();
			$data['login'] = $this->username;
			$data['password'] = $this->password;				
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();				

		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$this->endpoint, $data);
		try {
			if(isset($response['userContext']['conversationCredentials']['sessionToken'])){
				$_SESSION['YodleeSDK_userSessionToken'] = $response['userContext']['conversationCredentials']['sessionToken'];
				$_SESSION['YodleeSDK_userSessionToken_expire'] = date("U");
				return $_SESSION['YodleeSDK_userSessionToken'];	
			}
			else {
				throw new Exception($response['Error'][0]['errorDetail']);
			}
		}catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	} 
}