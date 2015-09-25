<?php

class Preview_IndexController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
        $this->_config = $this->getConfig();
        $this->_serverSoap = new Zend_Soap_Client($this->_config['resources']['view']['urlSoapWsdl']);
        $SoapBanner = $this->_serverSoap->receiveMessage('BANNER');
        $SoapMusica = $this->_serverSoap->receiveMessage('MUSICA');
        $SoapJuego = $this->_serverSoap->receiveMessage('JUEGO');
        $SoapText = $this->_serverSoap->receiveMessage('TEXT');
        $SoapServicio = $this->_serverSoap->receiveMessage('SERVICIO');
        $perfil = $this->_getParam('perfil', '5');
        switch ($perfil):
            case '1' :
                $this->forward('basico');
                break;
            case '2' :
                $this->forward('basico128');
                break;
            case '3' :
                $this->forward('basico240');
                break;
            case '4' :
                $this->forward('basico360');
                break;
            case '5' :
                $this->forward('avanzado');
                break;
            default:
                $this->forward('basico240');
                break;
        endswitch;
        $this->view->SoapBanners = $SoapBanner;
        $this->view->SoapMusica = $SoapMusica;
        $this->view->SoapJuego = $SoapJuego;
        $this->view->SoapText = $SoapText;
        $this->view->SoapServicio = $SoapServicio;
    }

    public function indexAction() {

        $this->_redirect('/basico240');
    }

    public function basicoAction() {
        $this->_helper->layout->disableLayout();
    }

    public function basico128Action() {

        $this->_helper->layout->setLayout('preview/layout-basico128');
    }

    public function basico240Action() {
        $this->_helper->layout->setLayout('preview/layout-basico240');
    }

    public function basico360Action() {
        $this->_helper->layout->setLayout('preview/layout-basico360');
    }

    public function avanzadoAction() {

        $this->_helper->layout->setLayout('preview/layout-avanzado');
    }
}
