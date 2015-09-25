<?php

ini_set("soap.wsdl_cache_enabled", "0");

class App_Controller_Action_Helper_GetResultSoapRealtones extends Zend_Controller_Action_Helper_Abstract {

    protected $_config;
    protected $_OPERADORA = 3;
    protected $_TIPO = 'realtones';

    public function __construct() {
        $this->_config = Zend_Registry::get('config');
        $this->_ModelLog = new Application_Model_ConfigPerfil();
        $this->_clienteSoap = new Zend_Soap_Client(
                $this->_config['resources']['view']['urlSoapRealtones'], array('soap_version' => SOAP_1_1));
    }

    public function _listarMenu() {
        $params = array(
            'operadora' => $this->_OPERADORA
        );
        try {
            $response = $this->_clienteSoap->listarMenu($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('listarMenu', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _tieneCreditosEnRealTones($numero) {
        $params = array(
            'numuser' => $numero,
        );

        try {
            $response = $this->_clienteSoap->tieneCreditosEnRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('tieneCreditosEnRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _consumirCreditoEnRealTones($numero) {
        $params = array(
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->consumirCreditoEnRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('consumirCreditoEnRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerCancionEnRealTones($codigo) {
        $params = array(
            'catalogo' => $codigo,
        );
        try {
            $response = $this->_clienteSoap->obtenerCancionEnRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerCancionEnRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _generarCodigoDescargaEnRealtones($codigo, $numero, $tarifaCobrada) {
        $params = array(
            'catalogo' => $codigo,
            'numuser' => $numero,
            'tarifaCobrada' => $tarifaCobrada,
        );
        try {
            $response = $this->_clienteSoap->generarCodigoDescargaEnRealtones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('generarCodigoDescargaEnRealtones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _confirmarDescargaEnRealtones($codigo, $estado) {
        $params = array(
            'codigoControl' => $codigo,
            'estado' => $estado,
        );
        try {
            $response = $this->_clienteSoap->confirmarDescargaEnRealtones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('confirmarDescargaEnRealtones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _estaSuscritoARealTones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->estaSuscritoARealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('estaSuscritoARealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _desuscribirDeRealtones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->desuscribirDeRealtones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('desuscribirDeRealtones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _cobrarRealTones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->cobrarRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    function _cobrarSuscripcionRealTones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->cobrarSuscripcionRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarSuscripcionRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    function _asignarCreditoEnRealtones($numero,$tarifa) {
        $params = array(
            'numuser' => $numero,
            'tarifa'=>$tarifa
        );
        try {
            $response = $this->_clienteSoap->asignarCreditoEnRealtones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('asignarCreditoEnRealtones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }
    
    function _cobrarDemandaRealTones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->cobrarDemandaRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarDemandaRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _suscribirARealTones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->suscribirARealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('suscribirARealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
//        } else {
//            $this->_ModelLog->saveCdrLog('suscribirARealTones', 'valor de numero:' . $numero, $this->_TIPO);
//            header("Location: /pe/error-servicios");
//        }
    }

    public function _obtenerControlDescargaEnRealTones($codigoControl) {
        $params = array(
            'controlDescarga' => $codigoControl,
        );
        try {
            $response = $this->_clienteSoap->obtenerControlDescargaEnRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerControlDescargaEnRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _listarCompraEnRealtones($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->listarCompraEnRealtones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('listarCompraEnRealtones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerSMSLinkEnRealTones($codigo) {
        $params = array(
            'codigo' => $codigo,
        );
        try {
            $response = $this->_clienteSoap->obtenerSMSLinkEnRealTones($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerSMSLinkEnRealTones', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

}
