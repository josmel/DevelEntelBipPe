<?php

class Admin_TextController extends Core_Controller_ActionAdmin {

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
        $obj = new Application_Entity_DataTable('Text', $iDisplayLength, $sEcho, true);
        $obj->setIconAction($this->action());
        $query = "";
        $query.=!empty($sSearch) ? " AND descripcion like '%" . $sSearch . "%' " : " ";
        $obj->setSearch($query);
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($obj->getQuery($iDisplayStart, $iDisplayLength)));
    }

    public function editAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->_getParam('id', 0);
        $form = new Admin_Form_Text();
        if (!empty($id)) {
            $obj = new Application_Entity_RunSql('Text');
            $obj->getone = $id;
            $dataObj = $obj->getone;
            $form->populate($dataObj);
        }
        $this->view->titulo = "Editar Text Link";
        $this->view->submit = "Guardar cambios";
        $this->view->action = "/admin/text/new";
        $form->setDecorators(array(array('ViewScript',
                array('viewScript' => 'forms/_formText.phtml'))));
        echo $form;
    }

    public function newAction() {
        $form = new Admin_Form_Text();
        $obj = new Application_Entity_RunSql('Text');
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                $msj = array();
                    $dataForm['fecha_edicion'] = date('Y-m-d H:i:s');
                    if (empty($dataForm['idtext'])) {
                        $dataForm['fecha_creacion'] = date('Y-m-d H:i:s');
                        $obj->save = $dataForm;
                    } else {
                        $obj->edit = $dataForm;
                    }
                    $this->_redirect('/admin/text');
                
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->view->titulo = "Nuevo Text Link";
            $this->view->submit = "Guardar";
            $this->view->action = "/admin/text/new";
            $form->addDecoratorCustom('forms/_formText.phtml');
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
                $obj = new Application_Entity_RunSql('Text');
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
        $action = "<a class=\"tblaction ico-edit\" title=\"Editar\" href=\"/admin/text/edit?id=__ID__\">Editar</a>
                    <a data-id=\"__ID__\" class=\"tblaction ico-delete\" title=\"Eliminar\"  href=\"/admin/text/delete?id=__ID__\">Eliminar</a>";
        return $action;
    }

}
