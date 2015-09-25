<?php

class Admin_Form_Text extends Core_Form_Form {

    public function init() {
        $obj = new Application_Model_DbTable_Text();
        $primaryKey = $obj->getPrimaryKey();
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setAttrib('idtext', $primaryKey);
        $this->setAction('/admin/text/new');
        $e = new Zend_Form_Element_Hidden($primaryKey);
        $this->addElement($e);
        $this->addElement($e);
        $e = new Zend_Form_Element_Checkbox('estado');
        $e->setValue(true);
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('url');
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('descripcion');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('alt');
        $this->addElement($e);



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
