<?php

class Realtones_FreeController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $this->_GetResultSoap = $this->_helper->getHelper('GetResultSoapRealtones');
        $model = new Application_Model_ConfigPerfil();
        $controller = $this->getParam('action');
        $model->saveCdr($ua, $controller, 'realtones');
        $this->_helper->layout->setLayout('realtones/layout-avanzado');
    }

    public function sinNumeroAction() {
        
    }

    public function legalAction() {
        
    }

    public function smsLinkAction() {
        $ObtenerNumero = new Core_Utils_Utils();
        $telefono = $ObtenerNumero->obtenerNumero();
        if ($this->_request->isGet()) {
            $codigo = $this->_getParam('i', '');
            try {
                if (isset($codigo) && $codigo != '') {
                    $resultSms = $this->_GetResultSoap->_obtenerSMSLinkEnRealTones($codigo);
                    $responseSmnLinkRt = $resultSms->obtenerSMSLinkEnRealTonesResult;
                    if ($responseSmnLinkFt->esValido == true && $responseSmnLinkFt->numuser == $telefono) {
                        $this->_redirect('/pe/realtones');
                    } else {
                        $this->_redirect('http://m.entretenimiento.entel.pe');
                    }
                } else {
                    $this->_redirect('http://m.entretenimiento.entel.pe');
                }
            } catch (Exception $e) {
                $this->_redirect('http://m.entretenimiento.entel.pe');
            }
        } else {
            $this->_redirect('http://m.entretenimiento.entel.pe');
        }
    }

}
