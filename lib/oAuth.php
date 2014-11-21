<?php
namespace Yodlee;
class oAuthToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "jsonsdk/OAuthAccessTokenManagementService/getOAuthAccessToken";	
	//START Static From Documentaiton, never changes
	private  $bridgeAppID = "10003200";
	private $oAuthKey = "a458bdf184d34c0cab7ef7ffbb5f016b";
	private $oAuthSecret = "1ece74e1ca9e4befbb1b64daba7c4a24";
	private $request_type = "call_api_service";
	private $request_api_service = "getOAuthAccessToken";
	//END Static From Documentaiton, never changes
    	private  $cobSessionToken;
    	private  $userSessionToken;



 	public function __construct($cobSessionToken,$userSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
 		$this->token = null;
 		$this->cobSessionToken = $cobSessionToken;
 		$this->userSessionToken = $userSessionToken; 	
 	}

	public function getToken(){
    		$data = array();
			$data['bridgetAppId'] = $this->bridgeAppID;
			$data['userSessionToken'] = $this->userSessionToken->getToken();
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();
			$data['request_type'] = $this->request_type;
			$data['request_api_service'] = $this->request_api_service;
			$data['url'] = $this->endpoint;

		$response = $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$this->endpoint, $data);
		try {
			if(isset($response['token'])){
				return array("token"=>$response['token'],"secret"=>$response['tokenSecret']);	
			}
			else {
				throw new Exception($response['Error'][0]['errorDetail']);
			}
		}catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	} 
}