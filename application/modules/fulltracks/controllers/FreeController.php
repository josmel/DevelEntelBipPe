<?php

class Fulltracks_FreeController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $this->_GetResultSoap = $this->_helper->getHelper('GetResultSoap');
        $model = new Application_Model_ConfigPerfil();
        $controller = $this->getParam('action');
        $model->saveCdr($ua, $controller, 'fulltracks');
        $this->_helper->layout->setLayout('fulltracks/layout-avanzado');
    }

    public function sinNumeroAction() {
        
    }

    public function legalAction() {
        
    }

    public function nAction() {
        $i = $this->_getParam('i', '');
        if ($i) {
            $nue2 = $i;
            $url3 = 'http://174.121.234.90/Moso/Cript/process.aspx?d1=' . $nue2;
            $nu = file_get_contents($url3);

//            var_dump($nu);
//            exit;
            //$nu="51998993069|201206191632|_FTWAP|7";
            $separador = "|";
            $param = explode($separador, $nu);
            if (isset($param[0]) && $param[0] != '') {
                $this->_redirect('/pe/fulltracks');
            } else {
                $this->_redirect('http://m.entretenimiento.entel.pe');
            }
        }
    }

    public function smsLinkAction() {
        $ObtenerNumero = new Core_Utils_Utils();
        $telefono = $ObtenerNumero->obtenerNumero();
        if ($this->_request->isGet()) {
            $codigo = $this->_getParam('i', '');
            try {
                if (isset($codigo) && $codigo != '' && $telefono != '') {
                    $resultSms = $this->_GetResultSoap->_obtenerSMSLinkEnFulltracks($codigo);
                     Zend_Debug::dump($resultSms);
                    $responseSmnLinkFt = $resultSms->obtenerSMSLinkEnFulltracksResult;
                    if ($responseSmnLinkFt->esValido == true && $responseSmnLinkFt->numuser == $telefono) {
                        $this->_redirect('/pe/fulltracks');
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
