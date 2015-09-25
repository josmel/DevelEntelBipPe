<?php

ini_set("soap.wsdl_cache_enabled", "0");

class App_Controller_Action_Helper_GetResultSoap extends Zend_Controller_Action_Helper_Abstract {

    protected $_config;
    protected $_OPERADORA = 3;
    protected $_TIPO = 'fulltracks';

    public function __construct() {
        $this->_config = Zend_Registry::get('config');
        $this->_ModelLog = new Application_Model_ConfigPerfil();
        $this->_clienteSoap = new Zend_Soap_Client(
                $this->_config['resources']['view']['urlSoapFulltracks'], array('soap_version' => SOAP_1_1));
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

    public function _obtenerCancionEnFulltracks($codigo) {
        $params = array(
            'catalogo' => $codigo,
        );
        try {
            $response = $this->_clienteSoap->obtenerCancionEnFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerCancionEnFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _generarCodigoDescargaEnFulltracks($codigo, $numero) {
        $params = array(
            'catalogo' => $codigo,
            'numuser' => $numero,
            'tarifaCobrada' => 0,
        );
        try {
            $response = $this->_clienteSoap->generarCodigoDescargaEnFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('generarCodigoDescargaEnFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _confirmarDescargaEnFulltracks($codigo, $estado) {
        $params = array(
            'codigoControl' => $codigo,
            'estado' => $estado,
        );
        try {
            $response = $this->_clienteSoap->confirmarDescargaEnFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('confirmarDescargaEnFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _estaSuscritoAFulltracks($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->estaSuscritoAFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('estaSuscritoAFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _desuscribirDeFulltracks($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->desuscribirDeFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('desuscribirDeFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _cobrarSuscripcionFulltracks($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->cobrarSuscripcionFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarSuscripcionFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _cobrarDemandaFulltracks($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->cobrarDemandaFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('cobrarDemandaFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _suscribirAFulltracks($numero) {
        $params = array(
            'operadora' => $this->_OPERADORA,
            'numuser' => $numero,
        );
        try {
            $response = $this->_clienteSoap->suscribirAFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('suscribirAFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerControlDescargaEnFulltracks($codigoControl) {
        $params = array(
            'codigoControl' => $codigoControl,
        );
        try {
            $response = $this->_clienteSoap->obtenerControlDescargaEnFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerControlDescargaEnFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

    public function _obtenerSMSLinkEnFulltracks($codigo) {
        $params = array(
            'codigo' => $codigo,
        );
        try {
            $response = $this->_clienteSoap->obtenerSMSLinkEnFulltracks($params);
        } catch (SoapFault $ex) {
            $this->_ModelLog->saveCdrLog('obtenerSMSLinkEnFulltracks', $ex->getMessage(), $this->_TIPO);
            header("Location: /pe/error-servicios");
        }
        return $response;
    }

}
