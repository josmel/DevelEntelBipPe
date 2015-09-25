<?php

class Admin_MusicaController extends Core_Controller_ActionAdmin {

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
        $obj = new Application_Entity_DataTable('Musica', $iDisplayLength, $sEcho, true);
        $obj->setIconAction($this->action());
        $query = "";
        $query.=!empty($sSearch) ? " AND titulo like '%" . $sSearch . "%' " : " ";
        $obj->setSearch($query);
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($obj->getQuery($iDisplayStart, $iDisplayLength)));
    }

    public function editAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->_getParam('id', 0);
        $form = new Admin_Form_Musica();
        if (!empty($id)) {
            $obj = new Application_Entity_RunSql('Musica');
            $obj->getone = $id;
            $dataObj = $obj->getone;
            $form->populate($dataObj);
        }
        $this->view->titulo = "Editar Música";
        $this->view->submit = "Guardar cambios";
        $this->view->action = "/admin/musica/new";
        $form->setDecorators(array(array('ViewScript',
                array('viewScript' => 'forms/_formMusica.phtml'))));
        echo $form;
    }

    public function newAction() {
        $form = new Admin_Form_Musica();
        $obj = new Application_Entity_RunSql('Musica');
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
                        rename($form->avanzado->getFileName(), ROOT_IMG_DINAMIC . '/musica/avanzado/' . $nombre);
                        rename($form->basico360->getFileName(), ROOT_IMG_DINAMIC . '/musica/basico360/' . $nombre);
                    }
                    $dataForm['fecha_edicion'] = date('Y-m-d H:i:s');
                    if (empty($dataForm['idmusica'])) {
                        $dataForm['fecha_creacion'] = date('Y-m-d H:i:s');
                        $dataForm['img'] = $nombre;
                        $obj->save = $dataForm;
                    } else {
                        if (!isset($nombres[0]) || $nombres[0] == '') {
                            $modelBanner = new Admin_Model_Musica();
                            $imagen = $modelBanner->idImg($dataForm['idmusica']);
                            $nombre = $imagen["img"];
                        }
                        $dataForm['img'] = $nombre;
                        $obj->edit = $dataForm;
                    }
                    $this->_redirect('/admin/musica');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->view->titulo = "Nueva Música";
            $this->view->submit = "Guardar";
            $this->view->action = "/admin/musica/new";
            $form->addDecoratorCustom('forms/_formMusica.phtml');
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
                $obj = new Application_Entity_RunSql('Musica');
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
        $action = "<a class=\"tblaction ico-edit\" title=\"Editar\" href=\"/admin/musica/edit?id=__ID__\">Editar</a>
                    <a data-id=\"__ID__\" class=\"tblaction ico-delete\" title=\"Eliminar\"  href=\"/admin/musica/delete?id=__ID__\">Eliminar</a>";
        return $action;
    }

}
