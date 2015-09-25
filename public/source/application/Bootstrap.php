<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initView() {
        
        $this->bootstrap('layout');        
        $layout = $this->getResource('layout');
        $v = $layout->getView();

        $v->addHelperPath(APPLICATION_PATH."/../library/Core/View/Helper", "Core_View_Helper");
            
        $config = Zend_Registry::get('config');
        $version= self::getVersion();

        $cache = $this->getPluginResource('cachemanager')->getCacheManager()->getCache('default');
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        Zend_Registry::set('Cache', $cache);
        
        $this->getResourceLoader()->addResourceType('entity', 'entitys/', 'Entity');
        
        //Definiendo Constante para Partials
        defined('STATIC_URL')
            || define('STATIC_URL', $config['app']['staticUrl']);
        defined('DINAMIC_URL')
            || define('DINAMIC_URL', $config['app']['dinamicUrl']);
        defined('IMG_URL')
            || define('IMG_URL', $config['app']['imgUrl']);
        defined('SITE_URL')
            || define('SITE_URL', $config['app']['siteUrl']);
        defined('SITE_TEMP')
            || define('SITE_TEMP',$config['app']['elementTemp']);
        defined('SITE_VERSION')
            || define('SITE_VERSION',$version);
        defined('STATIC_ADMIN_IMG')
            || define('STATIC_ADMIN_IMG',$config['app']['imgAdmin']);
        
        defined('ROOT_IMG_DINAMIC')
            || define('ROOT_IMG_DINAMIC',$config['app']['rootImgDinamic']);
//        defined('LOG_PATH')
//            || define('LOG_PATH',$config['app']['logPath']);

        //* Antes de modularizar -solo para el landing *//   
       
//            defined('CHALLENGE_DINAMIC_URL')
//                || define('CHALLENGE_DINAMIC_URL',$uriChallenge.'/dinamic/');
////        }
//        
        $doctypeHelper = new Zend_View_Helper_Doctype();                
        $doctypeHelper->doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);
        $v->headTitle($config['resources']['view']['title'])->setSeparator(' | ');
        $v->headMeta()->appendHttpEquiv('Content-Type',
            'text/html; charset=utf-8');
        $v->headMeta()->appendName("author", "onlineproduction");
        $v->headMeta()->setName("language", "es");
        $v->headMeta()->appendName("description",
            "managent aplication");
        $v->headMeta()->appendName("keywords",
            "ayuda.");
        if(APPLICATION_ENV!='LOCAL') $this->frontController->throwExceptions(false); 
    }
    
    public function getVersion(){
        $filename = APPLICATION_PATH.'/../last_commit';
        $version=date('dm');
        if(is_readable($filename)){
            $version=trim(file_get_contents($filename));
        }
        return $version;
    }
    
     public function _initRegistries()
    {        

        $this->bootstrap('multidb');
        $db = $this->getPluginResource('multidb')->getDb('db');
        Zend_Db_Table::setDefaultAdapter($db);
        //$multidb = $this->getPluginResource('multidb');
        Zend_Registry::set('multidb', $db);
       //Zend_Debug::dump($db); exit;
         $this->bootstrap('cachemanager');
         $cache = $this->getResource('cachemanager')->getCache('file');
         
         Zend_Registry::set('cache', $cache);         
//        $this->_executeResource('log');
//        $log = $this->getResource('log');
//        Zend_Registry::set('log', $log);
    }
    
 
    
   

    protected function _initAutoload() { 
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Application', 
            'basePath' => APPLICATION_PATH . '/models', 
        ));
        
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Businessman', 
            'basePath' => APPLICATION_PATH . '/entitys/businessman', 
        ));
        
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Shop', 
            'basePath' => APPLICATION_PATH . '/entitys/shop', 
        ));
        
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Mailing', 
            'basePath' => APPLICATION_PATH . '/entitys/mailing', 
        ));
        
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Admin', 
            'basePath' => APPLICATION_PATH . '/entitys/admin', 
        ));
        
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Challenge', 
            'basePath' => APPLICATION_PATH . '/entitys/challenge', 
        ));
        
        
        
        new Zend_Application_Module_Autoloader(array( 
            'namespace' => 'Biller', 
            'basePath' => APPLICATION_PATH . '/entitys/biller', 
        ));
    } 
    
//    protected function _initAcl() {
//        $cache = Zend_Registry::get('Cache');
//        if (!$acl = $cache->load('acl')) {
//            $acl = new Core_Acl();
//            $cache->save($acl, 'acl');
//        }
//        
//        Zend_Registry::set('Zend_Acl', $acl);
//    }
    
    
 
}

