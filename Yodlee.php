<?php
namespace Yodlee;

//Config, Session and External Libs
include("Yodlee.conf.php");
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
use Yodlee\Conf as Conf;
use Yodlee\SimpleRestJSON as SimpleRestJSON;
use Yodlee\cobSessionToken as cobSessionToken;
use Yodlee\userSessionToken as userSessionToken;

//API Libs
use Yodlee\coreService as coreService;
use Yodlee\fastLinkServices as fastLinkServices;
use Yodlee\containerServices as containerServices;
use Yodlee\siteServices as siteServices;
use Yodlee\dataExtractServices as dataExtractServices;

class API{
    private $cobSessionToken;
    private $containerServices;
    private $coreServices;
    private $siteServices;
    private $dataExtractServices;


    public function __construct(){
        $GLOBALS['YodleeConfig'] = new Conf;//Set YodleeConfig Global
        $this->cobSessionToken = new cobSessionToken(); //Generate Co Brand Tokens
    }


    public function FastLinkServices(){
        $fastLinkServices = new fastLinkServices($this->cobSessionToken,$this->coreServices->UserSession());
        return $fastLinkServices->getToken();
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

    public function DataExtractServices(){
        $this->dataExtractServices = ($this->dataExtractServices != null)? $this->dataExtractServices : new dataExtractServices($this->cobSessionToken);
        return $this->dataExtractServices;
    }

    

        
}