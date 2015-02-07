<?php 
namespace YodleeSDK;
class cobSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/coblogin";	

 	public function __construct(){
 		$_SESSION['YodleeSDK_cobSessionToken_expire']= (!isset($_SESSION['YodleeSDK_cobSessionToken_expire']))? null : $_SESSION['YodleeSDK_cobSessionToken_expire'];
 		$_SESSION['YodleeSDK_cobSessionToken']= (!isset($_SESSION['YodleeSDK_cobSessionToken']))? null : $_SESSION['YodleeSDK_cobSessionToken'];
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
		$this->refresh();

 	}

 	public function getToken(){
 		return ($this->is_expired())? $this->refresh() :  $_SESSION['YodleeSDK_cobSessionToken'];
 	}


	private function is_expired(){
		return ((date("U")-3600000) > $_SESSION['YodleeSDK_cobSessionToken_expire'] || !isset($_SESSION['YodleeSDK_cobSessionToken']))? true : false;//1 hour/3600000MS
	}

	private function refresh(){
    		$data = array();
			$data['cobrandLogin'] = $GLOBALS['YodleeConfig']->COBUsername;
			$data['cobrandPassword'] = $GLOBALS['YodleeConfig']->COBPassword;				
		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$this->endpoint, $data);
		try {
			if(isset($response['cobrandConversationCredentials']['sessionToken'])){
				$_SESSION['YodleeSDK_cobSessionToken'] = $response['cobrandConversationCredentials']['sessionToken'];				
				$_SESSION['YodleeSDK_cobSessionToken_expire'] = date("U");
				return $_SESSION['YodleeSDK_cobSessionToken'];					
			}
			else {
				throw new Exception($response['Error'][0]['errorDetail']);
			}
		}catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	} 
}