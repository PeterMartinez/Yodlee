<?php
require_once "Yodlee.conf.php";
class Yodlee{
	private $cobSessionToken;
    	private $containerServices;
    	private $coreServices;

 	public function __construct(){
		$GLOBALS['YodleeConfig'] = new YodleeConf;//Set YodleeConfig Global
 		$this->cobSessionToken = new cobSessionToken(); //Generate Co Brand Tokens
    	}
    	

    	public function getOAuthToken(){
 		$oAuthToken = new oAuthToken($this->cobSessionToken,$this->coreServices->UserSession());
 		return $oAuthToken->getToken();
    	}

    	public function ContainerServices(){
    		$this->containerServices = ($this->containerServices != null)? $this->containerServices : new containerServices($this->cobSessionToken);
    		return $this->containerServices;
    	}

    	public function CoreServices(){
    		$this->coreServices = ($this->coreServices != null)? $this->coreServices : new coreServices($this->cobSessionToken);
    		return $this->coreServices;
    	}

    	

}




//START containerServices
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
//END containerServices




//START containerServices
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
		debug($response);
		// try {
		// 	if(isset($response['cobrandConversationCredentials']['sessionToken'])){
		// 		$this->token = $response['cobrandConversationCredentials']['sessionToken'];
		// 		$this->expires= date("U");
		// 		return $this->token;					
		// 	}
		// 	else {
		// 		throw new Exception($response['Error'][0]['errorDetail']);
		// 	}
		// }catch (Exception $e) {
		// 	    echo 'Caught exception: ',  $e->getMessage(), "\n";
		// }
	} 
}
//END containerServices




//START Co Branded Session Tokens
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
//END Co Branded Session Tokens




//START User Session Tokens
class userSessionToken{
	private  $SimpleRestJSON;	
	private  $endpoint = "authenticate/login";	
	private $username;
	private $password;
    	private  $cobSessionToken;
    	private  $expires;

 	public function __construct($username,$password,$cobSessionToken){
 		$this->SimpleRestJSON = new SimpleRestJSON(); 		
 		$this->token = null;
 		$this->expires = null;
 		$this->username = $username;
 		$this->password = $password;
 		$this->cobSessionToken = $cobSessionToken;
 	}

 	public function getToken(){
 		return ($this->is_expired())? $this->refresh() : $this->token;
 	}


	private function is_expired(){
		return ((date("U")-3600000) > $this->expires || $this->token == null)? true : false;//1 hour/3600000MS
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
//END User Session Tokens




//START oAuth Tokens nessasry for Signing Requests (Fast Link)
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
//END oAuth Tokens nessasry for Signing Requests (Fast Link)




//START SimpleRestJson
class SimpleRestJSON{
	private $ch;
	public function __construct(){
		$this->ch = curl_init();
	}

	public function post($endpoint, $data){		
		curl_setopt($this->ch, CURLOPT_URL, $endpoint);		
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($this->ch);
		curl_close($this->ch);
		return json_decode($response,true);
	}

	public function get($endpoint, $data){		
		curl_setopt($this->ch, CURLOPT_URL, $endpoint.'?' .http_build_query($data));		
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Accept: application/json')                                                                       
		);  		
		$response = curl_exec($this->ch);
		curl_close($this->ch);
		return json_decode($response,true);
	}

}
//END SimpleRestJson