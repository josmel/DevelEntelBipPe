<?php

class Admin_Model_Juego extends Core_Model {

    protected $_tableJuego;

    public function __construct() {
        $this->_tableJuego = new Application_Model_DbTable_Juego();
    }

    public function idImg($id) {
        $smt = $this->_tableJuego->getAdapter()->select()
                        ->from($this->_tableJuego->getName(), array('img'))
                        ->where("idjuego = ?", $id)->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    public function eliminarBanner($id) {
        $where = $this->_tableJuego->getAdapter()
                ->quoteInto('idjuego = ?', $id);
        $this->_tableJuego->delete($where);
    }

     public function JuegoAll() {
        $select = $this->_tableJuego->getAdapter()->select()
                        ->from($this->_tableJuego->getName(), array('nombre', 'url', 'img'))
                        ->where('estado LIKE ?', '1')->query();
        $result = $select->fetchAll();
        $select->closeCursor();
        if (empty($result)) {
            $result = $result[0];
        }
        return $result;
    }


}
