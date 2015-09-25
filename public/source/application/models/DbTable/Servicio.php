<?php

class Application_Model_DbTable_Servicio extends Core_Db_Table {

    protected $_name = 'servicio';
        protected $_primary = "idservicio";

    const NAMETABLE = 'servicio';

    public function __construct($config = array(), $definition = null) {
        parent::__construct($config, $definition);
    }

    static function populate($params) {
        $data = array();
        if (isset($params['descripcion']))
            $data['descripcion'] = $params['descripcion'];
        if (isset($params['url']))
            $data['url'] = $params['url'];
        if (isset($params['img']))
            $data['img'] = $params['img'];
        if (isset($params['alt']))
            $data['alt'] = $params['alt'];
        if (isset($params['fecha_creacion']))
            $data['fecha_creacion'] = $params['fecha_creacion'];
        if (isset($params['fecha_edicion']))
            $data['fecha_edicion'] = $params['fecha_edicion'];
        if (isset($params['estado']))
            $data['estado'] = $params['estado'];
        return $data;
    }
    /**
     * 
     * @param obj DB $resulQuery
     */
    public function columnDisplay() {
        
        
        $rura= DINAMIC_URL . '/servicio/';
        return array('descripcion',"concat('$rura',img)", "IF(estado LIKE '1', 'Activo', 'Inactivo')");
    }
 
    public function getPrimaryKey() {
        return $this->_primary;
    }

    public function getWhereActive() {
        return " AND estado != 2";
    }

}
