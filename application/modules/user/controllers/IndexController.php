<?php

class User_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $auth = Zend_Auth::getInstance();
        if ($auth->getIdentity()->type != 'salesagent') {
            return $this->_helper->redirector('list', 'index', 'user');
        } else {
            return $this->_helper->redirector('list', 'index', 'client');
        }
    }

    public function listAction() {
        $user = new Application_Model_User();

        $proejct = new Application_Model_Project();
        $uid = $_SESSION['Zend_Auth']['storage']->id;
        $this->view->projectList = $proejct->Project_User_show_List($uid);
        
        $editLink = $this->view->url(array('module' => 'user',
            'controller' => 'index',
            'action' => 'edit',
            'id' => '#'), 'default', true);
        $deleteLink = $this->view->url(array('module' => 'user',
            'controller' => 'index',
            'action' => 'delete',
            'id' => '#'
                ), 'default', true);
        $viewLink = $this->view->url(array('module' => 'user',
            'controller' => 'index',
            'action' => 'view',
            'user' => '#'), 'default', true);

        $rights = explode(",", Zend_Auth::getInstance()->getIdentity()->rights);
        if (in_array(13, $rights)) {
            $projectlist = -1;
        }
        if (in_array(14, $rights)) {
            $projectlist = $_REQUEST['projectlist'];
        }

        $options = $user->getUserDataGridOption($editLink, $deleteLink, $this->view->baseUrl(), $projectlist);
        $dataGrid = new Bvb_MyGrid();

        $this->view->grid = $dataGrid->getGridObject($options);
        $this->view->userList = $user->read();
    }

    public function searchbyemailAction() {

        if (isset($_POST['Email'])) {
            $user = new Application_Model_User();
            $users = $user->searchbyemail($_POST['Email']);
        }
        $this->view->showemaillist = $users;
    }

    public function editAction() {

        $userId = $_SESSION['Zend_Auth']['storage']->id;
        $proejct = new Application_Model_Project();
        $userId = $this->getRequest()->getParam('id');
        $this->view->projectList = $proejct->projectUser($userId);
        $user = new Application_Model_User();
        $data = null;
        if (isset($userId)) {
            //echo $userId;
            $data = $user->find($userId);
        }
        $userForm = new Application_Form_adduser();
        $userForm->setAction($this->view->url(array('module' => 'user', 'controller' => 'index', 'action' => 'save')));
        $translate = Zend_Registry::get('translate');
        $userForm->setTranslator($translate);
        if (isset($data) && isset($data[0])) {
            $data[0]['rights'] = explode(",", $data[0]['rights']);
        }
        $userForm->customPopulate($data);
        $this->view->userForm = $userForm;
    }

    public function addAction() {

        $userIds = $_SESSION['Zend_Auth']['storage']->id;
        $user = new Application_Model_User();
        $data = null;
        $proejct = new Application_Model_Project();
        $r = $proejct->addprojectUser($userIds);

        $this->view->projectList = $proejct->addprojectUser($userIds);
        if (isset($userId))
            $data = $user->find($userId);
        $userForm = new Application_Form_adduser();
        $userForm->setAction($this->view->url(array('module' => 'user', 'controller' => 'index', 'action' => 'save')));
        $translate = Zend_Registry::get('translate');
        $userForm->setTranslator($translate);
        if (isset($data) && isset($data[0])) {
            $data[0]['rights'] = explode(",", $data[0]['rights']);
        }
        $userForm->customPopulate($data);
        $this->view->userForm = $userForm;
    }

    public function adduAction() {
        echo 'hi';
    }

    public function viewAction() {
        $userId = $this->getRequest()->getParam('user');
        $user = new Application_Model_User();
        $data = null;
        if (isset($userId)) {
            $data = $user->find($userId);

            $this->view->user = $data[0];
        }
        $proejct = new Application_Model_Project();
        $deleteLink = $this->view->url(array('module' => 'project',
            'controller' => 'index',
            'action' => 'delete-user-project',
            'id' => '#'), 'default', true);

        $options = $proejct->getUserProjectDataGridOption($deleteLink, $userId, $this->view->baseUrl());
        $dataGrid = new Bvb_MyGrid();

        /* Set grid in view */
        if (isset($userId)) {
            $this->view->grid = $dataGrid->getGridObject($options);
        }
        $this->view->userId = $userId;

        $this->view->projectList = $proejct->projectUser($userId);
    }

    public function saveAction() {

        $userForm = new Application_Form_User();
        $request = $this->getRequest();
        if ($userForm->isValid($request->getPost())) {
            $data = $userForm->getValues();
            $data['rights'] = implode(",", $data['rights']);
            $user = new Application_Model_User();
            $result = $user->save($data);

            if (isset($_REQUEST['project_id'])) {
                $projectid = $_REQUEST['project_id'];
                $projectuser = new Application_Model_ProjectUser();
                $data1['project_id'] = $projectid;
                if (isset($result['id'])) {                    // If We can add New User then is Send userid $result['id']
                    $data1['user_id'] = $result['id'];
                } else {                                        // If We can update any User then it not Send then we can use $data['id']
                    $data1['user_id'] = $data['id'];
                }
                $projectuser->save($data1);
            }

            if (isset($data['id']) && $data['id'] != "") {
                $this->_helper->getHelper('FlashMessenger')->addMessage('User information updated succesfully !!!!!');
            } else {
                $this->_helper->getHelper('FlashMessenger')->addMessage('User  succesfully Add!!!!!');
                $this->_sendMail($result);
            }
            $this->_helper->redirector("list", "index", "user");
        } else {
            $this->view->userForm = $userForm;
            $this->_helper->redirector("list", "index", "user");
        }
    }

    public function deletionAction() {
        $userId = $this->getRequest()->getParam('id');
        $user = new Application_Model_User();
        $this->view->Userid = $userId;
    }

    public function deleteAction() {

        $userId = $this->getRequest()->getParam('id');
        $user = new Application_Model_User();
        $this->view->Userid = $userId;
        $act = $this->getRequest()->getParam('act');
        if (!isset($act)) {
            $url = "user/index/deletion/id/" . $userId;
            $this->_redirect($url);
        }
        if (isset($act) && $act == 1) {
            $user->delete($userId);
            $this->_helper->getHelper('FlashMessenger')->addMessage('User deleted succesfully !!!!!');
            $this->_helper->redirector("list", "index", "user");
        } else {
            $this->_helper->redirector("list", "index", "user");
        }
    }

    private function _sendMail($result) {
        $mailer = Zend_Registry::get('logmailer');
        $mailer->clearSubject();
        $mailer->clearRecipients();
        $mailer->addTo($result['email_address']);
        //$mailer->addTo('laxit.patel@gmail.com');
        $mailer->setFrom('laxit.patel@gmail.com');
        $this->view->result = $result;
        $mailer->setSubject("Account is creatd for user :" . $result['first_name'] . " " . $result['last_name']);
        $mailer->setBodyHtml($this->view->render('index/account-created.phtml'));
        $mailer->send();
    }

    public function assignProjectAction() {
        $data = $this->getRequest()->getPost();
        $projectUser = new Application_Model_ProjectUser();
        $projectUser->save($data);

        return $this->_helper->redirector('view', 'index', 'user', array('user' => $data['user_id']));
    }

    public function updatePasswordAction() {
        $changePasswordForm = new Application_Form_ChangePassword();
        $data = $this->getRequest()->getParams();

        if ($this->getRequest()->isPost() && $changePasswordForm->isValid($data)) {
            $user = new Application_Model_User();

            $userData = array();
            $userData['id'] = Zend_Auth::getInstance()->getIdentity()->id;
            $data['newPassword'];
            $userData['password'] = ($data['newPassword']);

            $user->save($userData);
            $this->_helper->getHelper('FlashMessenger')->addMessage('Password updated succesfully !!!!!');
            return $this->_helper->redirector('list', 'index', 'user');
        }
        $this->view->changePassword = $changePasswordForm;
    }

}
