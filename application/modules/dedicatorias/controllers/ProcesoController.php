<?php

class Dedicatorias_ProcesoController extends Core_Controller_ActionDedicatorias {

    protected $_sessionDedicatoria;

    public function init() {
        parent::init();
        $this->_helper->layout->setLayout('dedicatorias/layout-avanzado');
        $this->_sessionDedicatoria = new Zend_Session_Namespace('sessionDedicatoria');
        $this->_datosUsuario = $this->estaSuscritoADedicatorias();
    }

    public function confirmarDedicatoriaAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                $this->view->catalogo = $dataForm['catalogo'];
                $this->view->artista = $dataForm['artista'];
                $this->view->tema = $dataForm['tema'];
                $this->view->action = '/pe/dedicatorias/generar-mensaje';
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/dedicatorias');
            }
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if (isset($catalogo) && $catalogo != '') {
                $rsTieneCreditos = $this->tieneCreditosEnDedicatorias();
                if ($rsTieneCreditos == TRUE) {
                    $this->_sessionDedicatoria->detalleMensaje['precio'] = 'GRATIS';
                } else {
                    $this->_sessionDedicatoria->detalleMensaje['precio'] = 'S/ 5.00';
                }
                $nombre_fichero = APPLICATION_PATH . '/../public/dinamic/dedicatorias/basico360/musica/' . $catalogo . '.jpg';
                if (file_exists($nombre_fichero)) {
                    $this->_sessionDedicatoria->detalleMensaje['imagen'] = $catalogo . '.jpg';
                } else {
                    $this->_sessionDedicatoria->detalleMensaje['imagen'] = 'default.gif';
                }
                $detalleMusica = $this->_GetResultSoap->_obtenerCancionEnDedicatorias($catalogo);
                $this->_sessionDedicatoria->detalleMensaje['artista'] = $detalleMusica->obtenerCancionEnDedicatoriasResult->artista;
                $this->_sessionDedicatoria->detalleMensaje['tema'] = $detalleMusica->obtenerCancionEnDedicatoriasResult->tema;
                $this->_sessionDedicatoria->detalleMensaje['codigo'] = $detalleMusica->obtenerCancionEnDedicatoriasResult->codigo;
                $this->view->catalogo = $this->_sessionDedicatoria->detalleMensaje['codigo'];
                $this->view->artista = $this->_sessionDedicatoria->detalleMensaje['artista'];
                $this->view->tema = $this->_sessionDedicatoria->detalleMensaje['tema'];
                $this->view->precio = $this->_sessionDedicatoria->detalleMensaje['precio'];
                $this->view->imagen = $this->_sessionDedicatoria->detalleMensaje['imagen'];
                $this->view->action = '/pe/dedicatorias/generar-mensaje';
            } else {
                $this->_redirect('/pe/dedicatorias');
            }
        }
    }

    public function confirmarDesuscripcionAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                $desuscribirDeDedicatorias = $this->desuscribirDeDedicatorias();
                $this->_redirect( $this->_config['app']['Entretenimiento']);
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/dedicatorias');
            }
        } else {
            
        }
    }
    
    public function generarMensajeAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                if (empty($dataForm['numeroDestino']) || empty($dataForm['dedicatoria'])) {
                    $this->_flashMessage->success('Faltan ingresar datos');
                    $this->_redirect('/pe/dedicatorias/generar-mensaje');
                }
                if (ctype_digit($dataForm['numeroDestino']) && strlen(trim($dataForm['numeroDestino'])) == 9) {
                    
                } else {
                    $this->_flashMessage->success('Ingresar datos correctos');
                    $this->_redirect('/pe/dedicatorias/generar-mensaje');
                }
                $rsSolicitarCompraToken = $this->solicitarCompraToken();
                  $this->_sessionDedicatoria->detalleMensaje['token'] = $rsSolicitarCompraToken->token;  // Save Value
                $this->_sessionDedicatoria->detalleMensaje['numeroDestino'] = $dataForm['numeroDestino'];  // Save Value
                $this->_sessionDedicatoria->detalleMensaje['dedicatoria'] = $dataForm['dedicatoria'];  // Save Value
                $this->_redirect('/pe/dedicatorias/detalle-mensaje');
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/dedicatorias');
            }
        }
        if (isset($this->_sessionDedicatoria->detalleMensaje['numeroDestino'])) {
            $this->view->numeroDestino = $this->_sessionDedicatoria->detalleMensaje['numeroDestino'];
            $this->view->dedicatoria = $this->_sessionDedicatoria->detalleMensaje['dedicatoria'];
        }
        $this->view->action = '/pe/dedicatorias/generar-mensaje';
    }

    public function detalleMensajeAction() {
        $this->view->action = '/pe/dedicatorias/detalle-mensaje';
        $this->view->catalogo = $this->_sessionDedicatoria->detalleMensaje['codigo'];
        $this->view->artista = $this->_sessionDedicatoria->detalleMensaje['artista'];
        $this->view->tema = $this->_sessionDedicatoria->detalleMensaje['tema'];
        $this->view->numeroDestino = $this->_sessionDedicatoria->detalleMensaje['numeroDestino'];
        $this->view->dedicatoria = $this->_sessionDedicatoria->detalleMensaje['dedicatoria'];
        $this->view->precio = $this->_sessionDedicatoria->detalleMensaje['precio'];
        $this->view->imagen = $this->_sessionDedicatoria->detalleMensaje['imagen'];
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
//                $rsTieneCreditos = $this->tieneCreditosEnDedicatorias();
//                if ($rsTieneCreditos == TRUE) {
//                    $tarifa = (int) $this->consumirCreditoEnDedicatorias();
//                } else {
//                    if ($this->_datosUsuario->esFreeUser == false) {
                     $cobrarRtDemanda = $this->cobrarDemandaDedicatorias($this->_sessionDedicatoria->detalleMensaje['token']);
                        if ($cobrarRtDemanda->estado == true) {
                            $tarifa = $cobrarRtDemanda->tarifa;
                            $this->_ModelLog->saveCdrCobros($tarifa, 1, 'demanda', 'dedicatorias');
                        } else {
                            $this->_ModelLog->saveCdrCobros(null, 0, 'demanda', 'dedicatorias');
                            $this->redirect( $this->_config['app']['Entretenimiento'].'?estado=' . $cobrarRtDemanda->xbiResultado);
                        }
//                    } else {
//                        $tarifa = 66; // CODIGO 66 = TARIFA LIBRE
//                    }
//                }

                //FALTA SERVICIO PARA GENERAR CODIGO DE ASIGNACIONDE DEDICATORIA
                
                $generarCodigoControlEnDedicatorias = $this->generarCodigoControlEnDedicatorias('51'.$this->_sessionDedicatoria->detalleMensaje['numeroDestino'],$this->_sessionDedicatoria->detalleMensaje['codigo'],$tarifa);
                $this->_sessionDedicatoria->detalleMensaje['codogoControl'] = $generarCodigoControlEnDedicatorias;  // Save Value
                $this->view->numeroDestino = $this->_sessionDedicatoria->detalleMensaje['numeroDestino'];
                $this->view->dedicatoria = $this->_sessionDedicatoria->detalleMensaje['dedicatoria'];
                $this->_redirect('/pe/dedicatorias/confirmacion-envio');
                
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/dedicatorias');
            }
        }
    }

    public function confirmacionEnvioAction() {
        $this->view->catalogo = $this->_sessionDedicatoria->detalleMensaje['codigo'];
        $this->view->artista = $this->_sessionDedicatoria->detalleMensaje['artista'];
        $this->view->tema = $this->_sessionDedicatoria->detalleMensaje['tema'];
        $this->view->precio = $this->_sessionDedicatoria->detalleMensaje['precio'];
        $this->view->imagen = $this->_sessionDedicatoria->detalleMensaje['imagen'];
        $this->view->action = '/pe/dedicatorias/confirmacion-envio';
        if ($this->_request->isPost()) {
            try {
                //ACTIVAR LLAMADA Y ENVIAR SMS DE AVISO 
                
               // $obtenerDatoDedicatoriasEnDedicatorias=$this->obtenerDatoDedicatoriasEnDedicatorias( $this->_sessionDedicatoria->detalleMensaje['codogoControl']);
                $this->activarLlamadaEnDedicatoria($this->_sessionDedicatoria->detalleMensaje['codogoControl'],$this->_sessionDedicatoria->detalleMensaje['dedicatoria']);
               
                $this->_flashMessage->success('La Dedicatoria ha sido enviada correctamente');
                $this->_redirect('/pe/dedicatorias');
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/dedicatorias');
            }
        } else {
//            $this->acuseReciboCompra($this->_sessionDedicatoria->detalleMensaje['token']);
            $this->view->numeroDestino = $this->_sessionDedicatoria->detalleMensaje['numeroDestino'];
            $this->view->dedicatoria = $this->_sessionDedicatoria->detalleMensaje['dedicatoria'];
        }
    }

    public function preSuscripcionAction() {
        if ($this->_datosUsuario->estaSuscrito == true) {
            $this->_flashMessage->success('Ud. ya se encuentra suscrito');
            $this->_redirect('/pe/dedicatorias');
        }
        
        $this->view->entelEntretenimiento = $this->_config['app']['Entretenimiento'];
        $this->view->action = '/pe/dedicatorias/suscripcion';
    }

    public function suscripcionAction() {
        $this->view->entelEntretenimiento = $this->_config['app']['Entretenimiento'];
        if ($this->_datosUsuario->estaSuscrito == true) {
            $this->_flashMessage->success('Ud. ya se encuentra suscrito');
            $this->_redirect('/pe/dedicatorias');
        } else {
            $this->view->action = '/pe/dedicatorias/suscripcion';
            if ($this->_request->isPost()) {
                try {
//            $this->desuscribirDeDedicatorias();
                    $rsSolicitarCompraToken = $this->solicitarCompraToken();
                    $this->_sessionDedicatoria->detalleMensaje['token'] = $rsSolicitarCompraToken->token;  // Save Value
                    $suscribirADedicatorias = $this->suscribirADedicatorias();
                   // var_dump($suscribirADedicatorias);exit;
                    if ($suscribirADedicatorias->estado == TRUE) {
                        $this->_flashMessage->info($suscribirADedicatorias->mensajeSuscripcionOK);
                        $this->_redirect('/pe/dedicatorias/confirma-suscripcion');
                    } else {
                        $this->_flashMessage->success('Ud. ya se encuentra suscrito');
                        $this->_redirect('/pe/dedicatorias');
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->_redirect('/pe/dedicatorias');
                }
            }
        }
    }

    public function confirmaSuscripcionAction() {
        $this->view->action = '/pe/dedicatorias/confirma-suscripcion';
        if ($this->_request->isPost()) {
            try {
                // $this->desuscribirDeDedicatorias();
                $cobrarSuscripcion = $this->cobrarSuscripcionDedicatorias($this->_sessionDedicatoria->detalleMensaje['token']);
                if ($cobrarSuscripcion->estado == true) {
                    $tarifa = $cobrarSuscripcion->tarifa;
                    $this->asignarCreditoEnDedicatorias($tarifa);
                    $this->_ModelLog->saveCdrCobros(null, 1, 'suscripcion', 'dedicatorias');
                    $this->_flashMessage->success('Bienvenido al servicio DEDICATORIAS,elige la cancion que deseas enviar. ! Envia tu primera dedicatoria de REGALO!');
                    $this->_redirect('/pe/dedicatorias');
                } elseif ($this->_datosUsuario->esFreeUser == true) {
                    $this->_redirect('/pe/dedicatorias');
                } else {
                    $this->_ModelLog->saveCdrCobros(null, 0, 'suscripcion', 'dedicatorias');
                    $this->_redirect( $this->_config['app']['Entretenimiento'].'?estado=' . $cobrarSuscripcion->xbiResultado);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/dedicatorias');
            }
        }
    }
    
   
//app.Entretenimiento
    public function legalAction() {
        
    }

}
