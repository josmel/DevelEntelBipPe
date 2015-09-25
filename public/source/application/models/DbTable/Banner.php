<?php

class Application_Model_DbTable_Banner extends Core_Db_Table {

    protected $_name = 'banners';
    protected $_primary = "idbanner";

    const NAMETABLE = 'banners';

    public function __construct($config = array(), $definition = null) {
        parent::__construct($config, $definition);
    }

    static function populate($params) {
        $data = array();
        if (isset($params['nombre']))
            $data['nombre'] = $params['nombre'];
        if (isset($params['descripcion']))
            $data['descripcion'] = $params['descripcion'];
        if (isset($params['enlace']))
            $data['enlace'] = $params['enlace'];
        if (isset($params['alt']))
            $data['alt'] = $params['alt'];
        if (isset($params['fecha_creacion']))
            $data['fecha_creacion'] = $params['fecha_creacion'];
        if (isset($params['fecha_edicion']))
            $data['fecha_edicion'] = $params['fecha_edicion'];
        if (isset($params['posicion']))
            $data['posicion'] = $params['posicion'];
        if (isset($params['estado']))
            $data['estado'] = $params['estado'];
       // $data = array_filter($data);
        //$data['estado'] = isset($params['estado']) ? $params['estado'] : 1;
        return $data;
    }
//<img width="50" src="http://local.adminwap/dinamic//banners/avanzado/nxqbhjpgckomuzi.gif">
    /**
     * 
     * @param obj DB $resulQuery
     */
    public function columnDisplay() {
        $rura= DINAMIC_URL . '/banners/avanzado/';
        return array('descripcion',"concat('$rura',nombre)", "concat('BANNER ',posicion)", "IF(estado LIKE '1', 'Activo', 'Inactivo')");
    }
 
    public function getPrimaryKey() {
        return $this->_primary;
    }

    public function getWhereActive() {
        return " AND estado != 2";
    }

}
