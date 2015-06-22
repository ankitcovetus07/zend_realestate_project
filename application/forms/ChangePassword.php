<?php

class Application_Form_ChangePassword extends Zend_Form
{

    public function init()
    {
        $oldPassword = new Zend_Form_Element_Password("oldPassword");
        $oldPassword->setAttrib('style', 'width:300px;font-size:20px;')
            ->setRequired(true)
            ->removeDecorator('label')
            ->setLabel('Old Password')
            ->removeDecorator('HtmlTag');
     
        $newPassword = new Zend_Form_Element_Password("newPassword");
        $newPassword->setAttrib('style', 'width:300px;font-size:20px;')
            ->setRequired(true)
            ->removeDecorator('label')
            ->setLabel('Old Password')
            ->removeDecorator('HtmlTag');
        
        $verifyPassword = new Zend_Form_Element_Password("verifyPassword");
        $verifyPassword->setAttrib('style', 'width:300px;font-size:20px;')
            ->setRequired(true)
            ->removeDecorator('label')
            ->setLabel('Verify Password')
            ->removeDecorator('HtmlTag');

      
        $this->addElements(array($oldPassword,$newPassword,$verifyPassword));
    }
    
    public function isValid($data) 
    {
        $valid = parent::isValid($data);
        if ($data['newPassword'] !== $data['verifyPassword']) {
            $valid = false;
            $this->verifyPassword->addError('Passwords don\'t match.');
        }
        $userData = Zend_Auth::getInstance()->getIdentity();
        if($userData->password != md5($data['oldPassword'])){
            $valid = false;
            $this->oldPassword->addError('Invalid Password');
        }
        return $valid;
    }


}

