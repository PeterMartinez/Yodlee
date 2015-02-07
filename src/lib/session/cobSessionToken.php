<?php 
namespace YodleeSDK;
class cobSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/coblogin";
	private $token = null;

 	public function __construct(){
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
		$this->refresh();

 	}

 	public function getToken(){
 		return $this->token;
 	}

	private function refresh(){
    		$data = array();
			$data['cobrandLogin'] = $GLOBALS['YodleeConfig']->COBUsername;
			$data['cobrandPassword'] = $GLOBALS['YodleeConfig']->COBPassword;				
		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$this->endpoint, $data);
		try {
			if(isset($response['cobrandConversationCredentials']['sessionToken'])){
				$this->token = $response['cobrandConversationCredentials']['sessionToken'];				
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