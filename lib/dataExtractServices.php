<?php
namespace Yodlee;
class dataExtractServices{
	private  $SimpleRestJSON;	
	private  $cobSessionToken;	

 	public function __construct($cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON();
 		$this->cobSessionToken = $cobSessionToken;
 	}

 	/*
 		Function: all
		Description: 
		This REST API is used to retrieve a list of items refreshed during a specified date range and refresh type, such as cache refresh and/or instant refresh.
		Parameters:
			- requiredAll: Set False to retrieve only successfully refreshed item and True to retrieve both, successful and failed refreshed items. By default the flag is set to False.	
			- startDate: This is the starting date of the time range. The start date should be in PST time zone. 	
			- endDate: This is the ending date of the time range. The end date should be in PST time zone.​	
			- refreshType: This indicates the type of refresh. Set 1 for cache refresh and 2 for instant refresh. If null value is passed, details of items refreshed will be returned depending on the value set in the requiredAll flag. If the requiredAll flag is set to FALSE, all the successfully refreshed items will be returned and when set to TRUE, all successfully refreshed items along with the ones that failed will be returned.

		More: http://developer.yodlee.com/Aggregation_API/Aggregation_Services_Guide/Aggregation_REST_API_Reference/getRefreshedUserItems
 	*/
	public function getRefreshedUserItems($requiredAll,$startDate,$endDate,$refreshType){
		$endpoint = "/jsonsdk/Refresh/getRefreshedUserItems";
    		$data = array();
			$data['cobSessionToken'] = $this->cobSessionToken->getToken();
			$data['refreshDataFilter.requiredAll'] = $requiredAll;
			$data['refreshDataFilter.startDate'] = date("m-d-Y\TG:i:s",$startDate);
			$data['refreshDataFilter.endDate'] = date("m-d-Y\TG:i:s",$endDate);
			$data['refreshDataFilter.refreshType'][0] = $refreshType;

		$response =  $this->SimpleRestJSON->post($GLOBALS['YodleeConfig']->COBURL.$endpoint, $data);
		debug($response);
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