<?php

class Client_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $this->view->message = "Welcome ......!!!!";
    }

    public function listAction() { 
        $proejct = new Application_Model_Project();
        $uid = $_SESSION['Zend_Auth']['storage']->id;
        $this->view->projectList = $proejct->Project_User_show_List($uid);
        $projectId = $_REQUEST['projectlist'];

        $client = new Application_Model_Client();
        $editLink = $this->view->url(array('module' => 'client',
            'controller' => 'index',
            'action' => 'edit',
            'id' => '#'), 'default', true);
        $deleteLink = $this->view->url(array('module' => 'client',
            'controller' => 'index',
            'action' => 'delete',
            'id' => '#'), 'default', true);
        $viewLink = $this->view->url(array('module' => 'client',
            'controller' => 'index',
            'action' => 'view',
            'client' => '#'), 'default', true);
        $options = $client->getClientDataGridOption($editLink, $deleteLink, $viewLink, $projectId, $this->view->baseUrl());
        $dataGrid = new Bvb_MyGrid();
        #echo '<pre>';
        #print_r($options);
        #echo '</pre>';
        $this->view->grid = $dataGrid->getGridObject($options);
        $this->view->clientList = $client->read();
    }

    public function viewAction() {
        $clientId = $this->getRequest()->getParam('client');
        $client = new Application_Model_Client();
        $data = null;
        $project_id = '';
        $suit_number = '';
        $secondary = new Application_Model_Secondary();
        $results = $secondary->findPurchaserByProjectClient($clientId);
        $this->view->purchaserList = $results;

        if (isset($clientId)) {
            $data = $client->clientInformation($clientId);
           #echo '<hr style="clear:both;"><pre>'; print_r($data);
            $this->view->client = $data[0];
            $project_id = $data[0]->project_id;
            $suit_number = $data[0]->suit_number;
        }
        if ($project_id != '' && $clientId != '') {
            $payment = new Application_Model_Payment();
            $deleteLink = '';
            $editLink = '';
            $deleteLink = $this->view->url(array('module' => 'payment',
                'controller' => 'index',
                'action' => 'delete',
                'id' => '#',
                'client_id' => $clientId), 'default', true);
            $editLink = $this->view->url(array('module' => 'payment',
                'controller' => 'index',
                'action' => 'edit',
                'id' => '#',
                'project_id' => $project_id,
                'client_id' => $clientId,
                'suit' => $suit_number
                    ), 'default', true);

            $options = $payment->getClientPaymentDataGridOption($editLink, $deleteLink, $clientId, $project_id, $this->view->baseUrl(), $data[0]->status, $suit_number);
            $dataGrid = new Bvb_MyGrid();
            $this->view->grid = $dataGrid->getGridObject($options);
            $deleteLink = '';
            $editLink = '';
            $deleteLink = $this->view->url(array('module' => 'payment',
                'controller' => 'index',
                'action' => 'delete',
                'id' => '#',
                'client_id' => $clientId), 'default', true);
            $editLink = $this->view->url(array('module' => 'payment',
                'controller' => 'index',
                'action' => 'withdrawedit',
                'id' => '#',
                'project_id' => $project_id,
                'client_id' => $clientId,
                'suit' => $suit_number
                    ), 'default', true);

            $options = $payment->getClientWithdrawDataGridOption($editLink, $deleteLink, $clientId, $project_id, $this->view->baseUrl(), $suit_number);
            $dataGrid = new Bvb_MyGrid();
            $this->view->withdrawgrid = $dataGrid->getGridObject($options);
        }
    }

    public function editAction() {
        $clientId = $this->getRequest()->getParam('id');
        $searchData = array();
        $t = $this->getRequest()->getParam('t');
        $pid = $this->getRequest()->getParam('pid');
        $client = new Application_Model_Client();
        if ($t != "" && $pid != "") {
            if ($t == 1) {
                $searchData = $client->clientData($pid);
            } else if ($t == 2) {
                $sclient = new Application_Model_Secondary();
                $searchData = $sclient->find($pid);
            }
        }


        $data = null;
        $results = array();
        $clientId;
        if (isset($clientId))
            $data = $client->find($clientId);
        $clientForm = new Application_Form_Client($searchData);

        $clientForm->setAction($this->view->url(array('module' => 'client', 'controller' => 'index', 'action' => 'save',)));
        $translate = Zend_Registry::get('translate');
        $clientForm->setTranslator($translate);
        $clientForm->customPopulate($data);
        $this->view->clientForm = $clientForm;
        $this->view->searchData = $searchData;
        $this->view->client = $data[0];
        $secondary = new Application_Model_Secondary();
        if (isset($clientId)) {
            $results = $secondary->findPurchaserByProjectClient($clientId);
        }
        $this->view->purchaserList = $results;
    }

    public function saveAction() {

        $searchData = array();
        $clientForm = new Application_Form_Client($searchData);
        $request = $this->getRequest();

        if ($clientForm->isValid($request->getPost())) {
            $paymentData = array();
            $data = $clientForm->getValues();
            $client = new Application_Model_Client();
            $checkClientId = $client->checksuitavailabel($data['project_id'], $data['suit_number'], $data['suit_unit_number']);
            if (isset($_REQUEST['client_id']) && $_REQUEST['client_id'] > 0) {
                
            } else {
                if ($checkClientId != 0 && $data['id'] != $checkClientId) {
                    $this->_helper->getHelper('FlashMessenger')->addMessage('This Suit is already allocated !!!!!');
                    $this->view->clientForm = $clientForm;
                    //$this->view->clientForm = $clientForm;
                    $url = 'client/index/edit';
                    $this->_helper->redirector->gotoUrl($url);
                }
            }
            if (isset($data['amount'])) {
                $paymentData['amount'] = $data['amount'];
                $paymentData['tarion_amount'] = $data['amount'];
                $paymentData['suit_number'] = $data['suit_number'];
                unset($data['amount']);
            }
            if (isset($data['payment_date'])) {
                $paymentData['payment_date'] = $data['payment_date'];
                unset($data['payment_date']);
            }
            if (isset($data['cheque_number'])) {
                $paymentData['cheque_number'] = $data['cheque_number'];
                unset($data['cheque_number']);
            }
            if (isset($data['payment_type'])) {
                $paymentData['payment_type'] = $data['payment_type'];
                unset($data['payment_type']);
            }
            if (isset($data['payment_method'])) {
                $paymentData['payment_method'] = $data['payment_method'];
                unset($data['payment_method']);
            }
            if (isset($data['id']) && $data['id'] != "") {
                
            }

            /* ------------------------Client Save And get client Id-------------- */
            $client = new Application_Model_Client();
            if (isset($_REQUEST['client_id']) && $_REQUEST['client_id'] > 0) {
                echo 'update' . $clientid = $_REQUEST['client_id'];
            } else {
                echo 'save' . $clientid = $client->save($data);
            }

            /* ------------------------Client Save And get client Id-------------- */
            /* ------------------Add Secondry Purchaser Start------------------------- */
            # echo '<hr><pre>';
            #           print_r($_REQUEST);
            #          echo $_REQUEST['type_sec'];
            #         die; 
            if (count($_REQUEST['type_sec']) > 0) {
                $count = count($_REQUEST['type_sec']);
                for ($i = 1; $i < $count; $i++) {
                    if (isset($_REQUEST['sec_id'][$i]) && !empty($_REQUEST['sec_id'][$i]) && ($_REQUEST['sec_id'][$i] > 0)) {
                        $data1['id'] = $_REQUEST['sec_id'][$i];
                    }
                    $data1['project_id'] = $_REQUEST['project_id'];
                    $data1['client_id'] = $clientid;
                    $data1['first_name'] = $_REQUEST['first_name_sec'][$i];
                    $data1['last_name'] = $_REQUEST['last_name_sec'][$i];
                    $data1['phone_number'] = $_REQUEST['phone_number_sec'][$i];
                    $data1['email_address'] = $_REQUEST['email_address_sec'][$i];
                    $data1['address'] = $_REQUEST['address_sec'][$i];
                    $data1['sin_number'] = $_REQUEST['sin_number_sec'][$i];
                    $data1['type'] = $_REQUEST['type_sec'][$i];
                    $secondary = new Application_Model_Secondary();
                    if (isset($_REQUEST['first_name_sec'][$i]) && !empty($_REQUEST['first_name_sec'][$i])) {
                        $secondary->save($data1);
                    }
                }

                if (isset($_REQUEST['client_id']) && $_REQUEST['client_id'] > 0) {
                    $this->_helper->getHelper('FlashMessenger')->addMessage('Update and Second Client added succesfully !!!!!');
                    $url = 'client/index/list';
                    $this->_helper->redirector->gotoUrl($url);
                }
            }
            /* ------------------Add Secondry Purchaser End------------------------- */
            $this->_helper->getHelper('FlashMessenger')->addMessage('Client added succesfully !!!!!');
            $url = 'payment/index/edit/project_id/' . $data['project_id'] . '/client_id/' . $clientid . '/suit/' . $data['suit_number'];
            $this->_helper->redirector->gotoUrl($url);

            if (!empty($paymentData)) {
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                $paymentData['client_id'] = $db->lastInsertId();
                $paymentData['created_by'] = $data['created_by'];
                $paymentData['project_id'] = $data['project_id'];
                $paymentData['type'] = "payment";
                $paymentData['status'] = "deposit";
                $payment = new Application_Model_Payment();
                $payment->save($paymentData);
            }
            if (isset($data['id']) && $data['id'] != "") {
                $this->_helper->getHelper('FlashMessenger')->addMessage('Client information updated succesfully !!!!!');
                $this->_helper->redirector("edit", "index", "client", array('id' => $clientid));
            } else
                $this->_helper->getHelper('FlashMessenger')->addMessage('Client added succesfully !!!!!');
            $this->_helper->redirector("edit", "index", "client");
        }
        else {
            $this->view->clientForm = $clientForm;
            return $this->render('edit');
        }
    }

    public function deleteAction() {
        $clientId = $this->getRequest()->getParam('id');
        $client = new Application_Model_Client();
        $client->delete($clientId);
        $this->_helper->getHelper('FlashMessenger')->addMessage('Project deleted succesfully !!!!!');
        $this->_helper->redirector("list", "index", "project");
    }

    public function verifyAction() {
        $clientId = $this->getRequest()->getParam('client');
        $client = new Application_Model_Client();
        $clientData = $client->clientInformation($clientId);
        if ($clientData[0]->status == 'active') {
            $data['status'] = 'deactive';
        } else {
            $data['status'] = 'active';
        }
        $data['id'] = $clientId;
        $client->save($data);
        $this->_helper->redirector("view", "index", "client", array('client' => $data['id']));
    }

    public function addSecondaryPurchaserAction() {
        $secondary = new Application_Model_Secondary();
        $data = $this->getRequest()->getParams($secondary);
        unset($data['module']);
        unset($data['action']);
        unset($data['controller']);
        echo $secondary->save($data);
        #exit;
    }

    public function deleteSecondaryPurchaserAction() {
        $clientId = $this->getRequest()->getParam('clientId');
        $secondary = new Application_Model_Secondary();
        $secondary->delete($clientId);
        $this->_helper->getHelper('FlashMessenger')->addMessage('Secondary purchaser Delete succesfully !!!!!');
        $url = 'client/index/edit/id/'.$_REQUEST['client_id'];
        $this->_helper->redirector->gotoUrl($url);
        die;
    }

    public function secondaryPurchaserAction() {
        $clientId = $this->getRequest()->getParam('clientId');
        $secondary = new Application_Model_Secondary();
        $results = $secondary->findPurchaserByProjectClient($clientId);
        $this->view->purchaserList = $results;
        $this->_helper->layout()->disableLayout();
    }

    public function speAction() {
        unset($_REQUEST['adminer_version']); 
        $Id = $this->getRequest()->getParam('id');
      
        $secondary = new Application_Model_Secondary();
        $results = $secondary->secondPurchaserByProjectClient($Id);
        $this->view->secpurchaserList = $results;
         
        if(isset($_REQUEST['first_name']) && !empty($_REQUEST['first_name']))
        { 
          
          
             unset($_REQUEST['PHPSESSID']); 
             unset($_REQUEST['adminer_version']); 
             print_r($_REQUEST); #die; 
            # die;
            $secondary->save($_REQUEST);#die;
            $this->_helper->getHelper('FlashMessenger')->addMessage('Secondary purchaser Update succesfully !!!!!');
            $url = 'client/index/edit/id/'.$_REQUEST['client_id'];
            $this->_helper->redirector->gotoUrl($url);
        }
    }

    public function searchAction() {
        $searchData = array();
        if (isset($_POST['search']) && $_POST['search'] != "") {
            $client = new Application_Model_Client();
            $search = $_POST['search'];
            $searchData = $client->search($search);
        }
        $this->view->searchData = $searchData;
    }

}
