<?php
namespace Yodlee;

//Config, Session and External Libs
include("Yodlee.conf.php");
include("lib/external/simpleRestJSON.php");
include("lib/session/cobSessionToken.php");
include("lib/session/userSessionToken.php");

//API Libs
include("lib/coreServices.php");
include("lib/oAuthServices.php");
include("lib/containerServices.php");
include("lib/siteServices.php");

//Config, Session and External Libs
use Yodlee\Conf as Conf;
use Yodlee\SimpleRestJSON as SimpleRestJSON;
use Yodlee\cobSessionToken as cobSessionToken;
use Yodlee\userSessionToken as userSessionToken;

//API Libs
use Yodlee\coreService as coreService;
use Yodlee\oAuthToken as oAuthToken;
use Yodlee\containerServices as containerServices;
use Yodlee\siteServices as siteServices;

class API{
    private $cobSessionToken;
    private $containerServices;
    private $coreServices;
    private $siteServices;

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

    public function SiteServices(){
        $this->siteServices = ($this->siteServices != null)? $this->siteServices : new siteServices($this->cobSessionToken);
        return $this->siteServices;
    }

        
}