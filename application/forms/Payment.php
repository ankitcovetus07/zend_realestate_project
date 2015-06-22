
<?php

class Application_Form_Payment extends Zend_Form 
{

    private $client_id;
    private $project_id;
    private $suit;

    public function __construct($client_id, $project_id, $suit) 
    {
        $this->client_id = $client_id;
        $this->project_id = $project_id;
        $this->suit = $suit;
        $this->init();
    }

    public function init() 
    {
        $this->setName("paymentform");
        $this->setMethod('post');
        $this->setAttrib('id', 'paymentform');
        $auth = Zend_Auth::getInstance();
        $amount = new Zend_Form_Element_Text('amount');
        $amount->setLabel('Scheduled Payment Amount')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->addValidator('Float');
        $amount->addValidator('stringLength', false, array())->removeDecorator('label');
        
        //$financial_institution = new Zend_Form_Element_Text('financial_institution');
        //$financial_institution->setLabel('Financial Institution')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        //$financial_institution->addValidator('stringLength', false, array())->removeDecorator('label');
	$transit_number = new Zend_Form_Element_Text('transit_number');
        $transit_number->setLabel('Transit Number')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        $transit_number->addValidator('stringLength', false, array())->removeDecorator('label');
	//$account_number = new Zend_Form_Element_Text('account_number');
        //$account_number->setLabel('Account Number')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        //$account_number->addValidator('stringLength', false, array())->removeDecorator('label');
        //$address = new Zend_Form_Element_Text('address');
        //$address->setLabel('Address')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        //$address->addValidator('stringLength', false, array())->removeDecorator('label');
	//$telephone = new Zend_Form_Element_Text('telephone');
        //$telephone->setLabel('Telephone')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        //$telephone->addValidator('stringLength', false, array())->removeDecorator('label');
        
        $cc_expiry = new Zend_Form_Element_Text('cc_expiry');
        $cc_expiry->setLabel('VISA Expiry')->setAttrib('size', 40);
        $cc_expiry->removeDecorator('label');
        $cc_expiry->removeDecorator('HtmlTag');
        
        $cvv_number = new Zend_Form_Element_Text('cvv_number');
        $cvv_number->setLabel('VISA  CVV Code')->setAttrib('size', 40);
        $cvv_number->removeDecorator('label');
        $cvv_number->removeDecorator('HtmlTag');
       
		
       
        $payment_date = new Zend_Form_Element_Text('payment_date');
        $payment_date->setLabel('Payment date')->setAttrib('size', 40);
        $payment_date->removeDecorator('label');
        $payment_date->removeDecorator('HtmlTag');
        
        //$payment_date = new Zend_Form_Element_Text('payment_date');
        //$payment_date->setLabel('Cheque date')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        //$payment_date->addValidator('stringLength', false, array())->removeDecorator('label');
		
        
        
        $trance_date = new Zend_Form_Element_Text('trance_date');
        $trance_date->setLabel('Scheduled Payment Date')->setAttrib('size', 40);
        $trance_date->removeDecorator('label');
        $trance_date->removeDecorator('HtmlTag');
        
        //$trance_date = new Zend_Form_Element_Text('trance_date');
        //$trance_date->setLabel('trance date')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true);
        //$trance_date->addValidator('stringLength', false, array())->removeDecorator('label');
		
        
		
		//$nsf_refund_date = new Zend_Form_Element_Text('nsf_refund_date');
        //$nsf_refund_date->setLabel('NSF date')->addFilter('StripTags')->addValidator('NotEmpty', true);
        //$nsf_refund_date->addValidator('stringLength', false, array())->removeDecorator('label');


        
        $cheque_number = new Zend_Form_Element_Text('cheque_number');
        $cheque_number->setLabel('Cheque #')->setAttrib('size', 40);
        $cheque_number->removeDecorator('label');
        $cheque_number->removeDecorator('HtmlTag');
        
        //$cheque_number = new Zend_Form_Element_Text('cheque_number');
        //$cheque_number->setLabel('Cheque #')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->addValidator('Digits');
        //$cheque_number->addValidator('stringLength', false, array())->removeDecorator('label');
		
		
        
        $cc_number = new Zend_Form_Element_Text('cc_number');
        $cc_number->setLabel('VISA Number')->setAttrib('size', 40);
        $cc_number->removeDecorator('label');
        $cc_number->removeDecorator('HtmlTag');
        
        //$cc_number = new Zend_Form_Element_Text('cc_number');
        //$cc_number->setLabel('VISA Number')->addFilter('StripTags')->setRequired(true)->addValidator('NotEmpty', true)->addValidator('Digits');
        //$cc_number->addValidator('stringLength', false, array())->removeDecorator('label');
        
        
        
        $payment_method = new Zend_Form_Element_Select('payment_method');
        $payment_method->setLabel('Scheduled Payment type')->addFilter('StripTags')->removeDecorator('label')->setRequired(true);
        $payment_method->addMultiOption('0', 'Select');
        $payment_method->addMultiOption('cheque', 'Cheque');
		$payment_method->addMultiOption('bank_draft', 'Bank Draft');
        $payment_method->addMultiOption('wire_transfer', 'Bank Wire');
        $payment_method->addMultiOption('visa', 'Credit Card');
		$payment_method->addMultiOption('Cash_Receipt', 'Cash');
        
        
        
        $purchase_type = new Zend_Form_Element_Select('payment_type');
        $purchase_type->setLabel('Payment towards')->addFilter('StripTags')->removeDecorator('label')->setRequired(true);
        //$purchase_type->setDecorators($tdDecorator);
        $purchase_type->addMultiOption('0', 'Select');
        $purchase_type->addMultiOption('purchase', 'Total Purchase Price');
        $purchase_type->addMultiOption('parking', 'Parking Only');
        $purchase_type->addMultiOption('storage', 'Storage Only');
        $purchase_type->addMultiOption('upgrade', 'Upgrades Only');
        $purchase_type->setValue('purchase');


        
        $created_by = new Zend_Form_Element_Hidden('created_by', array(
            'value' => $auth->getIdentity()->id,
            'filters' => array('StringTrim', 'StripTags')
        ));
        $type = new Zend_Form_Element_Hidden('type', array(
            'value' => "payment",
            'filters' => array('StringTrim', 'StripTags')
        ));
        $clientId = new Zend_Form_Element_Hidden('client_id', array(
            'value' => $this->client_id,
            'filters' => array('StringTrim', 'StripTags')
        ));
        $projectId = new Zend_Form_Element_Hidden('project_id', array(
            'value' => $this->project_id,
            'filters' => array('StringTrim', 'StripTags')
        ));
        $suit_number = new Zend_Form_Element_Hidden('suit_number', array(
            'value' => $this->suit,
            'filters' => array('StringTrim', 'StripTags')
        ));
        
        
        
        $paymentId = new Zend_Form_Element_Hidden("id");

        
        
        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Payment Status')->addFilter('StripTags')->removeDecorator('label');
        $status->addMultiOption('Outstanding', 'Outstanding');
        $status->addMultiOption('Deposited', 'Deposited');
        $status->addMultiOption('Processed', 'Processed'); 
        $status->addMultiOption('Returned NSF', 'Returned NSF'); 
        #$status->addMultiOption('NSF', 'NSF');
        $status->addMultiOption('refunded', 'Refunded');
        $submit = new Zend_Form_Element_Submit('submit');
        //$NSF_fee = new Zend_Form_Element_Text('NSF_fee'); 
        //$NSF_fee->setLabel('NSF Fee')->addFilter('StripTags')->addValidator('Float');
        //$NSF_fee->addValidator('stringLength', false, array())->removeDecorator('label'); 

        $this->addElements(array($paymentId, $projectId, $clientId,$cc_expiry,$cvv_number,$Cheque_date, $amount,
            $payment_date,$trance_date, $cheque_number, $cc_number, $type, $created_by, $status, $suit_number, $purchase_type, $transit_number,
            $payment_method, $submit
        ));
    }

    public function customPopulate($values) {
        if (isset($values)) {
            parent::populate($values[0]->toArray());
        }
    }

}

