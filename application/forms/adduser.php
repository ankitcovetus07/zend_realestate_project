<?php

class Application_Form_adduser extends Zend_Form {

    public function init() {
        $userId = new Zend_Form_Element_Hidden("id");

        $fname = new Zend_Form_Element_Text('first_name');

        $fname->setLabel('firstName')->setRequired(true)->setAttrib('size', 50);

        $fname->removeDecorator('label');

        $fname->removeDecorator('HtmlTag');





        $lname = new Zend_Form_Element_Text('last_name');

        $lname->setLabel('Last Name')->setRequired(true)->setAttrib('size', 50);

        $lname->removeDecorator('label');

        $lname->removeDecorator('HtmlTag');



        $email = new Zend_Form_Element_Text('email_address');

        $email->setLabel('E-mail')->setRequired(true)->setAttrib('size', 50);

        $email->removeDecorator('label');

        $email->removeDecorator('HtmlTag');

        $email->addValidator('EmailAddress', TRUE); // added true here

        $email->addValidator('NotEmpty', true);

        $email->addValidator(new Zend_Validate_Db_NoRecordExists('users', 'email_address'));


        $passwordautogen = new Zend_Form_Element_MultiCheckbox("passwordautogen");
        $passwordautogen->addMultiOption("1", "Auto Generate Passwords");


        $pass = new Zend_Form_Element_Text('password');

        $pass->setLabel('Password')->setAttrib('size', 50);

        $pass->removeDecorator('label');

        $pass->removeDecorator('HtmlTag');



        $type = new Zend_Form_Element_Select('type');
        $type->setLabel('User Type');
        $type->removeDecorator('label');

        $type->removeDecorator('HtmlTag');


        $loggedInUser = Zend_Auth::getInstance()->getIdentity();
        $userType['0'] = 'Select';
        if ($loggedInUser->type == 'superadmin' || $loggedInUser->type == 'admin') {
            $userType['admin'] = 'Escrow Agent';
        }

        $userType['salesagent'] = 'Sales Agent';

        $type->setMultiOptions($userType);



        $address = new Zend_Form_Element_Text('address');

        $address->setLabel('Address')->addFilter('StripTags')->removeDecorator('label')->setAttrib('size', 50)->addValidator('NotEmpty', true)->removeDecorator('HtmlTag');



        $agent_name = new Zend_Form_Element_Text('agent_name');

        $agent_name->setLabel("Name of Company/Firm/Brokerage")->addFilter('StripTags')->setAttrib('size', 50)->removeDecorator('label')->removeDecorator('HtmlTag');

        $Phone = new Zend_Form_Element_Text('phone');
        $Phone->setLabel("Phone")->addFilter('StripTags')->setAttrib('size', 50)->removeDecorator('label')->removeDecorator('HtmlTag');


        $position = new Zend_Form_Element_Text('position');

        $position->setLabel('Position')->addFilter('StripTags')->setAttrib('size', 50)->addValidator('NotEmpty', true)->removeDecorator('label')->removeDecorator('HtmlTag');



        $entity = new Zend_Form_Element_Select('entity');

        $entity->removeDecorator('label');

        $entity->removeDecorator('HtmlTag');

        $entity->setLabel('Type of Entity');

        $entity->addMultiOption('partnership', 'Partnership');

        $entity->addMultiOption('corporation', 'Corporation');

        $entity->addMultiOption('trust', 'Trust');
        $entity->addMultiOption('individual', 'individual');



        $right = new Zend_Form_Element_MultiCheckbox('rights');




        $user = array(
            1 => 'Sales Agent',
            11 => 'Add New or Existing Sales Agents under this Project',
            12 => 'Edit Existing Sales Agents under this Project',
            13 => 'User can view its Own Sales Agents under this Project',
            14 => 'User can view all Sales Agents under this Project',
            2 => 'Project',
            21 => 'Add New Projects under this Escrow Agent',
            22 => 'Edit Existing Projects under this Escrow Agent',
            23 => 'User can view its Own Projects under this Escrow Agent',
            24 => 'User can view all Projects under this Escrow Agent',
            3 => 'Purchaser',
            31 => 'Add New or Existing Purchasers & Deposits under this Project',
            32 => 'Edit Purchasers & Deposits under this Project unless locked',
            33 => 'Release or Refund Deposits under this Project unless locked',
            34 => 'User can view its Own Purchasers under this Project',
            35 => 'User can view all Purchasers under this Project',
            4 => 'Report',
            41 => 'Produce a Report of all Transactions to date under this Project',
            42 => 'Produce a Monthly Report of all Transaction under this Project',
            43 => 'Produce a Purchaser Report under this Project'
                );

if ($_SESSION['Zend_Auth'][storage]->type == 'admin' || $_SESSION['Zend_Auth'][storage]->type == 'salesagent') 
{
    if(strpos($_SESSION['Zend_Auth'][storage]->rights,',1,2,3,4')>0)
    {}else{$_SESSION['Zend_Auth'][storage]->rights = $_SESSION['Zend_Auth'][storage]->rights . ',1,2,3,4';}
}
//echo $_SESSION['Zend_Auth'][storage]->rights;

        $rights = explode(',', $_SESSION['Zend_Auth'][storage]->rights);

        foreach ($user as $k => $v) {
            if ($_SESSION['Zend_Auth'][storage]->type == "superadmin") 
            {
                      $right->addMultiOption($k, $v);
            } else 
                { 
                
                if (in_array($k, $rights)) 
                {
                    $right->addMultiOption($k, $v);
                }
            }
        }

        //$right->setRequired(true);



        $submit = new Zend_Form_Element_Submit('submit');

        $submit->removeDecorator('label');

        $submit->removeDecorator('HtmlTag');

        $this->addElements(array($userId, $fname, $lname, $email, $pass, $type, $address, $passwordautogen, $agent_name, $position, $entity, $Phone, $right, $submit));
    }

    public function customPopulate($values) {

        if (isset($values)) {

            parent::populate($values[0]->toArray());
        }
    }

}
?>
<script>
$(document).ready(function(){
   $("#rights-label").before("<div id='textdata'></div>");
   
});
</script>