<?php

/**
 * Description of User
 *
 * @author Josmel
 */
class Application_Model_ConfigPerfil extends Core_Model {

    public function saveCdr($userAgent, $result, $portal = null) {
        $datos = $this->obtenerPerfilNumero();
        $name = date('YmdH');
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/cdr/' . $portal . '/' . $name . ".moso");
        $formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
        $writer->setFormatter($formatter);
        $log = new Zend_Log($writer);
        $mensaje = $datos['fecha'] . "," . $datos['hora'] . "," . $_SERVER['REMOTE_ADDR']
                . "," . $_SERVER['SERVER_ADDR'] . "," . $datos['telefono'] . "," . 'PERFIL:'. $datos['perfil']
                . "," . $result . "," . $userAgent;
        $log->info($mensaje);
    }

    public function saveCdrCobros($tarifa = null, $estado, $tipo, $portal) {
        if ($estado == 1) {
            $estado = 'TRUE';
        } else {
            $estado = 'FALSE';
        }
        $datos = $this->obtenerPerfilNumero();
        $name = date('YmdH');
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/cdr/' . $portal . '/' . $name . ".cobro");
        $formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
        $writer->setFormatter($formatter);
        $log = new Zend_Log($writer);
        $mensaje = $datos['fecha'] . "," . $datos['hora'] . "," . 'PERFIL:'. $datos['perfil']
                . "," . $tarifa . "," . $estado . "," . $tipo . "," . $datos['telefono'];
        $log->info($mensaje);
    }

    public function saveCdrDescargas($codigoCatalogo, $codigoControl, $portal) {
        $datos = $this->obtenerPerfilNumero();
        $name = date('YmdH');
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/cdr/' . $portal . '/' . $name . ".descarga");
        $formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
        $writer->setFormatter($formatter);
        $log = new Zend_Log($writer);
        $mensaje = $datos['fecha'] . "," . $datos['hora'] . "," . 'PERFIL:'. $datos['perfil']
                . "," . $codigoCatalogo . "," . $codigoControl . "," . $datos['telefono'];
        $log->info($mensaje);
    }

    public function saveCdrLog($url, $message, $portal) {
        $datos = $this->obtenerPerfilNumero();
        $name = date('YmdH');
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/cdr/' . $portal . '/' . $name . ".log");
        $formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
        $writer->setFormatter($formatter);
        $log = new Zend_Log($writer);
        $mensaje = $datos['fecha'] . "," . $datos['hora'] . "," . 'PERFIL:'. $datos['perfil']
                . "," . $url . "," . $message . "," . $datos['telefono'];
        $log->info($mensaje);
    }

    function obtenerPerfilNumero() {
        $ObtenerNumero = new Core_Utils_Utils();
        $Perfil = $ObtenerNumero->obtenerPerfil();
        $telefono = $ObtenerNumero->obtenerNumero();
        $datos = array(
            'telefono' => $telefono,
            'perfil' => $Perfil,
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
        );
        return $datos;
    }

}
