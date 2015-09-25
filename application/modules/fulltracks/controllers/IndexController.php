<?php

class Fulltracks_IndexController extends Core_Controller_ActionFulltracks {

    public function init() {
        parent::init();
        $estaSuscrito = $this->estaSuscrito();
        if ($estaSuscrito->estaSuscrito == false) {
            $this->_redirect('/pe/fulltracks/pre-suscripcion');
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if ($estaSuscrito->ultimoCobro == date('Ymd') || $estaSuscrito->esFreeUser) {
                if (isset($catalogo) && $catalogo != '') {
                    $this->_redirect('/pe/fulltracks/confirmar-descarga?catalogo=' . $catalogo);
                }
            } else {
                if (isset($catalogo) && $catalogo != '') {
                    $this->_redirect('/pe/fulltracks/confirma-suscripcion-demanda?catalogo=' . $catalogo);
                } else {
                    $this->_redirect('http://m.entretenimiento.entel.pe/');
                }
            }
        }
        $this->view->SoapMusica = $this->listarMusica();
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
