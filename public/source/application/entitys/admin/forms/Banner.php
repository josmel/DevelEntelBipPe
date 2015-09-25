<?php

class Admin_Form_Banner extends Core_Form_Form {

    public function init() {
        $obj = new Application_Model_DbTable_Banner();
        $primaryKey = $obj->getPrimaryKey();
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setAttrib('idtCp', $primaryKey);
        $this->setAction('/cp/new');
        $e = new Zend_Form_Element_Hidden($primaryKey);
        $this->addElement($e);
        $e = new Zend_Form_Element_Select('posicion');
        $e->setMultiOptions(array(
            1 => 'BANNER 1',
            2 => 'BANNER 2',
            3 => 'BANNER 3',
            4 => 'BANNER 4',
            5 => 'BANNER 5',
            6 => 'BANNER 6',
            7 => 'BANNER 7',
            8 => 'BANNER 8',
            9 => 'BANNER 9',
            10 => 'BANNER 10',
        ));
        $this->addElement($e);
        $e = new Zend_Form_Element_Checkbox('estado');
        $e->setValue(true);
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('descripcion');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('enlace');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('alt');
        $this->addElement($e);

        $i = new Zend_Form_Element_File('avanzado');
        $this->addElement($i);
        $this->getElement('avanzado')
                ->setDestination(ROOT_IMG_DINAMIC . '/banners/avanzado/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                // ->setValueDisabled( true )
                ->setRequired(false);

//
//        $i = new Zend_Form_Element_Image('submitImage');
//        $this->addElement($i);
//        $this->getElement('submitImage')
//                ->setImage(ROOT_IMG_DINAMIC . '/banners/avanzado/nxqbhjpgckomuzi.gif');
//      
//     

        $i = new Zend_Form_Element_File('basico128');
        $this->addElement($i);
        $this->getElement('basico128')
                ->setDestination(ROOT_IMG_DINAMIC . '/banners/basico128/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                //  ->setValueDisabled( true )
                ->setRequired(false);

        $i = new Zend_Form_Element_File('basico240');
        $this->addElement($i);
        $this->getElement('basico240')
                ->setDestination(ROOT_IMG_DINAMIC . '/banners/basico240/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                // ->setValueDisabled( true )
                ->setRequired(false);

        $i = new Zend_Form_Element_File('basico360');
        $this->addElement($i);
        $this->getElement('basico360')
                ->setDestination(ROOT_IMG_DINAMIC . '/banners/basico360/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                //  ->setValueDisabled( true )
                ->setRequired(false);

        foreach ($this->getElements() as $element) {
            $element->removeDecorator('Label');
            $element->removeDecorator('DtDdWrapper');
            $element->removeDecorator('HtmlTag');
        }
    }

    public function populate(array $values) {
        if (isset($values['estado']))
            $values['estado'] = $values['estado'] == 1 ? 1 : 0;

        parent::populate($values);
    }

}
