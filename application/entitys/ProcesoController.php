<?php

class Realtones_ProcesoController extends Core_Controller_ActionRealtones {

    public function init() {
        parent::init();
        $this->_datosUsuario = $this->estaSuscrito();
    }

    public function suscripcionAction() {
        
        $this->view->action = '/pe/realtones/suscripcion';
        $this->view->mensajeConfirmacion = $this->_datosUsuario->mensajeConfirmacion;
        $this->view->numero = $this->_datosUsuario->numuser;
        if ($this->_request->isPost()) {
            //$dataForm = $this->_request->getPost();
            $suscribir = $this->suscribirARealTones();
            if ($suscribir->estado == true) {
                $this->_flashMessage->success($suscribir->mensajeSuscripcionOK);
                $this->_redirect('/pe/realtones/confirma-suscripcion');
            } else {
                $this->_flashMessage->success('Ud. ya se encuentra suscrito');
                $this->_redirect('/pe/realtones');
            }
        }
    }

    public function confirmaSuscripcionAction() {
        if ($this->_request->isPost()) {
            $cobrarRt = $this->cobrarSuscripcionRealTones();
            //  FALTA  HABILITAR ELA FECHA ACTUAL DE COBRO
            if ($cobrarRt->estado == true) {
                $this->_redirect('/pe/realtones');
            } elseif ($this->_datosUsuario->esFreeUser == true) {
                $this->_redirect('/pe/realtones');
            } else {
                $this->redirect('http://m.entretenimiento.entel.pe/?estado=' . $cobrarRt->xbiResultado);
            }
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if (isset($catalogo) && $catalogo != '') {
                $cobrarRt = $this->cobrarSuscripcionRealTones();
                if ($cobrarRt->estado == true) {
                    $this->_redirect('/pe/realtones/confirmar-descarga?catalogo=' . $catalogo);
                } elseif ($this->_datosUsuario->esFreeUser == true) {
                    $this->_redirect('/pe/realtones/confirmar-descarga?catalogo=' . $catalogo);
                } else {
                    $this->redirect('http://m.entretenimiento.entel.pe/?estado=' . $cobrarRt->xbiResultado);
                }
            }
            $this->view->action = '/pe/realtones/confirma-suscripcion';
        }
    }

    public function misComprasAction() {
        $this->view->perfil = $this->obtenerPerfil();
        $listarCompras = $this->_GetResultSoap->_listarCompraEnRealtones($this->obtenerNumero());
        $cantidadLista = count($listarCompras->listarCompraEnRealtonesResult->compraBE);
        if ($cantidadLista == 1 && $listarCompras->listarCompraEnRealtonesResult->compraBE->codigoCompra == 0) {
            $this->view->cantidad = 0;
        } else {
            $this->view->compras = $listarCompras->listarCompraEnRealtonesResult->compraBE;
            $this->view->cantidad = count($listarCompras->listarCompraEnRealtonesResult->compraBE);
        }
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                if (!empty($dataForm['archivo'])) {
                    $file = file_get_contents($this->_config['app']['downloadRt'] . '/' . $dataForm['archivo']);
                    $this->getResponse()
                            ->setBody($file)
                            ->setHeader('Content-Type', 'audio/mpeg3')
                            ->setHeader('Content-Disposition', 'attachment; filename="' . $dataForm['archivo'] . '"')
                            ->setHeader('Content-Length', strlen($file));
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                } else {

                    $this->_redirect('/pe/realtones/mis-compras');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/realtones/mis-compras');
            }
        }
    }

    public function cobrarDemanda() {
        if ($this->_request->isPost()) {
            $cobrarRt = $this->cobrarDemandaRealTones();
            return $cobrarRt->estado;
            // faltaobtener la tarifa para enviar  al codigo de descarga

            if ($cobrarRt->estado == true) {
                $this->_redirect('/pe/realtones');
            } else {

                $this->redirect('http://m.entretenimiento.entel.pe/?estado=' . $cobrarRt->xbiResultado);
            }
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if (isset($catalogo) && $catalogo != '') {

                // faltaobtener la tarifa para enviar  al codigo de descarga
                $cobrarRt = $this->cobrarDemandaRealTones();
                if ($cobrarRt->estado == true) {

                    $this->_redirect('/pe/realtones/confirmar-descarga?catalogo=' . $catalogo);
                } else {

                    $this->redirect('http://m.entretenimiento.entel.pe/?estado=' . $cobrarRt->xbiResultado);
                }
            }
        }
        //$this->view->action = '/pe/realtones/confirma-suscripcion-demanda';
    }

    public function confirmarDescargaAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                if (isset($dataForm['descargar']) && $dataForm['descargar'] == 'true') {
                    if ($this->tieneCreditosEnRealTones() == true) {
                        $tarifa = (int) $this->consumirCreditoEnRealTones();
                    } else {
                        if ($this->_datosUsuario->esFreeUser == false) {
                            $cobrarRtDemanda = $this->cobrarDemandaRealTones();
                            if ($cobrarRtDemanda->estado == true) {
                                $tarifa = $cobrarRtDemanda->tarifa;
                            } else {
                                $this->redirect('http://m.entretenimiento.entel.pe/?estado=' . $cobrarRtDemanda->xbiResultado);
                            }
                        } else {
                            $tarifa = 66; // CODIGO 66 = TARIFA LIBRE
                        }
                    }
                    $generarCodigoDescarga = $this->_GetResultSoap->_generarCodigoDescargaEnRealtones($dataForm['catalogo'], $this->obtenerNumero(), $tarifa);
                    $tiket = $generarCodigoDescarga->generarCodigoDescargaEnRealTonesResult;
                    $Match = $this->_config['resources']['view']['Match'];
                    $Utilcodificar = new Core_Utils_Utils();
                    $encodificado = $Utilcodificar->encode($Match . $tiket);
                    $this->_redirect('/pe/realtones/reproductor?dw=' . urlencode($encodificado));
                } else {
                    $this->view->catalogo = $dataForm['catalogo'];
                    $this->view->artista = $dataForm['artista'];
                    $this->view->tema = $dataForm['tema'];
                    $this->view->action = '/pe/realtones/confirmar-descarga';
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/realtones');
            }
        } else {
            $catalogo = $this->_getParam('catalogo', '');
            if (isset($catalogo) && $catalogo != '') {
                $detalleTonos = $this->_GetResultSoap->_obtenerCancionEnRealTones($catalogo);
                $this->view->catalogo = $catalogo;
                $this->view->artista = $detalleTonos->obtenerCancionEnRealTonesResult->artista;
                $this->view->tema = $detalleTonos->obtenerCancionEnRealTonesResult->tema;
                $this->view->action = '/pe/realtones/confirmar-descarga';
            } else {
                $this->_redirect('/pe/realtones');
            }
        }
        $this->view->action = '/pe/realtones/confirmar-descarga';
    }

    function descargaAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                if (!empty($dataForm['codigoControl']) && (int) $dataForm['codigoControl'] > 0) {
                    $this->_GetResultSoap->_confirmarDescargaEnRealtones($dataForm['codigoControl'], 1);
                    $file = file_get_contents($this->_config['app']['downloadRt'] . '/' . $dataForm['codigo']);
                    $this->getResponse()
                            ->setBody($file)
                            ->setHeader('Content-Type', 'audio/mpeg3')
                            ->setHeader('Content-Disposition', 'attachment; filename="' . $dataForm['codigo'] . '"')
                            ->setHeader('Content-Length', strlen($file));
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                } else {
                    $this->_redirect('/pe/realtones');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/realtones');
            }
        }
    }

    function reproductorAction() {
        $this->_helper->layout->setLayout('realtones/layout-descarga');
        $codigoTiket = $this->_getParam('dw', '');
        if (isset($codigoTiket) && $codigoTiket != '') {
            $Utilcodificar = new Core_Utils_Utils();
            $decodificar = $Utilcodificar->decode(urldecode($codigoTiket));
            $codigoControl = preg_replace("/[^0-9]/", "", $decodificar);
            $DatosCatalogo = $this->_GetResultSoap->_obtenerControlDescargaEnRealTones($codigoControl);
            if ($DatosCatalogo->obtenerControlDescargaEnRealTonesResult->numuser == $this->obtenerNumero()) {
                $this->view->codigoControl = $codigoControl;
                $this->view->action = '/pe/realtones/descarga';
                $this->view->codigo = $DatosCatalogo->obtenerControlDescargaEnRealTonesResult->archivo;
            } else {
                $this->_redirect('/pe/realtones');
            }
        } else {
            $this->_redirect('/pe/realtones');
        }
    }

}
