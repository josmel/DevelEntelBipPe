<?php

class Realtones_IndexController extends Core_Controller_ActionRealtones {

    public function init() {
        parent::init();
        $estaSuscrito = $this->estaSuscrito();
        if ($estaSuscrito->estaSuscrito == false) {
            $this->_redirect('/pe/realtones/pre-suscripcion');
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if (isset($catalogo) && $catalogo != '') {
                $this->_redirect('/pe/realtones/confirmar-descarga?catalogo=' . $catalogo);
            }
        }
        $this->view->SoapTonos = $this->listarTonos();
       // var_dump($this->listarTonos());exit;
        $this->forward($this->mobileDetect());
    }

    public function indexAction() {

        $this->_redirect('/basico240');
    }

    public function basico240Action() {
        
    }

    public function basico360Action() {
        
    }

    public function avanzadoAction() {
        
    }

}
