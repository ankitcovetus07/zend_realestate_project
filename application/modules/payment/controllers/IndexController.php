<?php
class Payment_IndexController extends Zend_Controller_Action
{
    
    public function indexAction()
    {
        $this->view->message = "Welcome ......!!!!";
    }
    public function listAction()
    {
			
    }
		
   
    public function editAction()
    {
	
        $paymentId=$this->getRequest()->getParam('id');
        $client_id=$this->getRequest()->getParam('client_id');
        $project_id=$this->getRequest()->getParam('project_id');
        $Bank= new Application_Model_bank();
        $this->view->BankList    =   $Bank->read($project_id);
        $suit=$this->getRequest()->getParam('suit');
	    $payment = new Application_Model_Payment();
		//$this->_helper->layout()->disableLayout();
		$data=null;
        if(isset($paymentId))
        $data=$payment->find($paymentId);
        #echo ($data[0]['bankid']);
	$paymentForm = new Application_Form_Payment($client_id,$project_id,$suit);
	$paymentForm->setAction($this->view->url(array ('module' => 'payment','controller' => 'index','action' => 'save')));
        $translate = Zend_Registry::get('translate');
        $paymentForm->setTranslator($translate);
	$paymentForm->customPopulate($data);
	$this->view->paymentForm=$paymentForm;
	$this->view->bankidselect=$data[0]['bankid'];	
   }
   public function withdraweditAction()
    {
		
        $paymentId=$this->getRequest()->getParam('id');
        $client_id=$this->getRequest()->getParam('client_id');
        $project_id=$this->getRequest()->getParam('project_id');
        $suit=$this->getRequest()->getParam('suit');
	    $withdraw = new Application_Model_Payment();
		//$this->_helper->layout()->disableLayout();
		$data=null;
        if(isset($paymentId))
        $data=$withdraw->find($paymentId);
	    $withdrawForm = new Application_Form_Withdraw($client_id,$project_id,$suit);
		$withdrawForm->setAction($this->view->url(array ('module' => 'payment','controller' => 'index','action' => 'save')));
        $translate = Zend_Registry::get('translate');
        $withdrawForm->setTranslator($translate);
		$withdrawForm->customPopulate($data);
        $this->view->withdrawForm=$withdrawForm;
		
   }
    public function saveAction()
    { 
        
		$request = $this->getRequest();
		$postData= $request->getPost();
        
	    $paymentForm = new Application_Form_Payment($postData['client_id'],$postData['project_id'],$postData['suit_number']);
        $request = $this->getRequest();
        $paymentForm->isValid($request->getPost());
	    if(1) {
	        
            $data = $paymentForm->getValues();
            echo $_REQUEST['payment_type'];
	    echo $data['payment_type'];
            #die;
            $payment = new Application_Model_Payment();
			if(isset($data['id']) && $data['id'] == ""){
				if($data['payment_type']!="upgrade" && $data['payment_type']!= 'parking' && $data['payment_type']!='purchase' && $data['payment_type']!='storage' && $data['status']!="refunded")
                {
					$paidAmount = $payment->totalpaidamount($data['client_id'],$data['project_id'],$postData['suit_number']);
					$withdrawAmount = $payment->totalwithdrawamount($data['client_id'],$data['project_id'],$postData['suit_number']);
					$totalTarionAmount = $paidAmount - $withdrawAmount;
					$tarion = new Application_Model_Project();
					$tarionData = $tarion->tarionread();
					if($totalTarionAmount < $tarionData[0]['tarion_amount']){
						$lessamount = $tarionData[0]['tarion_amount']-$totalTarionAmount;
						if($data['amount'] > $lessamount){
							$regularAmount = $data['amount']-$lessamount;
							$data['regular_amount']= $regularAmount;
							$data['tarion_amount']= $lessamount;
						}else {
							$data['tarion_amount']= $data['amount'];
						}
					}
                    else
                    {
						$data['regular_amount']= $data['amount'];
					}
				}
			}
			$paymentOldData=array();
			$payment = new Application_Model_Payment();
	#print_r($postData);
        #die;
        $data['bankid']=$_REQUEST['bankid'];
		    $payment->save($data);
            $this->_helper->getHelper('FlashMessenger')->addMessage('Payment added succesfully !!!!!');
            $this->_helper->redirector("view","index","client",array('client' => $data['client_id']));
			if(isset($data['id']) && $data['id'] != ""){
				$paymentOldData = $payment->find($data['id']);
			}
		    
			if(isset($data['id']) && $data['id'] != ""){
				if(!empty($paymentOldData)){	
					if($paymentOldData[0]['status']!=$data['status']){
							$this->_sendMail($data);
					}
				}
                $this->_helper->getHelper('FlashMessenger')->addMessage('Payment information updated succesfully !!!!!');
            }    
            else{
				$this->_sendMail($data);
			    $this->_helper->getHelper('FlashMessenger')->addMessage('Payment added succesfully !!!!!');
			}	
				
           $this->_helper->redirector("view","index","client",array('client' => $data['client_id']));
        } else {
            $this->view->paymentForm = $paymentForm;
            return $this->render('edit');
        }
    }
    
    public function withdrawsaveAction()
    {
        $totalpayment=$totalwithdraw=0;
        $request = $this->getRequest();
		$postData= $request->getPost();
        $withdrawForm = new Application_Form_Withdraw($postData['client_id'],$postData['project_id'],$postData['suit_number']);
        $request = $this->getRequest();
        if($withdrawForm->isValid($request->getPost())) {
               
            $data = $withdrawForm->getValues();
            $payment = new Application_Model_Payment();
            if($data['widthdraw_type']=="tarion")
            {
               $totalpayment=$payment->totalpaidamount($postData['project_id']);
               $totalwithdraw=$payment->totalwithdrawamount($postData['project_id']);
            }
            
            
               
           if(($totalpayment-$totalwithdraw)>$data['amount'])
            {
                if($payment->withdrawamounttest($data['amount'],$postData['project_id'],$data,$payment))
                {
                 $this->_helper->getHelper('FlashMessenger')->addMessage('Withdraw added succesfully !!!!!');
                }
                else
                {
                   
                    $this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw this Amount because');
                }
                
            }
            else
            {
                    $this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw this Amount '.$data['amount'].' because You have '.($totalpayment-$totalwithdraw).' only.');
            }
            
$this->_helper->redirector("withdrawedit","index","payment",array('project_id' => $postData['project_id']));
          
        
        
        }
        
        
        
	    
		if($withdrawForm->isValid($request->getPost())) {
	        $data = $withdrawForm->getValues();
		    $payment = new Application_Model_Payment();
			
        if($data['widthdraw_type']!="upgrade")
        {
			if($data['widthdraw_type']=="tarion")
            {
$tarionAmount = $payment->totalpaidamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
$tarionwithdrawAmount = $payment->totalwithdrawamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
 
$totalAmount = $tarionAmount-$tarionwithdrawAmount;
           if($totalAmount >=$data['amount'])
                {
						$data['tarion_amount'] = $data['amount'];
				}
                else
                {
						$this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw !!!!!');
						 $this->view->withdrawForm = $withdrawForm;
						return $this->render('withdrawedit');
				}
			}
				if($data['widthdraw_type']=="regular")
                {
					$regularAmount = $payment->totalregularamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$regularwithdrawAmount = $payment->totalregularwithdrawamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$totalAmount = $regularAmount-$regularwithdrawAmount;
					if($totalAmount >=$data['amount'])
                    {
						$data['regular_amount'] = $data['amount'];
					}
                    else
                    {
                        
						$this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw !!!!!');
						$this->view->withdrawForm = $withdrawForm;
						return $this->render('withdrawedit');
					}
				}
		}
        else
        {
				
					$upgradeAmount = $payment->totalupgradeamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$upgradewithdrawAmount = $payment->totalupgraderwithdrawamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$totalAmount = $upgradeAmount-$upgradewithdrawAmount;
					if($totalAmount >=$data['amount'])
                    {
						$data['amount'] = $data['amount'];
					}
                    else
                    {
						$this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw !!!!!');
						$this->view->withdrawForm = $withdrawForm;
						return $this->render('withdrawedit');
					}
				
		}
			#unset($data['payment_type']);
		    $payment->save($data); 
            if(isset($data['id']) && $data['id'] != ""){
                $this->_helper->getHelper('FlashMessenger')->addMessage('Withdraw information updated succesfully !!!!!');
            }
            else
                $this->_helper->getHelper('FlashMessenger')->addMessage('Withdraw added succesfully !!!!!');
                $this->_helper->redirector("list","index","project");
        } 
        else 
        {
                $this->view->withdrawForm = $withdrawForm;
                return $this->render('withdrawedit');
        }
    }	
    
    
    
    
    
    
	public function withdrawsave1Action()    //Covets Change function name withdrawsaveAction to withdrawsave1Action
    {
        
        $request = $this->getRequest();
		$postData= $request->getPost();
        
	    $withdrawForm = new Application_Form_Withdraw($postData['client_id'],$postData['project_id'],$postData['suit_number']);
		$request = $this->getRequest();
        
	    
		if($withdrawForm->isValid($request->getPost())) {
	        $data = $withdrawForm->getValues();
		    $payment = new Application_Model_Payment();
			
        if($data['widthdraw_type']!="upgrade")
        {
			if($data['widthdraw_type']=="tarion")
            {
$tarionAmount = $payment->totalpaidamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
$tarionwithdrawAmount = $payment->totalwithdrawamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
 
$totalAmount = $tarionAmount-$tarionwithdrawAmount;
           if($totalAmount >=$data['amount'])
                {
						$data['tarion_amount'] = $data['amount'];
				}
                else
                {
						$this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw !!!!!');
						 $this->view->withdrawForm = $withdrawForm;
						return $this->render('withdrawedit');
				}
			}
				if($data['widthdraw_type']=="regular")
                {
					$regularAmount = $payment->totalregularamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$regularwithdrawAmount = $payment->totalregularwithdrawamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$totalAmount = $regularAmount-$regularwithdrawAmount;
					if($totalAmount >=$data['amount'])
                    {
						$data['regular_amount'] = $data['amount'];
					}
                    else
                    {
						$this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw !!!!!');
						$this->view->withdrawForm = $withdrawForm;
						return $this->render('withdrawedit');
					}
				}
		}
        else
        {
				
					$upgradeAmount = $payment->totalupgradeamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$upgradewithdrawAmount = $payment->totalupgraderwithdrawamount($postData['client_id'],$postData['project_id'],$postData['suit_number']);
					$totalAmount = $upgradeAmount-$upgradewithdrawAmount;
					if($totalAmount >=$data['amount'])
                    {
						$data['amount'] = $data['amount'];
					}
                    else
                    {
	$this->_helper->getHelper('FlashMessenger')->addMessage('You can not withdraw !!!!!');
	$this->view->withdrawForm = $withdrawForm;
	return $this->render('withdrawedit');
					}
				
		}
			//unset($data['payment_type']);
            
		    $payment->save($data); 
            if(isset($data['id']) && $data['id'] != ""){
                $this->_helper->getHelper('FlashMessenger')->addMessage('Withdraw information updated succesfully !!!!!');
            }
            else
                $this->_helper->getHelper('FlashMessenger')->addMessage('Withdraw added succesfully !!!!!');
                $this->_helper->redirector("list","index","project");
        } 
        else 
        {
                $this->view->withdrawForm = $withdrawForm;
                return $this->render('withdrawedit');
        }
    }	
    public function deleteAction()
    {
        $paymentId=$this->getRequest()->getParam('id');
        $client_id=$this->getRequest()->getParam('client_id');
        $payment = new Application_Model_Payment();
        $payment->delete($paymentId);
        $this->_helper->getHelper('FlashMessenger')->addMessage('Record Deleted Succesfully !!!!!');
        $this->_helper->redirector("view","index","client",array('client' => $client_id));
    }   
	private function _sendMail($result)
    {
        $mailer = Zend_Registry::get('logmailer');
		$client = new Application_Model_Client();
		$clientData=$client->find($result['client_id']);
        $mailer->clearSubject();
        $mailer->clearRecipients();
        $mailer->addTo('poojajoshi9898@gmail.com');
        $mailer->setFrom('poojajoshi9898@gmail.com');
        $this->view->clientData = $clientData[0];
        $this->view->result = $result;
		 $pos = strpos($mystring, '_');
		if(false !== $pos) 
        {
			$mailer->setSubject(ucfirst($result['status']));
		}
		else 
        {
			$status = str_replace(' ', '_', $result['status']);
			$mailer->setSubject(ucfirst($status));
		}
        $mailer->setBodyHtml($this->view->render('index/'.$result['status'].'.phtml'));
        $mailer->send();
    }
	
}

