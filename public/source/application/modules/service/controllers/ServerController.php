<?php

class Service_ServerController extends Zend_Controller_Action {

    public function init() {
        $this->_config = Zend_Registry::get('config');
        $this->_serverSoap = new Zend_Soap_Server(
                $this->_config['resources']['view']['urlSoapWsdl'], array("soap_version" => SOAP_1_2)
        );
    }

    public function soapAction() {
        $this->_helper->viewRenderer->setNoRender();
        if (isset($_GET['wsdl'])) {
            $this->hadleWSDL();
        } else {
            $this->handleSOAP();
        }
    }

    public function hadleWSDL() {
        $autodiscover = new Zend_Soap_AutoDiscover();
        $autodiscover->setClass('Server_Data');
        $autodiscover->handle();
    }

    public function handleSOAP() {
        $this->_serverSoap->setClass('Server_Data');
        $this->_serverSoap->registerFaultException(array('Server_Exception'));
        $this->_serverSoap->handle();
    }

}
