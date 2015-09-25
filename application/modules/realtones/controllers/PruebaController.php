<?php

class Realtones_PruebaController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        //$config = $this->getConfig();
    }

    function descargaAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                 header("Location: http://174.121.234.90/Descargas/FileService/Descarga.aspx?ticket=461");
        exit;
                
//                if (!empty($dataForm['codigo'])) {
//                    $file = file_get_contents(APPLICATION_PATH . '/../multimedia/realtones/' . $dataForm['codigo']);
//                    $this->getResponse()
//                            ->setBody($file)
//                            ->setHeader('Content-Type', 'audio/mpeg3')
//                            ->setHeader('Content-Disposition', 'attachment; filename="' . $dataForm['codigo'] . '"')
//                            ->setHeader('Content-Length', strlen($file));
//                    $this->_helper->layout->disableLayout();
//                    $this->_helper->viewRenderer->setNoRender(true);
//                } else {
//                    $this->_redirect('/pe/realtones');
//                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/pe/realtones');
            }
        }
    }

    function reproductorAction() {
        $this->_helper->layout->setLayout('realtones/layout-descarga');
        $this->view->action = '/realtones/prueba/descarga';
    }

}
