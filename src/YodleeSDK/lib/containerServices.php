<?php
namespace YodleeSDK;
class containerServices{
	private  $SimpleRestJSON;	
	private  $cobSessionToken;	

 	public function __construct($cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON();
 		$this->cobSessionToken = $cobSessionToken;
 	}

 	/*
 		Function: all
		Description: 
		This REST API returns all the content services for the given cobrand. 
		Since this API returns a large volume of data, it could take some time for processing and  can 
		cause an OutOfMemoryException on the server or client. So this API should be invoked only 
		when necessary or on a periodical basis to get the most up-to-date list of content services. 
		It is recommended that this list of supported financial institutions be cached for subsequent use. 

		Parameters: 
		$notrim => True || False
			- Set this to true in order to receive the full ContentServiceInfo object

		More: http://developer.yodlee.com/Aggregation_API/Aggregation_Services_Guide/Aggregation_REST_API_Reference/getAllContentServices
 	*/
	public function all($notrim = false){
		$endpoint = "/jsonsdk/ContentServiceTraversal/getAllContentServices";
    		$data = array();
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();
			$data['notrim'] = $notrim;

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