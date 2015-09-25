<?php

ini_set("soap.wsdl_cache_enabled", "0");

class App_Controller_Action_Helper_GetResultSoapDedicatorias extends Zend_Controller_Action_Helper_Abstract {

    protected $_config;
    protected $_OPERADORA = 3;
    protected $_SERVICIO = 146;
    protected $_TIPO = 'dedicatorias';

    public function __construct() {
        $this->_config = Zend_Registry::get('config');
        $this->_ModelLog = new Application_Model_ConfigPerfil();
        $this->_clienteSoap = new Zend_Soap_Client(
                $this->_config['resources']['view']['urlSoapDedicatorias'], array('soap_version' => SOAP_1_1));
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

    public function _tieneCreditosEnDedicatorias($numero) {
        $params = array(
            'numuser' => $numero,
        );

        try {
            $response = $this->_clienteSoap->tieneCreditosEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('tieneCreditosEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _consumirCreditoEnDedicatorias($numero) {
        $params = array(
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->consumirCreditoEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('consumirCreditoEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _solicitarCompraToken($numero) {
        $params = array(
            'numuser' => $numero,
            'servicio' => $this->_SERVICIO
        );

        try {
            $response = $this->_clienteSoap->solicitarCompraToken($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('solicitarCompraToken', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _acuseReciboCompra($numero, $token) {
        $params = array(
            'numuser' => $numero,
            'servicio' => $this->_SERVICIO,
            'token' => $token
        );

        try {
            $response = $this->_clienteSoap->acuseReciboCompra($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('acuseReciboCompra', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerCancionEnDedicatorias($codigo) {
        $params = array(
            'catalogo' => $codigo,
        );
        try {
            $response = $this->_clienteSoap->obtenerCancionEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerCancionEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerDatoDedicatoriasEnDedicatorias($codigoControl) {
        $params = array(
            'codigoControl' => $codigoControl,
        );
        try {
            $response = $this->_clienteSoap->obtenerDatoDedicatoriasEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerDatoDedicatoriasEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _activarLlamadaEnDedicatoria($codigoControl, $mensajeADedicar) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'codigoControl' => $codigoControl,
            'mensajeADedicar' => $mensajeADedicar
        );
        try {
            $response = $this->_clienteSoap->activarLlamadaEnDedicatoria($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('activarLlamadaEnDedicatoria', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _estaSuscritoADedicatorias($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->estaSuscritoADedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('estaSuscritoADedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _desuscribirDeDedicatorias($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->desuscribirDeDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('desuscribirDeDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    function _cobrarSuscripcionDedicatorias($numero, $token) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
            'token' => $token
        );
        try {
            $response = $this->_clienteSoap->cobrarSuscripcionDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarSuscripcionDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    function _generarCodigoControlEnDedicatorias($numero, $numdestino, $catalogo, $tarifaCobrada) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
            'numdestino' => $numdestino,
            'catalogo' => $catalogo,
            'tarifaCobrada' => $tarifaCobrada
        );
        try {
            $response = $this->_clienteSoap->generarCodigoControlEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('generarCodigoControlEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    function _cobrarDemandaDedicatorias($numero,$token) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
            'token' => $token
        );
        try {
            $response = $this->_clienteSoap->cobrarDemandaDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarDemandaDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _suscribirADedicatorias($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->suscribirADedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('suscribirADedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerSMSLinkEnDedicatorias($codigo) {
        $params = array(
            'codigo' => $codigo,
        );
        try {
            $response = $this->_clienteSoap->obtenerSMSLinkEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerSMSLinkEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    function _asignarCreditoEnDedicatorias($numero, $tarifa) {
        $params = array(
            'numuser' => $numero,
            'tarifa' => $tarifa
        );
        try {
            $response = $this->_clienteSoap->asignarCreditoEnDedicatorias($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('asignarCreditoEnDedicatorias', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

}
