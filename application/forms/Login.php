<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setName("login");
        $this->setMethod('post');
             
        $username = new Zend_Form_Element_Text("username");
        $username->setAttrib('style', 'width:300px;font-size:20px;')
            ->setRequired(true)
            ->removeDecorator('label')
            ->setLabel('Username')
            ->removeDecorator('HtmlTag');
        
        $password = new Zend_Form_Element_Password("password");
        $password->setAttrib('style', 'width:300px;font-size:20px;')
            ->setRequired(true)
            ->removeDecorator('label')
            ->setLabel('Password')
            ->removeDecorator('HtmlTag');
     

      
        $this->addElements(array($username,$password));
    }


}

