<?php

class Application_Form_Withdraw extends Zend_Form
{
	private $client_id;
	private $project_id;
	private $suit;

	public function __construct($client_id,$project_id,$suit)
	{
		$this->client_id = $client_id;
		$this->project_id= $project_id;
		$this->suit= $suit;
		$this->init();
	}
    public function init()
    {
		$this->setName("withdrawform");
        $this->setMethod('post');
        $this->setAttrib('id', 'withdrawform');
		$auth = Zend_Auth::getInstance();
		$amount = new Zend_Form_Element_Text('amount');
		$amount->setLabel('Amount of Withdraw')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->addValidator('Float');
        $amount->addValidator('stringLength', false, array())->removeDecorator('label');
        
		$payment_date = new Zend_Form_Element_Text('payment_date');
		$payment_date->setLabel('Date of Withdraw')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        $payment_date->addValidator('stringLength', false, array())->removeDecorator('label');
        
		
		$created_by  = new Zend_Form_Element_Hidden('created_by',array(
               'value'   => $auth->getIdentity()->id,
               'filters'   => array('StringTrim','StripTags')
		));
		$type  = new Zend_Form_Element_Hidden('type',array(
               'value'   => "withdraw",
               'filters'   => array('StringTrim','StripTags')
		));
		$clientId  = new Zend_Form_Element_Hidden('client_id',array(
               'value'   => $this->client_id,
               'filters'   => array('StringTrim','StripTags')
		));
		$projectId  = new Zend_Form_Element_Hidden('project_id',array(
               'value'   => $this->project_id,
               'filters'   => array('StringTrim','StripTags')
		));
		$paymentId = new Zend_Form_Element_Hidden("id");     
		
		$payment_type = new Zend_Form_Element_Select('widthdraw_type');
		$payment_type->setLabel('Type')->addFilter('StripTags')->removeDecorator('label');
		$payment_type->addMultiOption('tarion','Tarion');
		$payment_type->addMultiOption('regular','Ragular');
		$payment_type->addMultiOption('upgrade','Upgrade');
		$suit_number  = new Zend_Form_Element_Hidden('suit_number',array(
               'value'   => $this->suit,
               'filters'   => array('StringTrim','StripTags')
		));
		
		$submit = new Zend_Form_Element_Submit('submit');
        
		     
		$this->addElements(array($paymentId,$projectId,$clientId,$amount,$payment_date,$type,$created_by,$payment_type,$suit_number,$submit));
       
    }
    
    public function customPopulate($values) {
        if(isset($values)){
            parent::populate($values[0]->toArray());
        }
    }
}

