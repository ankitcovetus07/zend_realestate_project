<?php

class Application_Form_Client extends Zend_Form
{

	public $searchData;
	public function __construct($searchData)
  {
    $this->searchData = $searchData;
    $this->init();
  }

    public function init()
    {
		
        //$tdTag=array(array('data'=>'HtmlTag'), array('tag' => 'td'));
        //$labelTag=array('Label', array('tag' => 'td'));
       // $trTag=array(array('row'=>'HtmlTag'),array('tag'=>'tr'));
        $tdDecorator ='';
		$this->setName("clientform");
        $this->setMethod('post');
        $this->setAttrib('id', 'clientform');
		$auth = Zend_Auth::getInstance();
		$editClientId = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
		$clientId = new Zend_Form_Element_Hidden("id");
		$model = new Application_Model_Project();
		$projectList = $model->getUserProjectData($auth->getIdentity()->id);
		$projects = array();
		foreach($projectList as $key => $value){
			$projects[$value->id] = $value->name;
		}
		
		$suit_number = new Zend_Form_Element_Text('suit_number');
		$suit_number->setLabel('Suit #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $suit_number->addValidator('stringLength', false, array())->removeDecorator('label');
       // $suit_number->setDecorators($tdDecorator);
		
		$suit_unit_number = new Zend_Form_Element_Text('suit_unit_number');
		$suit_unit_number->setLabel('Unit #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $suit_unit_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$unit_number->setDecorators($tdDecorator);
		
		$suit_level = new Zend_Form_Element_Text('suit_level');
		$suit_level->setLabel('Suite Level')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $suit_level->addValidator('stringLength', false, array())->removeDecorator('label');
        //$suit_level->setDecorators($tdDecorator);
		
		$parking_number = new Zend_Form_Element_Text('parking_number');
		$parking_number->setLabel('Park #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $parking_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$parking_number->setDecorators($tdDecorator);
		
		$parking_unit_number = new Zend_Form_Element_Text('parking_unit_number');
		$parking_unit_number->setLabel('Park Unit #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $parking_unit_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$parking_unit_number->setDecorators($tdDecorator);
		
		$parking_level_number = new Zend_Form_Element_Text('parking_level_number');
		$parking_level_number->setLabel('Park Level #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $parking_level_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$parking_level_number->setDecorators($tdDecorator);
		
		$locker_number = new Zend_Form_Element_Text('locker_number');
		$locker_number->setLabel('Locker #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $locker_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$locker_number->setDecorators($tdDecorator);
		
		$locker_unit_number = new Zend_Form_Element_Text('locker_unit_number');
		$locker_unit_number->setLabel('Locker Unit	 #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $locker_unit_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$locker_unit_number->setDecorators($tdDecorator);
		
		$locker_level_number = new Zend_Form_Element_Text('locker_level_number');
		$locker_level_number->setLabel('Locker Level #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->addValidator('Digits');
        $locker_level_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$locker_level_number->setDecorators($tdDecorator);
		
		$fname = new Zend_Form_Element_Text('first_name');
		$fname->setLabel('firstName')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30);
        $fname->removeDecorator('label');
        //$fname->setDecorators($tdDecorator);
		$fname->addValidator('NotEmpty', true);
		if($editClientId =="" && isset($this->searchData[0])){
			$fname->setValue($this->searchData[0]['first_name']);
		}
        
        $authorized_signing_officer = new Zend_Form_Element_Text('authorized_signing_officer');
		$authorized_signing_officer->setLabel('AuthorizedSigningOfficer')->addFilter('StripTags')->setAttrib('size',30)->removeDecorator('label');
		if($editClientId =="" && isset($this->searchData[0])){
			$authorized_signing_officer->setValue($this->searchData[0]['authorized_signing_officer']);
		}
        $title = new Zend_Form_Element_Text('title');
		$title->setLabel('title')->addFilter('StripTags')->setAttrib('size',30)->removeDecorator('label');
		if($editClientId =="" && isset($this->searchData[0])){
			$title->setValue($this->searchData[0]['title']);
		}
                
                
        $lname = new Zend_Form_Element_Text('last_name');
		$lname->setLabel('Last Name')->addFilter('StripTags')->setAttrib('size',30)->removeDecorator('label');
		if($editClientId =="" && isset($this->searchData[0])){
			$lname->setValue($this->searchData[0]['last_name']);
		}
        //$lname->setDecorators($tdDecorator);
        
		$email = new Zend_Form_Element_Text('email_address');
		$email->setLabel('E-mail')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->removeDecorator('label');
		//$email->setDecorators($tdDecorator);
		$email->addValidator('EmailAddress',  TRUE  ); // added true here
		$email->addValidator('NotEmpty', true);
		if($editClientId =="" && isset($this->searchData[0])){
			$email->setValue($this->searchData[0]['email_address']);
		}
			
		$sin_number = new Zend_Form_Element_Text('sin_number');
		$sin_number->setLabel('Social Insurance Number')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->removeDecorator('label');
		if($editClientId =="" && isset($this->searchData[0])){
			$sin_number->setValue($this->searchData[0]['sin_number']);
		}
        //$sin_number->setDecorators($tdDecorator);

		$phone_number = new Zend_Form_Element_Text('phone_number');
		$phone_number->setLabel('Phone #')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->removeDecorator('label');
        //$phone_number->setDecorators($tdDecorator);	
		if($editClientId =="" && isset($this->searchData[0])){
			$phone_number->setValue($this->searchData[0]['phone_number']);
		}
		
		$date_of_birth = new Zend_Form_Element_Text('date_of_birth');
		$date_of_birth->setLabel('Date Of Birth')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->removeDecorator('label');
		if($editClientId =="" && isset($this->searchData[0])){
			$date_of_birth->setValue($this->searchData[0]['date_of_birth']);
		}
        //$date_of_birth->setDecorators($tdDecorator);

		$purchase_price = new Zend_Form_Element_Text('purchase_price');
		$purchase_price->setLabel('Purchase Price')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->removeDecorator('label');
        //$purchase_price->setDecorators($tdDecorator);	
        
		$address = new Zend_Form_Element_Text('address');
		$address->setLabel('Current Mailing Address')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true)->removeDecorator('label');
        //$address->setDecorators($tdDecorator);	
		if($editClientId =="" && isset($this->searchData[0])){
			$address->setValue($this->searchData[0]['address']);
		}

		
		
        
		$purchase_date = new Zend_Form_Element_Text('purchase_date');
		$purchase_date->setLabel('Purchase Date')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->removeDecorator('label');
        	
        
		$type = new Zend_Form_Element_Select('type');
		$type->setLabel('Type')->addFilter('StripTags')->removeDecorator('label');
		//$type->setDecorators($tdDecorator);
		$type->addMultiOption('individual','Individual');
		$type->addMultiOption('corporation','Corporation');
		$type->addMultiOption('trustee','Trustee');
		
        $project_id = new Zend_Form_Element_Select('project_id');
	$project_id->setLabel('Project')->addFilter('StripTags')->removeDecorator('label');
		//$project_id->setDecorators($tdDecorator);
        $project_id->setMultiOptions($projects);
    
		$terminate = new Zend_Form_Element_Select('terminate');
		$terminate->setLabel('Terminated')->addFilter('StripTags')->removeDecorator('label');
		$terminate->addMultiOption('yes','Yes');
		$terminate->addMultiOption('no','No');
		
			
	
		$created_by  = new Zend_Form_Element_Hidden('created_by',array(
               'value'   => $auth->getIdentity()->id,
               'filters'   => array('StringTrim','StripTags')
		));
		$submit = new Zend_Form_Element_Submit('submit');
		
		if($editClientId==''){
			$purchase_type = new Zend_Form_Element_Select('payment_type');
			$purchase_type->setLabel('Payment Type')->addFilter('StripTags')->removeDecorator('label');
			//$purchase_type->setDecorators($tdDecorator);
			$purchase_type->addMultiOption('purchase','Purchase');
			$purchase_type->addMultiOption('parking','Parking');
			$purchase_type->addMultiOption('locker','Locker');
			$purchase_type->addMultiOption('upgrade','Upgrade');
			
			$payment_method = new Zend_Form_Element_Select('payment_method');
			$payment_method->setLabel('Payment Method')->addFilter('StripTags')->removeDecorator('label')->setRequired(true);
			$payment_method->addMultiOption('cheque', 'Cheque');
			$payment_method->addMultiOption('bank_wire', 'Bank wire');
			$payment_method->addMultiOption('visa', 'Visa');
			
            $amount = new Zend_Form_Element_Text('amount');
            $amount->setLabel('Amount of Deposit')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->addValidator('Digits');
            $amount->addValidator('stringLength', false, array())->removeDecorator('label');
            //$unit_number->setDecorators($tdDecorator);

            $payment_date = new Zend_Form_Element_Text('payment_date');
            $payment_date->setLabel('Date of Deposite')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
            $payment_date->addValidator('stringLength', false, array())->removeDecorator('label');
            //$suit_level->setDecorators($tdDecorator);
            
            $authorized_signing_officer = new Zend_Form_Element_Text('authorized_signing_officer');
            $authorized_signing_officer->setLabel('Date of Deposite')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
            $authorized_signing_officer->addValidator('stringLength', false, array())->removeDecorator('label');
            
            
            $title = new Zend_Form_Element_Text('$title');
            $title->setLabel('Date of Deposite')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
            $title->addValidator('stringLength', false, array())->removeDecorator('label');
            
            $cheque_number = new Zend_Form_Element_Text('cheque_number');
            $cheque_number->setLabel('Check #')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->addValidator('Digits');
            $cheque_number->addValidator('stringLength', false, array())->removeDecorator('label');
            $this->addElements(array($clientId,$authorized_signing_officer,$title,$project_id,$suit_number,$suit_unit_number,
                $suit_level,$parking_number,$parking_unit_number,$parking_level_number,$locker_number,
                $locker_unit_number,$locker_level_number,$fname,$lname,$email,$sin_number,
                $phone_number,$date_of_birth,$purchase_price,$purchase_type,$address,$type,
                $created_by,$amount,$payment_date,$cheque_number,$payment_method,$purchase_date,$terminate,$submit
            ));
		}
		else{
            $this->addElements(array($clientId,$authorized_signing_officer,$title,$project_id,$suit_number,$suit_unit_number,$suit_level,$parking_number,$parking_unit_number,$parking_level_number,$locker_number,$locker_unit_number,$locker_level_number,$fname,$lname,$email,$sin_number,$phone_number,$date_of_birth,$purchase_price,$address,$type,$created_by,$purchase_date,$terminate,$submit));	
        }     
    }
    
    public function customPopulate($values) {
        if(isset($values)){
            parent::populate($values[0]->toArray());
        }
    }
}

