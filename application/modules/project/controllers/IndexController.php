<?php

class Project_IndexController extends Zend_Controller_Action {

    public function indexAction() {

        $this->view->message = "Welcome ......!!!!";
    }
    public function delAction()
    {echo 'del';}
    public function listAction() {

        $proejct = new Application_Model_Project();

        $editLink = $this->view->url(array('module' => 'project',
            'controller' => 'index',
            'action' => 'edit',
            'id' => '#'), 'default', true);
        $clientLink = $this->view->url(array('module' => 'client',
            'controller' => 'index',
            'action' => 'list',
            'id' => '#'), 'default', true);
        $deleteLink = $this->view->url(array('module' => 'project',
            'controller' => 'index',
            'action' => 'delete',
            'id' => '#'), 'default', true);
        /* $projectdetailLink = $this->view->url(array('module' => 'project',
          'controller' => 'index',
          'action' => 'projectdetail',
          'id' => '#'),
          'default', true); */
        $projectReleases = $this->view->url(array('module' => 'payment',
            'controller' => 'index',
            'action' => 'withdrawedit',
            'project_id' => '#'), 'default', true);


        $options = $proejct->getProjectDataGridOption($editLink, $clientLink, $deleteLink, $projectReleases, $this->view->baseUrl());
        # $options = $proejct->getProjectDataGridOption($editLink,$clientLink, $deleteLink, $projectdetailLink,$projectReleases, $this->view->baseUrl());
        $dataGrid = new Bvb_MyGrid();


        $this->view->grid = $dataGrid->getGridObject($options);
        $this->view->projectList = $proejct->read();
    }

    public function projectdetailAction() {
        $request = $this->getRequest();
        if ($request->getPost()) {
            $proejctdetail = new Application_Model_ProjectDetail();
            $data = array();
            $data['dwelling_units'] = $this->getRequest()->getPost('dwelling_units', null);
            $data['parking_units'] = $this->getRequest()->getPost('parking_units', null);
            $data['storage_units'] = $this->getRequest()->getPost('storage_units', null);
            $data['number_of_lot'] = $this->getRequest()->getPost('number_of_lot', null);
            $data['lot_range_start'] = $this->getRequest()->getPost('lot_range_start', null);
            $data['lot_range_end'] = $this->getRequest()->getPost('lot_range_end', null);
            $data['projectid'] = $this->getRequest()->getPost('id', null);

            $levelv = $this->getRequest()->getPost('levelv', null);
            $startv = $this->getRequest()->getPost('startv', null);
            $toval = $this->getRequest()->getPost('toval', null);
            $allrangdata = '';
            for ($i = 0; $i < count($levelv); $i++) {
                if (strlen($levelv[$i]) > 0) {
                    $allrangdata .= $commcon . $levelv[$i] . '_' . $startv[$i] . '_' . $toval[$i];
                    $commcon = ',';
                }
            }

            $data['vrange'] = $allrangdata;
            $iprojectdetail = $proejctdetail->save($data);

            $this->_helper->redirector("list", "index", "project");
        } else {
            $projectId = $this->getRequest()->getParam('id');
            $proejctdetail = new Application_Model_ProjectDetail();
            //if(isset($projectId))
            $data = array();
            $data = $proejctdetail->find($projectId);

            if (count($data) > 0) {
                $this->view->data = $data[0];
            }

            $datac = array();
            $proejct = new Application_Model_Project();
            $datac = $proejct->find($projectId);
            $this->view->id = $this->_getParam('id');
            $this->view->datac = $datac[0];
        }
    }
   public function addbankAction()
    {
      
       if(isset($_REQUEST['button']))
       {  
            $data['id']=$_REQUEST['bankid']; 
            $data['project_id']=$_REQUEST['pid'];	
            $data['nickname']=$_REQUEST['nickname'];	
            $data['name_of_bank_trust_company']=$_REQUEST['name_of_bank_trust_company'];
            $data['branch_address']=$_REQUEST['branch_address'];	 
            $data['branch_phone_number']=$_REQUEST['branch_phone_number'];	
            $data['branch_fax_number']=$_REQUEST['branch_fax_number'];		 
            $data['branch_contact_person']=$_REQUEST['branch_contact_person'];		 
            $data['transit_number']=$_REQUEST['transit_number'];	
            $data['account_number']=$_REQUEST['account_number'];
            $Bank= new Application_Model_bank();
            $Bank->save($data);
            #echo 'tt'; print_r($_REQUEST);
            if(!empty($data['id']))
            {$this->_helper->getHelper('FlashMessenger')->addMessage('Bank Sucessfully Update !!!!!');}
            else
            {$this->_helper->getHelper('FlashMessenger')->addMessage('New Bank Added !!!!');}
            $this->_redirect('/project/index/edit/id/'.$data['project_id']);
            $this->_helper->redirector("edit", "index", "project");
       }
       
       $this->view->ProjectId = $this->getRequest()->getParam('pid');
       $BankId = $this->getRequest()->getParam('bankid');
       $Bank= new Application_Model_bank();
       if($BankId==0)
       {}else{       
       $this->view->v=$Bank->find($BankId);
       }
       //print_r($v);
    }

    public function editAction() {
        $Bank= new Application_Model_bank();
        $delId = $this->getRequest()->getParam('did');
        if($delId>0)
        {
            $Bank->delete($delId);
        }
        $projectId = $this->getRequest()->getParam('id');
        $this->view->pojectid=$projectId;
        $project = new Application_Model_Project();
        
        $Bank= new Application_Model_bank();
        $this->view->BankList    =   $Bank->read($projectId);
        
        
        $data = null;
        if (isset($projectId))
            $data = $project->find($projectId);
        $projectForm = new Application_Form_Project();

        $projectForm->setAction($this->view->url(array('module' => 'project', 'controller' => 'index', 'action' => 'save',)));
        $translate = Zend_Registry::get('translate');
        $projectForm->setTranslator($translate);
        $projectForm->customPopulate($data);
        $this->view->projectForm = $projectForm;
    }

    public function addprojectAction() {

        $project = new Application_Model_Project();
    }

    public function addsaveAction() {

        echo 'hi';
    }

    public function saveAction() {
        
        /*------------------Project Save Get Data  Using Request Variable------------------------*/
         if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") 
         {
             $project_data['id']=$_REQUEST['id'];
         }
        $project_data['name']=$_REQUEST['name'];
        $project_data['address']=$_REQUEST['address'];
        $project_data['project_type']=$_REQUEST['project_type'];
        $project_data['condo']=$_REQUEST['condo'];
        $project_data['status']=$_REQUEST['status'];
        $project_data['dwellingunits']=$_REQUEST['dwellingunits'];
        $project_data['numberofparking']=$_REQUEST['numberofparking'];
        $project_data['numberstorage']=$_REQUEST['numberstorage'];
        $project_data['created_by']=$_SESSION['Zend_Auth']['storage']->id;
        $project = new Application_Model_Project();
        $projectid = $project->save($project_data);
        /*------------------Project Save Get Data  Using Request Variable------------------------*/
        /*------------------Bank Save Get Data  Using Request Variable------------------------*/
        if(count($_REQUEST['nicname'])>0)
        {
            for($i=0;$i<=count($_REQUEST['nicname']);$i++)
            {
                $bank_data['project_id'] = $projectid;
                $bank_data['nickname'] = $_REQUEST['nicname'][$i];
                $bank_data['name_of_bank_trust_company'] = $_REQUEST['bank_name'][$i];
                $bank_data['branch_address'] = $_REQUEST['branch_add'][$i];
                $bank_data['branch_phone_number'] = $_REQUEST['branch_ph'][$i];
                $bank_data['branch_fax_number'] = $_REQUEST['branch_fx_no'][$i];
                $bank_data['branch_contact_person'] = $_REQUEST['branch_contact_person_name'][$i];
                $bank_data['transit_number'] = $_REQUEST['transit_no'][$i];
                $bank_data['account_number'] = $_REQUEST['acc_no'][$i];
                if(!empty($_REQUEST['acc_no'][$i]))
                {
                    $bank = new Application_Model_bank();
                    $bank = $bank->save($bank_data);
                }
            }
        }
        /*------------------Bank Save Get Data  Using Request Variable------------------------*/
        /*------------------Project Conect By User Get Data  Using Request Variable-----------*/
        if ($_SESSION['Zend_Auth']['storage']->type == 'admin') 
            {  //If login user is Escrow agent and add project then its own assign 
                $userID = $_SESSION['Zend_Auth']['storage']->id;
                $data1['project_id'] = $projectid;
                $data1['user_id'] = $userID;
                $projectuser = new Application_Model_ProjectUser();
                $projectuser->save($data1);
            }
        /*------------------Project Conect By User Get Data  Using Request Variable-----------*/
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") 
        {
                $Data['projectid'] = $_REQUEST['id'];
                $this->_helper->getHelper('FlashMessenger')->addMessage('Project information updated succesfully !!!!!');
        } 
        else
        {
                $Data['projectid'] = $projectid;
                $this->_helper->getHelper('FlashMessenger')->addMessage('Project added succesfully !!!!!');
        }
        /*------------------Project Detail Save User Get Data  Using Request Variable-----------*/
        $projectFormDetail = new Application_Model_ProjectDetail();
        $Data['dwelling_units'] = $data['dwellingunits'];
        $Data['parking_units'] = $data['numberofparking'];
        $Data['storage_units'] = $data['numberstorage'];
        $projectFormDetail->save($Data);
        /*------------------Project Detail Save User Get Data  Using Request Variable-----------*/
        $this->_helper->redirector("list", "index", "project");
        
        
        
        //unset($_REQUEST['PHPSESSID']);
        
        echo '<pre>';
        print_r($_REQUEST);
        echo '</pre>';
        die;

        $projectForm = new Application_Form_Project();
        $projectFormDetail = new Application_Model_ProjectDetail();
        $request = $this->getRequest();
        $data = $projectForm->getValues();


        if ($projectForm->isValid($request->getPost())) {
            $data = $projectForm->getValues();

            $projectid = $project->save($data);

            /* ----------------------------Change by Laxit 27/feb/2015-------------------- */
            if ($_SESSION['Zend_Auth']['storage']->type == 'admin') {  //If login user is Escrow agent and add project then its own assign 
                $userID = $_SESSION['Zend_Auth']['storage']->id;

                $data1['project_id'] = $projectid;
                $data1['user_id'] = $userID;
                $projectuser->save($data1);
            }
            /* ----------------------------Change by Laxit 27/feb/2015-------------------- */
            if (isset($data['id']) && $data['id'] != "") {
                $Data['projectid'] = $data['id'];
                $this->_helper->getHelper('FlashMessenger')->addMessage('Project information updated succesfully !!!!!');
            } else {
                $Data['projectid'] = $projectid;
                $this->_helper->getHelper('FlashMessenger')->addMessage('Project added succesfully !!!!!');
            }

            $Data['dwelling_units'] = $data['dwellingunits'];
            $Data['parking_units'] = $data['numberofparking'];
            $Data['storage_units'] = $data['numberstorage'];
            $projectFormDetail->save($Data);
            $this->_helper->redirector("list", "index", "project");
        } else {
            $this->view->projectForm = $projectForm;
            return $this->render('edit');
        }
    }

    public function deleteAction() {
        $projectId = $this->getRequest()->getParam('id');
        $project = new Application_Model_Project();
        $project->delete($projectId);
        $this->_helper->getHelper('FlashMessenger')->addMessage('Project deleted succesfully !!!!!');
        $this->_helper->redirector("list", "index", "project");
    }

    public function deleteUserProjectAction() {
        $projectUserId = $this->getRequest()->getParam('id');
        $projectUser = new Application_Model_ProjectUser();
        $projectUserData = $projectUser->find($projectUserId);
        $projectUser->deleteUserProject($projectUserId);
        return $this->_helper->redirector('view', 'index', 'user', array('user' => $projectUserData[0]->user_id));
    }

    public function tarionAction() {
        $tarionId = $this->getRequest()->getParam('id');
        $tarion = new Application_Model_Project();
        $data = array();
        $data = $tarion->findTarion($tarionId);
        #print_r($data);
        $this->view->data = $data[0];
    }

    public function tarionsaveAction() {
        $request = $this->getRequest();
        $data = $request->getPost();
        $tarion = new Application_Model_Project();
        unset($data['submit']);
        $tarion->tarionsave($data);
        if (isset($data['id']) && $data['id'] != "") {
            $this->_helper->getHelper('FlashMessenger')->addMessage('Tarion information updated succesfully !!!!!');
        }
        $this->_helper->redirector("tarion", "index", "project", array('id' => $data['id']));
    }

}
