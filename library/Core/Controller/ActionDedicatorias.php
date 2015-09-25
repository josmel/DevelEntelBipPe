<?php

class Core_Controller_ActionDedicatorias extends Core_Controller_Action {

    protected $_identity;

    public function init() {
        parent::init();
        $this->_config = $this->getConfig();
        $this->_GetResultSoap = $this->_helper->getHelper('GetResultSoapDedicatorias');
        $this->_flashMessage = new Core_Controller_Action_Helper_FlashMessengerCustom();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $controller = $this->getParam('action');
        $this->_ModelLog = new Application_Model_ConfigPerfil();
        $this->_ModelLog->saveCdr($ua, $controller, 'dedicatorias');
        $this->layout();
    }

    function mobileDetect() {
//        $detect = new App_MobileDetect();
//        // $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
//        if ($detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS() || $detect->isWindowsPhoneOS()) {
//            return 'basico360';
//        } elseif ($detect->isGenericPhone()) {
//            return 'basico240';
//        } else {
        return 'basico360';
//        }
    }

    function obtenerPerfil() {
//        $detect = new App_MobileDetect();
//        // $deviceType = ($destect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
//        if ($detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS() || $detect->isWindowsPhoneOS()) {
        return '3';
//        } elseif ($detect->isGenericPhone()) {
//            return '1';
//        } else {
//            return '2';
//        }
    }

    function layout() {
//        switch ($this->mobileDetect()):
//            case 'basico240' :
//                return $this->_helper->layout->setLayout('dedicatorias/layout-basico240');
//                break;
//            case 'basico360' :
        return $this->_helper->layout->setLayout('dedicatorias/layout-basico360');
//                break;
//            case 'avanzado' :
//        return $this->_helper->layout->setLayout('dedicatorias/layout-avanzado');
//                break;
//        endswitch;
    }

    function listarMusica() {
        $resultSP = $this->_GetResultSoap->_listarMenu();
        return $resultSP->listarMenuResult->albumDetalleBE;
    }

    function estaSuscritoADedicatorias() {
        $estaSuscrito = $this->_GetResultSoap->_estaSuscritoADedicatorias($this->obtenerNumero());
        return $estaSuscrito->estaSuscritoADedicatoriasResult;
    }

    function asignarCreditoEnDedicatorias($tarifa) {
        $resultSP = $this->_GetResultSoap->_asignarCreditoEnDedicatorias($this->obtenerNumero(), $tarifa);
        return $resultSP->asignarCreditoEnDedicatoriasResult;
    }

    function cobrarSuscripcionDedicatorias($token) {
        $resultSP = $this->_GetResultSoap->_cobrarSuscripcionDedicatorias($this->obtenerNumero(), $token);
        return $resultSP->cobrarSuscripcionDedicatoriasResult;
    }

    function suscribirADedicatorias() {
        $resultSP = $this->_GetResultSoap->_suscribirADedicatorias($this->obtenerNumero());
        return $resultSP->suscribirADedicatoriasResult;
    }

    function desuscribirDeDedicatorias() {
        $resultSP = $this->_GetResultSoap->_desuscribirDeDedicatorias($this->obtenerNumero());
        return $resultSP->suscribirADedicatoriasResult;
    }

    function solicitarCompraToken() {
        $resultSP = $this->_GetResultSoap->_solicitarCompraToken($this->obtenerNumero());
        return $resultSP->solicitarCompraTokenResult;
    }

    function acuseReciboCompra($token) {
        $resultSP = $this->_GetResultSoap->_acuseReciboCompra($this->obtenerNumero(), $token);
        return $resultSP->acuseReciboCompraResult;
    }

    function tieneCreditosEnDedicatorias() {
        $resultSP = $this->_GetResultSoap->_tieneCreditosEnDedicatorias($this->obtenerNumero());
        return $resultSP->tieneCreditosEnDedicatoriasResult;
    }

    function cobrarDemandaDedicatorias($token) {
        $resultSP = $this->_GetResultSoap->_cobrarDemandaDedicatorias($this->obtenerNumero(),$token);
        return $resultSP->cobrarDemandaDedicatoriasResult;
    }

    //////////////////////7777777777
    function generarCodigoControlEnDedicatorias($numdestino, $catalogo, $tarifaCobrada) {
        $resultSP = $this->_GetResultSoap->_generarCodigoControlEnDedicatorias($this->obtenerNumero(), $numdestino, $catalogo, $tarifaCobrada);
        return $resultSP->generarCodigoControlEnDedicatoriasResult;
    }

    function activarLlamadaEnDedicatoria($codigoControl, $mensajeADedicar) {
        $resultSP = $this->_GetResultSoap->_activarLlamadaEnDedicatoria($codigoControl, $mensajeADedicar);
        return $resultSP->activarLlamadaEnDedicatoriaResult;
    }

    function obtenerDatoDedicatoriasEnDedicatorias($codigoControl) {
        $resultSP = $this->_GetResultSoap->_obtenerDatoDedicatoriasEnDedicatorias($codigoControl);
        return $resultSP->obtenerDatoDedicatoriasEnDedicatoriasResult;
    }

    ////////////////////////777  

    function consumirCreditoEnDedicatorias() {
        $resultSP = $this->_GetResultSoap->_consumirCreditoEnDedicatorias($this->obtenerNumero());
        return $resultSP->consumirCreditoEnDedicatoriasResult;
    }

    public function preDispatch() {
        parent::preDispatch();
    }

    public function obtenerNumero() {

        $apnIfun = strpos($_SERVER['REMOTE_ADDR'], '216.194.');
        if ($apnIfun !== FALSE) {
            $dosG = $_SERVER['HTTP_X_UP_SUBNO'];
            $url = file_get_contents("http://wsperu.multibox.pe/ws-nextel.php?nextel-2g=$dosG");
            $conteDosG = json_decode($url);
            if ($conteDosG->PTN)
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
        if (ctype_digit($telefono) && strlen(trim($telefono)) == 11) {
            return $telefono;
        } else {
        return '51925222078';
//            $this->_redirect('/pe/dedicatorias/sin-numero');
        }

        //    return '51955222078';
    }

}

