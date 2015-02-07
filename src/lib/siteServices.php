<?php
namespace YodleeSDK;
class siteServices{
	private  $SimpleRestJSON;	
	private  $cobSessionToken;	

 	public function __construct($cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON();
 		$this->cobSessionToken = $cobSessionToken;
 	}

 	/*
 		Function: all
		Description: 
		This REST API returns all the sites in the system that are enabled for the specific cobrand 

		Parameters: none

		More: http://developer.yodlee.com/Aggregation_API/Aggregation_Services_Guide/Aggregation_REST_API_Reference/getAllSites
 	*/
	public function all(){
		$endpoint = "/jsonsdk/SiteTraversal/getAllSites";
    		$data = array();
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();

		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$endpoint, $data);
		try {
			if(isset($response[0])){
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