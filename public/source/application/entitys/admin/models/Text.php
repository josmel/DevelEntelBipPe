<?php

class Admin_Model_Text extends Core_Model {

    protected $_tableText;

    public function __construct() {
        $this->_tableText = new Application_Model_DbTable_Text();
    }


     public function TextAll() {
        $select = $this->_tableText->getAdapter()->select()
                        ->from($this->_tableText->getName(), array('descripcion', 'url', 'alt'))
                        ->where('estado LIKE ?', '1')->query();
        $result = $select->fetchAll();
        $select->closeCursor();
        if (empty($result)) {
            $result = $result[0];
        }
        return $result;
    }


}
