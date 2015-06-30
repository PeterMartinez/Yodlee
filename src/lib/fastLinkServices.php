<?php
namespace YodleeSDK;
class fastLinkServices{
	private  $SimpleRestJSON;	
	private  $endpoint = "jsonsdk/OAuthAccessTokenManagementService/getOAuthAccessToken";	
	//START Static From Documentaiton, never changes
	private $fastLinkBase = null;	
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
 		$this->fastLinkBase = $GLOBALS['YodleeConfig']->FASTLINKBASE;
 	}

 	public function getFastLinkURL(){

 		$tokens = $this->getToken();
 		$oauthVersion = "1.0";
		$oauthSignatureMethod = "HMAC-SHA1"; 
		$accessTokenUrl = $this->fastLinkBase; 
		$nonce = md5(mt_rand()); 
		$oauthTimestamp = time();


		$sigBase = "GET&" . rawurlencode($accessTokenUrl)
		    .'&access_type%3Doauthdeeplink%26'	
		    .'displayMode%3Ddesktop%26'
		    .'oauth_callback%3DOOB%26'
		    ."oauth_consumer_key%3D". rawurlencode($this->oAuthKey).'%26'
		    ."oauth_nonce%3D" . rawurlencode($nonce).'%26'
		    ."oauth_signature_method%3D" . rawurlencode($oauthSignatureMethod).'%26'
		    ."oauth_timestamp%3D" . rawurlencode($oauthTimestamp).'%26'
		    ."oauth_token%3D" . rawurlencode($tokens['token']).'%26'
		    ."oauth_version%3D" . rawurlencode($oauthVersion); 
		$sigKey= $this->oAuthSecret."&".$tokens['secret'];
		$oauthSig = base64_encode(hash_hmac("sha1",$sigBase, $sigKey, TRUE));
		$requestUrl = $accessTokenUrl . "?"
		    .'&access_type=oauthdeeplink'		    
		    .'&displayMode=desktop'		    
		    .'&oauth_callback=OOB'		    
		    .'&oauth_consumer_key='.rawurlencode($this->oAuthKey)		    
		    .'&oauth_nonce=' . rawurlencode($nonce)		    
		    ."&oauth_signature_method=" . rawurlencode($oauthSignatureMethod)
		    ."&oauth_timestamp=" . rawurlencode($oauthTimestamp)
		    ."&oauth_token=" . rawurlencode($tokens["token"])
		    ."&oauth_version=". rawurlencode($oauthVersion)
		    ."&oauth_signature=" . $oauthSig; 

		return $requestUrl;
 	}

	private function getToken(){
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