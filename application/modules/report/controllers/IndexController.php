<?php

class Report_IndexController extends Zend_Controller_Action 
{

    public function indexAction() 
    {
        $type = $this->getRequest()->getParam('type');
        $name = $this->getRequest()->getParam('name');
        if(!isset($type)){
            $type = 'Table';
        }
        $report = new Application_Model_Report();
        $dataGrid = new Bvb_MyGrid();
        $options = $report->getReportData($name);
        $deploy = array();
        $deploy['save'] = 1;
        $deploy['download'] = 1;
        $deploy['orientation'] = 'LANDSCAPE';
        $deploy['size'] = 'A4';
        $deploy['dir'] = APPLICATION_PATH . "/../public/tmp";
        $this->view->grid = $dataGrid->getGridObject($options, array(), $type, $deploy);
    }
    
    public function reportAllAction()
    {
        $proejct = new Application_Model_Project();
       $uid=$_SESSION['Zend_Auth']['storage']->id;
        $this->view->projectList=$proejct->Project_User_show_List($uid);
        $payment = new Application_Model_Payment();
        $paymentData = $payment->getPaymentDetails(null,null,null,$_REQUEST['projectlist']);
        $this->view->paymentData = $paymentData;
    }
    
    public function clientAction()
    {
        $proejct = new Application_Model_Project();
        $uid=$_SESSION['Zend_Auth']['storage']->id;
        $this->view->projectList=$proejct->Project_User_show_List($uid);
        $data = $this->getRequest()->getParams();
        if($this->getRequest()->isPost())
        {
            
            $client = new Application_Model_Client();
            $clients = $client->searchClients($data);
            $this->view->clients = $clients;
        }
    }
    
    public function clientPreviewAction()
    {
        $clientId = $this->getRequest()->getParam('id');
        $payment = new Application_Model_Payment();
        $paymentData = $payment->getPaymentDetails($clientId);
        $this->view->paymentData = $paymentData;
    }
    
    public function monthlyAction()
    {
        $proejct = new Application_Model_Project();
        $uid=$_SESSION['Zend_Auth']['storage']->id;
        $this->view->projectList=$proejct->Project_User_show_List($uid);
        $data = $this->getRequest()->getParams();
        if($this->getRequest()->isPost()){
            
            $client = new Application_Model_Client();
            $clients = $client->searchClients($data);
            $this->view->clients = $clients;
        }
    }
    
    public function monthlyPreviewAction()
    {
        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');
        $pid = $this->getRequest()->getParam('projectlist');
        
        $payment = new Application_Model_Payment();
        $paymentData = $payment->getPaymentDetails(null, $fromDate, $toDate,$pid);
        
        $this->view->fromDate = $fromDate;
        $this->view->toDate = $toDate;
        $this->view->paymentData = $paymentData;
    }
}

