<?php

class Core_Controller_ActionRealtones extends Core_Controller_Action {

    public function init() {
        parent::init();
        $this->_config = $this->getConfig();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $this->_ModelLog = new Application_Model_ConfigPerfil();
        $controller = $this->getParam('action');
        $this->_ModelLog->saveCdr($ua, $controller, 'realtones');
        $this->_GetResultSoap = $this->_helper->getHelper('GetResultSoapRealtones');
        $this->_flashMessage = new Core_Controller_Action_Helper_FlashMessengerCustom();
        $this->layout();
    }

    function mobileDetect() {
        $detect = new App_MobileDetect();
        // $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        if ($detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS() || $detect->isWindowsPhoneOS()) {
            return 'basico360';
        } elseif ($detect->isGenericPhone()) {
            return 'basico240';
        } else {
            return 'basico360';
        }
      
    }

    function obtenerPerfil() {
        $detect = new App_MobileDetect();
        // $deviceType = ($destect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        if ($detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS() || $detect->isWindowsPhoneOS()) {
            return '3';
        } elseif ($detect->isGenericPhone()) {
            return '1';
        } else {
            return '2';
        }
    }

    function layout() {
        switch ($this->mobileDetect()):
            case 'basico240' :
                return $this->_helper->layout->setLayout('realtones/layout-basico240');
                break;
            case 'basico360' :
                return $this->_helper->layout->setLayout('realtones/layout-basico360');
                break;
            case 'avanzado' :
                return $this->_helper->layout->setLayout('realtones/layout-avanzado');
                break;
        endswitch;
    }

    function listarTonos() {
        $resultSP = $this->_GetResultSoap->_listarMenu();
        return $resultSP->listarMenuResult->albumDetalleBE;
    }

    public function preDispatch() {
        parent::preDispatch();
    }

    function desuscribirDeFulltracks() {
        $estaSuscrito = $this->_GetResultSoap->_desuscribirDeFulltracks($this->obtenerNumero());
        var_dump($estaSuscrito);
        exit;
    }

    function suscribirARealTones() {
        $estaSuscrito = $this->_GetResultSoap->_suscribirARealTones($this->obtenerNumero());
        return $estaSuscrito->suscribirARealTonesResult;
    }

    function estaSuscrito() {
        $estaSuscrito = $this->_GetResultSoap->_estaSuscritoARealTones($this->obtenerNumero());
        return $estaSuscrito->estaSuscritoARealTonesResult;
    }

    function cobrarRealTones() {
        $cobrarRealTones = $this->_GetResultSoap->_cobrarRealTones($this->obtenerNumero());
        //var_dump($cobrarFulltracks->cobrarFulltracksResult);exit;
        return $cobrarRealTones->cobrarRealTonesResult;
    }

    function asignarCreditoEnRealtones($tarifa) {
        $this->_GetResultSoap->_asignarCreditoEnRealtones($this->obtenerNumero(),$tarifa);
    }

    function cobrarSuscripcionRealTones() {
        $cobrarSuscripcionRealTones = $this->_GetResultSoap->_cobrarSuscripcionRealTones($this->obtenerNumero());
        //var_dump($cobrarFulltracks->cobrarFulltracksResult);exit;
        return $cobrarSuscripcionRealTones->cobrarSuscripcionRealTonesResult;
    }
    
    function cobrarDemandaRealTones() {
        $cobrarFulltracks = $this->_GetResultSoap->_cobrarDemandaRealTones($this->obtenerNumero());
        //var_dump($cobrarFulltracks->cobrarFulltracksResult);exit;
        return $cobrarFulltracks->cobrarDemandaRealTonesResult;
    }

    function tieneCreditosEnRealTones() {
        $tieneCreditos = $this->_GetResultSoap->_tieneCreditosEnRealTones($this->obtenerNumero());
        return $tieneCreditos->tieneCreditosEnRealTonesResult;
    }

    function consumirCreditoEnRealTones() {
        $consumirCreditos = $this->_GetResultSoap->_consumirCreditoEnRealTones($this->obtenerNumero());
        return $consumirCreditos->consumirCreditoEnRealTonesResult;
    }

    public function obtenerNumero() {

        $apnIfun = strpos($_SERVER['REMOTE_ADDR'], '216.194.');
        if ($apnIfun !== FALSE) {
            $dosG = $_SERVER['HTTP_X_UP_SUBNO'];
            $url = file_get_contents("http://wsperu.multibox.pe/ws-nextel.php?nextel-2g=$dosG");
            $conteDosG = json_decode($url);
            $telefono = "51" . $conteDosG->PTN;
            if ($telefono == "51") {
                $b = strpos($_SERVER['HTTP_COOKIE'], "msisdn=") + 7;
                if ($b != "7") {
                    $num = substr($_SERVER['HTTP_COOKIE'], $b);
                    $telefono = $num;
                } else {
                    $telefono = '';
                }
            }
        } elseif (isset($_SERVER['HTTP_MSISDN']) && $_SERVER['HTTP_MSISDN'] != "") {
            $telefono = $_SERVER['HTTP_MSISDN'];
        } elseif (isset($_SERVER['HTTP_COOKIE']) && $_SERVER['HTTP_COOKIE'] != "") {
            $b = strpos($_SERVER['HTTP_COOKIE'], "msisdn=") + 7;
            if ($b != "7") {
                $num = substr($_SERVER['HTTP_COOKIE'], $b);
                $telefono = $num;
            } else {
                $telefono = '';
            }
        } elseif (isset($_SERVER['HTTP_X_UP_SUBNO']) && $_SERVER['HTTP_X_UP_SUBNO'] != "") {
            $dosG = $_SERVER['HTTP_X_UP_SUBNO'];
            $url = file_get_contents("http://wsperu.multibox.pe/ws-nextel.php?nextel-2g=$dosG");
            $conteDosG = json_decode($url);
            $telefono = "51" . $conteDosG->PTN;
        } else {
            $telefono = '';
        }
//        if (ctype_digit($telefono) && strlen(trim($telefono)) == 11) {
//            return $telefono;
//        } else {
//            $this->_redirect('/pe/realtones/sin-numero');
//        }
      return '51955222071';
    }

}
