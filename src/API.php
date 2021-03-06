<?php
namespace YodleeSDK;

//Config, Session and External Libs
include("lib/external/simpleRestJSON.php");
include("lib/session/cobSessionToken.php");
include("lib/session/userSessionToken.php");

//API Libs
include("lib/coreServices.php");
include("lib/fastLinkServices.php");
include("lib/containerServices.php");
include("lib/siteServices.php");
include("lib/dataExtractServices.php");



//Config, Session and External Libs
use YodleeSDK\SimpleRestJSON as SimpleRestJSON;
use YodleeSDK\cobSessionToken as cobSessionToken;
use YodleeSDK\userSessionToken as userSessionToken;

//API Libs
use YodleeSDK\coreService as coreService;
use YodleeSDK\fastLinkServices as fastLinkServices;
use YodleeSDK\containerServices as containerServices;
use YodleeSDK\siteServices as siteServices;
use YodleeSDK\dataExtractServices as dataExtractServices;

class API{
    private $cobSessionToken = null;
    private $containerServices;
    private $coreServices;
    private $siteServices;
    private $dataExtractServices;

    //Conf => Namespace Yodlee/Conf
    public function __construct($Conf){
        $GLOBALS['YodleeConfig'] = $Conf;
    }

    private function setCobSession(){
        $this->cobSessionToken = ($this->cobSessionToken == null)? new cobSessionToken() : $this->cobSessionToken;
    }
    public function FastLinkServices(){
        $this->setCobSession();
        $fastLinkServices = new fastLinkServices($this->cobSessionToken,$this->coreServices->UserSession());
        return $fastLinkServices->getFastLinkURL();
    }

    public function ContainerServices(){
        $this->setCobSession();        
        $this->containerServices = ($this->containerServices != null)? $this->containerServices : new containerServices($this->cobSessionToken);
        return $this->containerServices;
    }

    public function CoreServices(){
        $this->setCobSession();        
        $this->coreServices = ($this->coreServices != null)? $this->coreServices : new coreServices($this->cobSessionToken);
        return $this->coreServices;
    }

    public function SiteServices(){
        $this->setCobSession();        
        $this->siteServices = ($this->siteServices != null)? $this->siteServices : new siteServices($this->cobSessionToken);
        return $this->siteServices;
    }

    public function DataExtractServices(){
        $this->setCobSession();        
        $this->dataExtractServices = ($this->dataExtractServices != null)? $this->dataExtractServices : new dataExtractServices($this->cobSessionToken);
        return $this->dataExtractServices;
    }

    

        
}