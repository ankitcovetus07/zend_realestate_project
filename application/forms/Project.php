<?php

class Application_Form_Project extends Zend_Form
{

    public function init()
    {
		
        $tdTag=array(array('data'=>'HtmlTag'), array('tag' => 'td'));
        $labelTag=array('Label', array('tag' => 'td'));
        $trTag=array(array('row'=>'HtmlTag'),array('tag'=>'tr'));
        $tdDecorator = array('ViewHelper','Description','Errors',$tdTag,$labelTag,$trTag);
        $this->setAttrib('id', 'proejctform');
		$auth = Zend_Auth::getInstance();
		$projectId = new Zend_Form_Element_Hidden("id");
        
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Project Name')->addFilter('StripTags')->setAttrib('size',50)->setRequired(true);
                $name->addValidator('stringLength', false, array(3, 100));
                $name->setDecorators($tdDecorator);
		
		$address = new Zend_Form_Element_Text('address');
		$address->setLabel('Address')->addFilter('StripTags')->setRequired(true)->setAttrib('size',30)->addValidator('NotEmpty', true);
		$address->setDecorators($tdDecorator);
		
		$project_type = new Zend_Form_Element_Select('project_type');
		$project_type->setLabel('Type')->addFilter('StripTags');
		$project_type->addMultiOption('0','Select');
		$project_type->addMultiOption('condo_units','Condo Building');
		$project_type->addMultiOption('town_houses','Houses');
		$project_type->setDecorators($tdDecorator);
		
		$status = new Zend_Form_Element_Select("status");
		$status->setLabel("Status")  
		->setName("status")
		->addFilter('StripTags');
	        
                $status->setDecorators($tdDecorator);
		$status->addMultiOption('active','Active');
		$status->addMultiOption('deactive','Deactive');
		
		$condo = new Zend_Form_Element_select('condo');
		$condo->setLabel('Is this a Condo?')->addFilter('StripTags');
		$condo->addMultiOption('yes','Yes');
		$condo->addMultiOption('no','No');
		$condo->setDecorators($tdDecorator);
 	
                
                $dwellingunits = new Zend_Form_Element_Text('dwellingunits');
		$dwellingunits->setLabel('Dwelling Units')->addFilter('StripTags')->setAttrib('size',50)->setRequired(true);
                $dwellingunits->setDecorators($tdDecorator);
                
                $numberofparking = new Zend_Form_Element_Text('numberofparking');
		$numberofparking->setLabel('Parking Unit')->addFilter('StripTags')->setAttrib('size',50)->setRequired(true);
                $numberofparking->setDecorators($tdDecorator);
                
                $numberstorage = new Zend_Form_Element_Text('numberstorage');
		$numberstorage->setLabel('Storage Unit')->addFilter('StripTags')->setAttrib('size',50)->setRequired(true);
                $numberstorage->setDecorators($tdDecorator);
	
                $created_by  = new Zend_Form_Element_Hidden('created_by',array(
               'value'   => $auth->getIdentity()->id,
               'filters'   => array('StringTrim','StripTags')
    ));
             
	$submit = new Zend_Form_Element_Submit('submit'); 
	$submit->setDecorators(array('ViewHelper','Description','Errors',array(array('data'=>'HtmlTag'), array('tag' => 'td','colspan'=>'2','align'=>'center')),$trTag));	 		
	$this->addElements(array($projectId,$name,$address,$project_type,$condo,$status,$created_by,$dwellingunits,$numberofparking,$numberstorage,$submit));
	$this->setDecorators(array('FormElements',array(array('data'=>'HtmlTag'),array('tag'=>'table','border'=>0)),'Form'));
       
    }
    
    public function customPopulate($values) {
        if(isset($values)){
            parent::populate($values[0]->toArray());
        }
    }
}

