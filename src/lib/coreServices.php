<?php
namespace Yodlee;
class coreServices{
	private  $SimpleRestJSON;	
	private  $cobSessionToken;	
	private $userSessionToken;

 	public function __construct($cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON();
 		$this->cobSessionToken = $cobSessionToken;
 	}


 	public function setUserSessionToken($params){
		$this->userSessionToken = 
 			new userSessionToken($params['username'],$params['password'],$this->cobSessionToken);//Set User Session Token		
    	}

	public function UserSession(){
    		return $this->userSessionToken;
    	}    	


 	/*
 		Function: accountSummary
		Description: 
		The REST API gets a list that provides summary information for all accounts added by the user. The API output includes information that can be used in making further calls to retrieve additional account information. Please note that account information is not returned for (a) Deleted accounts and (b) De-activated accounts 

		Parameters: None
		More: http://developer.yodlee.com/Aggregation_API/Aggregation_Services_Guide/Aggregation_REST_API_Reference/Account%2F%2FSummary%2F%2FAll
 	*/
	public function accountSummary(){
		$endpoint = "account/summary/all";
    		$data = array();
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();
			$data['userSessionToken'] = $this->userSessionToken->getToken();

			echo $GLOBALS['YodleeConfig']->COBURL.$endpoint;

		$response =  $this->SimpleRestJSON->get($GLOBALS['YodleeConfig']->COBURL.$endpoint, $data);
		try {
			if(isset($response['ItemContainer'])){
				return $response;					
			}
			else {
				throw new Exception($response['Error'][0]['errorDetail']);
			}
		}catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
}