<?php

class Admin_JuegoController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        
    }

    public function listAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_getAllParams();
        $iDisplayStart = isset($params['iDisplayStart']) ? $params['iDisplayStart'] : null;
        $iDisplayLength = isset($params['iDisplayLength']) ? $params['iDisplayLength'] : null;
        $sEcho = isset($params['sEcho']) ? $params['sEcho'] : 1;
        $sSearch = isset($params['sSearch']) ? $params['sSearch'] : '';
        $obj = new Application_Entity_DataTable('Juego', $iDisplayLength, $sEcho, true);
        $obj->setIconAction($this->action());
        $query = "";
        $query.=!empty($sSearch) ? " AND nombre like '%" . $sSearch . "%' " : " ";
        $obj->setSearch($query);
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($obj->getQuery($iDisplayStart, $iDisplayLength)));
    }

    public function editAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->_getParam('id', 0);
        $form = new Admin_Form_Juego();
        if (!empty($id)) {
            $obj = new Application_Entity_RunSql('Juego');
            $obj->getone = $id;
            $dataObj = $obj->getone;
            $form->populate($dataObj);
        }
        $this->view->titulo = "Editar Juego";
        $this->view->submit = "Guardar cambios";
        $this->view->action = "/admin/juego/new";
        $form->setDecorators(array(array('ViewScript',
                array('viewScript' => 'forms/_formJuego.phtml'))));
        echo $form;
    }

    public function newAction() {
        $form = new Admin_Form_Juego();
        $obj = new Application_Entity_RunSql('Juego');
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                $msj = array();
                if (!$form->avanzado->receive() || !$form->basico360->receive()) {
                    $msj[] = $form->getMessages();
                } else {
                    $nombre = "";
                    $code = "";
                    $fInfo = $form->avanzado->getFileInfo();
                    $nombres = explode('.', $fInfo['avanzado']['name']);
                    $ext = $nombres[count($nombres) - 1];
                    $code = Core_Utils_Utils::getRamdomChars(15, 'A');
                    $nombre = $code . '.' . $ext;
                    if (isset($nombres[0]) || $nombres[0] != '') {
                        rename($form->avanzado->getFileName(), ROOT_IMG_DINAMIC . '/juego/avanzado/' . $nombre);
                        rename($form->basico360->getFileName(), ROOT_IMG_DINAMIC . '/juego/basico360/' . $nombre);
                    }
                    $dataForm['fecha_edicion'] = date('Y-m-d H:i:s');
                    if (empty($dataForm['idjuego'])) {
                        $dataForm['fecha_creacion'] = date('Y-m-d H:i:s');
                        $dataForm['img'] = $nombre;
                        $obj->save = $dataForm;
                    } else {
                        if (!isset($nombres[0]) || $nombres[0] == '') {
                            $modelBanner = new Admin_Model_Juego();
                            $imagen = $modelBanner->idImg($dataForm['idjuego']);
                            $nombre = $imagen["img"];
                        }
                        $dataForm['img'] = $nombre;
                        $obj->edit = $dataForm;
                    }
                    $this->_redirect('/admin/juego');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->view->titulo = "Nuevo Juego";
            $this->view->submit = "Guardar";
            $this->view->action = "/admin/juego/new";
            $form->addDecoratorCustom('forms/_formJuego.phtml');
            echo $form;
        }
    }

    public function deleteAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->getParam('id');
        $rpta = array();
        if (!empty($id)) {
            try {
                $obj = new Application_Entity_RunSql('Juego');
                $obj->erase = $id;
                $rpta['msj'] = 'ok';
            } catch (Exception $e) {
                $rpta['msj'] = $e->getMessage();
            }
        } else {
            $rpta['msj'] = 'faltan datos';
        }
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-type', 'application/json; charset=UTF-8', true)
                ->appendBody(json_encode($rpta));
    }

    function action() {
        $action = "<a class=\"tblaction ico-edit\" title=\"Editar\" href=\"/admin/juego/edit?id=__ID__\">Editar</a>
                    <a data-id=\"__ID__\" class=\"tblaction ico-delete\" title=\"Eliminar\"  href=\"/admin/juego/delete?id=__ID__\">Eliminar</a>";
        return $action;
    }

}
