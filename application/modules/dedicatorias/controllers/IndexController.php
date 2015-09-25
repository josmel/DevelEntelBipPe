<?php

class Dedicatorias_IndexController extends Core_Controller_ActionDedicatorias {

    public function init() { 
        parent::init();
        $this->_helper->layout->setLayout('dedicatorias/layout-avanzado');
        $estaSuscrito = $this->estaSuscritoADedicatorias();
        if ($estaSuscrito->estaSuscrito == false) {
            $this->_redirect('/pe/dedicatorias/pre-suscripcion');
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if (isset($catalogo) && $catalogo != '') {
                $this->_redirect('/pe/dedicatorias/confirmar-dedicatoria?catalogo=' . $catalogo);
            }
        }
        $this->view->SoapMusica = $this->listarMusica();
        //  $this->_helper->layout->setLayout('dedicatorias/layout-basico240');
        $this->forward($this->mobileDetect());
    }

    public function indexAction() {
        $this->_redirect('/basico240');
    }

    public function basicoAction() {
        
    }

    public function basico240Action() {
        
    }

    public function basico360Action() {
        
    }

    public function avanzadoAction() {
        
    }

}
