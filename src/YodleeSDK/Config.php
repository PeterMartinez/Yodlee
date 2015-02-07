<?php
namespace YodleeSDK;

ini_set('memory_limit', '512M');//Some API Calls are huge
class Conf{
    
    public $AppID = "";//Yodlee App ID
    public $COBID = "";//CoBrand ID 
    public $COBUsername = "";//CoBrand Username
    public $COBPassword = "";//CoBrand Password
    public $COBURL = "";

    public function __construct($conf)
    {
        $this->AppID = $conf['AppID'];
        $this->COBID = $conf['COBID'];
        $this->COBUsername = $conf['COBUsername'];
        $this->COBPassword = $conf['COBPassword'];
        $this->COBURL = $conf['COBURL'];
        
    }
}