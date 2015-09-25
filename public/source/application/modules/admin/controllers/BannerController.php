<?php

class Admin_BannerController extends Core_Controller_ActionAdmin {

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
        $obj = new Application_Entity_DataTable('Banner', $iDisplayLength, $sEcho, true);
        $obj->setIconAction($this->action());
        $query = "";
        $query.=!empty($sSearch) ? " AND descripcion like '%" . $sSearch . "%' OR posicion = '$sSearch' " : " ";
        $obj->setSearch($query);
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($obj->getQuery($iDisplayStart, $iDisplayLength)));
    }

    public function editAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->_getParam('id', 0);
        $form = new Admin_Form_Banner();
        if (!empty($id)) {
            $obj = new Application_Entity_RunSql('Banner');
            $obj->getone = $id;
            $dataObj = $obj->getone;
            $form->populate($dataObj);
        }
        $this->view->titulo = "Editar Banner";
        $this->view->submit = "Guardar cambios";
        $this->view->action = "/admin/banner/new";
        $form->setDecorators(array(array('ViewScript',
                array('viewScript' => 'forms/_formBanner.phtml'))));
        echo $form;
    }

    public function newAction() {
        $form = new Admin_Form_Banner();
        $obj = new Application_Entity_RunSql('Banner');
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                $msj = array();
                if (!$form->basico128->receive() || !$form->avanzado->receive() ||
                        !$form->basico240->receive() || !$form->basico360->receive()) {
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
                        rename($form->avanzado->getFileName(), ROOT_IMG_DINAMIC . '/banners/avanzado/' . $nombre);
                        rename($form->basico128->getFileName(), ROOT_IMG_DINAMIC . '/banners/basico128/' . $nombre);
                        rename($form->basico240->getFileName(), ROOT_IMG_DINAMIC . '/banners/basico240/' . $nombre);
                        rename($form->basico360->getFileName(), ROOT_IMG_DINAMIC . '/banners/basico360/' . $nombre);
                    }
                    $dataForm['fecha_edicion'] = date('Y-m-d H:i:s');
                    if (empty($dataForm['idbanner'])) {
                        $dataForm['fecha_creacion'] = date('Y-m-d H:i:s');
                        $dataForm['nombre'] = $nombre;
                        $obj->save = $dataForm;
                    } else {
                        if (!isset($nombres[0]) || $nombres[0] == '') {
                            $modelBanner = new Admin_Model_Banner();
                            $imagen = $modelBanner->idImg($dataForm['idbanner']);
                            $nombre = $imagen["nombre"];
                        }
                        $dataForm['nombre'] = $nombre;
                        $obj->edit = $dataForm;
                    }
                    $this->_redirect('/admin/banner');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->view->titulo = "Nuevo Banners";
            $this->view->submit = "Guardar";
            $this->view->action = "/admin/banner/new";
            $form->addDecoratorCustom('forms/_formBanner.phtml');
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
                $obj = new Application_Entity_RunSql('Banner');
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
        $action = "<a class=\"tblaction ico-edit\" title=\"Editar\" href=\"/admin/banner/edit?id=__ID__\">Editar</a>
                    <a data-id=\"__ID__\" class=\"tblaction ico-delete\" title=\"Eliminar\"  href=\"/admin/banner/delete?id=__ID__\">Eliminar</a>";
        return $action;
    }

}
