<?php
namespace Yodlee;
include("lib/coreService.php");
include("Yodlee.conf.php");
include("lib/SimpleRestJSON.php");
include("lib/cobSessionToken.php");
include("lib/userSessionToken.php");
include("lib/oAuth.php");
include("lib/containerServices.php");

use Yodlee\coreService as coreService;
use Yodlee\Conf as Conf;
use Yodlee\SimpleRestJSON as SimpleRestJSON;
use Yodlee\cobSessionToken as cobSessionToken;
use Yodlee\userSessionToken as userSessionToken;
use Yodlee\oAuthToken as oAuthToken;
use Yodlee\containerServices as containerServices;

class API{
	private $cobSessionToken;
    	private $containerServices;
    	private $coreServices;

 	public function __construct(){
		$GLOBALS['YodleeConfig'] = new Conf;//Set YodleeConfig Global
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