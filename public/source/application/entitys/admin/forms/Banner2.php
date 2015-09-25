<?php

class Admin_Form_Banner2 extends Core_Form_Form {

    public function init() {
        $obj = new Application_Model_DbTable_Banner2();
        $primaryKey = $obj->getPrimaryKey();
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setAttrib('idbanner', $primaryKey);
        $this->setAction('/banner2/index');
        $e = new Zend_Form_Element_Hidden($primaryKey);
        $this->addElement($e);
        $e = new Zend_Form_Element_Checkbox('chkEstado[]');
        $e->setValue(true);
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('txtTitulo[]');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('txtLink[]');
        $this->addElement($e);
        $e = new Zend_Form_Element_Hidden('txtImagen[]');
        $this->addElement($e);
        $i = new Zend_Form_Element_File('avanzado');
        $this->addElement($i);
        $this->getElement('avanzado[]')
                ->setDestination(ROOT_IMG_DINAMIC . '/banner/avanzado/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                // ->setValueDisabled( true )
                ->setRequired(false);

        $i = new Zend_Form_Element_File('basico128');
        $this->addElement($i);
        $this->getElement('basico128[]')
                ->setDestination(ROOT_IMG_DINAMIC . '/banner/basico128/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                //  ->setValueDisabled( true )
                ->setRequired(false);

        $i = new Zend_Form_Element_File('basico240');
        $this->addElement($i);
        $this->getElement('basico240[]')
                ->setDestination(ROOT_IMG_DINAMIC . '/banner/basico240/')
                ->addValidator('Size', false, 10024000) // limit to 100k
                ->addValidator('Extension', true, 'jpg,png,gif,jpeg')// only JPEG, PNG, and GIFs
                // ->setValueDisabled( true )
                ->setRequired(false);

        $i = new Zend_Form_Element_File('basico360');
        $this->addElement($i);
        $this->getElement('basico360[]')
                ->setDestination(ROOT_IMG_DINAMIC . '/banner/basico360/')
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

}
