<?php 
namespace Yodlee;
class cobSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/coblogin";	
    	private  $token;
    	private  $expires;

 	public function __construct(){
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
 		$this->token = null;
 		$this->expires = null;
		$this->refresh(); 
 	}

 	public function getToken(){
 		return ($this->is_expired())? $this->refresh() : $this->token;
 	}


	private function is_expired(){
		return ((date("U")-3600000) > $this->expires || $this->token == null)? true : false;//1 hour/3600000MS
	}

	private function refresh(){
    		$data = array();
			$data['cobrandLogin'] = $GLOBALS['YodleeConfig']->COBUsername;
			$data['cobrandPassword'] = $GLOBALS['YodleeConfig']->COBPassword;				
		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$this->endpoint, $data);
		try {
			if(isset($response['cobrandConversationCredentials']['sessionToken'])){
				$this->token = $response['cobrandConversationCredentials']['sessionToken'];
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