<?php

class Application_Model_DbTable_Musica extends Core_Db_Table {

    protected $_name = 'musica';
    protected $_primary = "idmusica";

    const NAMETABLE = 'musica';

    public function __construct($config = array(), $definition = null) {
        parent::__construct($config, $definition);
    }

    static function populate($params) {
        $data = array();
        if (isset($params['titulo']))
            $data['titulo'] = $params['titulo'];
        if (isset($params['url']))
            $data['url'] = $params['url'];
        if (isset($params['img']))
            $data['img'] = $params['img'];
        if (isset($params['fecha_creacion']))
            $data['fecha_creacion'] = $params['fecha_creacion'];
        if (isset($params['fecha_edicion']))
            $data['fecha_edicion'] = $params['fecha_edicion'];
        if (isset($params['estado']))
            $data['estado'] = $params['estado'];
        return $data;
    }
//<img width="50" src="http://local.adminwap/dinamic//banners/avanzado/nxqbhjpgckomuzi.gif">
    /**
     * 
     * @param obj DB $resulQuery
     */
    public function columnDisplay() {
        
        
        $rura= DINAMIC_URL . '/musica/avanzado/';
        return array('titulo',"concat('$rura',img)","IF(estado LIKE '1', 'Activo', 'Inactivo')");
    }
 
    public function getPrimaryKey() {
        return $this->_primary;
    }

    public function getWhereActive() {
        return " AND estado != 2";
    }

}
