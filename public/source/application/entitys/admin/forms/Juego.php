<?php

class Admin_Form_Juego extends Core_Form_Form {


    public function init() {
        $obj = new Application_Model_DbTable_Juego();
        $primaryKey = $obj->getPrimaryKey();
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setAttrib('idjuego', $primaryKey);
        $this->setAction('/admin/juego/new');
        $e = new Zend_Form_Element_Hidden($primaryKey);
        $this->addElement($e);
        $this->addElement($e);
        $e = new Zend_Form_Element_Checkbox('estado');
        $e->setValue(true);
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('url');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('nombre');
        $this->addElement($e);

        $i = new Zend_Form_Element_File('avanzado');
        $this->addElement($i);
        $this->getElement('avanzado')
                ->setDestination(ROOT_IMG_DINAMIC . '/juego/avanzado/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                // ->setValueDisabled( true )
                ->setRequired(false);

        $i = new Zend_Form_Element_File('basico360');
        $this->addElement($i);
        $this->getElement('basico360')
                ->setDestination(ROOT_IMG_DINAMIC . '/juego/basico360/')
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
