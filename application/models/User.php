<?php

class Application_Model_User {

    public function read() {
        $db = new Application_Model_DbTable_User();
        $result = $db->fetchAll(null);
        return $result;
    }

    public function find($id) {
        $db = new Application_Model_DbTable_User();
        $result = $db->find($id);
        return $result;
    }

    public function searchbyemail($email) {
        $_SESSION['Zend_Auth']['storage']->type;
        $searchData = array();
        $db = new Application_Model_DbTable_User();
        if ($_SESSION['Zend_Auth']['storage']->type == 'admin') {    //If log in  Escrow Agent then find only Salesagent
            $select = $db->select()->where('email_address = ?', $email)
                    ->where('type = ?', 'salesagent')
                    ->where('id != ?', $_SESSION['Zend_Auth']['storage']->id);
        }
        if ($_SESSION['Zend_Auth']['storage']->type == 'superadmin') {    //If log in  Super Admin then find only Escrow Agent
            $select = $db->select()->where('email_address = ?', $email)
                    ->where('type = ?', 'admin')
                    ->where('id != ?', $_SESSION['Zend_Auth']['storage']->id);
        }
        if ($_SESSION['Zend_Auth']['storage']->type == 'salesagent') {    //If log in  Super Admin then find only Escrow Agent
            $select = $db->select()->where('email_address = ?', $email)
                    ->where('type = ?', 'salesagent')
                    ->where('id = ?', $_SESSION['Zend_Auth']['storage']->id);
        }



        $result = $db->fetchAll($select);

        if (isset($result[0])) {
            $searchData['first_name'] = $result[0]['first_name'];
            $searchData['last_name'] = $result[0]['last_name'];
            $searchData['email_address'] = $result[0]['email_address'];
            $searchData['address'] = $result[0]['address'];
            $searchData['type'] = $result[0]['type'];
            $searchData['pid'] = $result[0]['id'];
        } else {
            $searchData['first_name'] = '';
            $searchData['last_name'] = '';
            $searchData['email_address'] = '';
            $searchData['address'] = '';
            $searchData['phone_number'] = '';
            $searchData['pid'] = '';
        }
        return $searchData;
    }

    public function findByEmail($email) {
        $db = new Application_Model_DbTable_User();
        $select = $db->select()->where('email_address = ?', $email);
        $result = $db->fetchAll($select)->toArray();

        if (count($result) > 0)
            return $result[0];
        else
            return NULL;
    }

    public function save($data) {


        $loggedInUserId = (Zend_Auth::getInstance()->getIdentity()->id);

        $db = new Application_Model_DbTable_User();

        if (isset($data['id']) && $data['id'] != "") {
            $data['updated_by'] = $loggedInUserId;
            $data['updated_at'] = date('Y-m-d H-i-s');
            if (!empty($data['password'])) {
                $password = $data['password'];
                $data['password'] = md5($data['password']);
            } else {
                unset($data['password']);
            }

            $db->update($data, "id = " . $data['id']);
        } else {
            $data['created_by'] = $loggedInUserId;
            $data['created_at'] = date('Y-m-d H-i-s');
            $data['updated_by'] = $loggedInUserId;
            $data['updated_at'] = date('Y-m-d H-i-s');

            if (!empty($data['password'])) {

                $password = $data['password'];
            } else {
                $password = $this->_generatePassword();
            }
            $data['password'] = md5($password);

            $result = $db->insert($data);
        }


        $data['id'] = $result;
        $data['password'] = $password;
        $result = $data;
        return $result;
    }

    private function _generatePassword() {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    }

    public function updatePassword($data) {
        $password = $this->_generatePassword();
        $data['password'] = ($password);
        $this->save($data);
        return $password;
    }

    public function delete($id) {

        $db = new Application_Model_DbTable_User();
        $db->delete('id=' . $id);
    }

    public function _getDepartmentDataSql($ProjectId) {
        $auth = Zend_Auth::getInstance();
        $dao = new Application_Model_DbTable_User();
        $select = $dao->getDefaultAdapter()->select();
        $id = $auth->getIdentity()->id;
        // Change By Ankit About Super User All User Show      
        if ($auth->getIdentity()->type == "superadmin") {
            $id = $auth->getIdentity()->id;
            $select->from(array('u' => "users"), array('id',
                'u.first_name',
                'u.last_name',
                'email_address',
                'type',
                'created_at' => new Zend_Db_Expr('DATE_FORMAT(u.created_at,"%d-%m-%Y %H:%i:%s")'),
                'updated_at' => new Zend_Db_Expr('DATE_FORMAT(u.updated_at,"%d-%m-%Y %H:%i:%s")')
            ));
            $select->where('id != ?', $id);
        } else {
            if ($ProjectId == -1) {

                $select->from(array('u' => "users"), array('id',
                    'u.first_name',
                    'u.last_name',
                    'email_address',
                    'type',
                    'created_at' => new Zend_Db_Expr('DATE_FORMAT(u.created_at,"%d-%m-%Y %H:%i:%s")'),
                    'updated_at' => new Zend_Db_Expr('DATE_FORMAT(u.updated_at,"%d-%m-%Y %H:%i:%s")'),
                    'Created By' => 'u1.first_name'
                        )
                )->JoinInner(array('u1' => 'users'), 'u.created_by = u1.id', array());
                $select->where("u.created_by=?", $id);
            } else {
                $select->from(array('u' => "users"), array('id',
                    'u.first_name',
                    'u.last_name',
                    'email_address',
                    'type',
                    'created_at' => new Zend_Db_Expr('DATE_FORMAT(u.created_at,"%d-%m-%Y %H:%i:%s")'),
                    'updated_at' => new Zend_Db_Expr('DATE_FORMAT(u.updated_at,"%d-%m-%Y %H:%i:%s")'),
                    'Created By' => 'u1.first_name'
                        )
                )->JoinInner(array('p' => 'project_user'), 'u.id = p.user_id', array())->JoinInner(array('u1' => 'users'), 'u.created_by = u1.id', array());
            }
        }
        if ($auth->getIdentity()->type == "admin") {
            $user = new Application_Model_User();
            $ids = $user->getChildUser();
            $select->where("u.type='salesagent'");
            if ($ProjectId > 0) {
                $select->where("p.project_id=?", $ProjectId);
            }
        }
        $select->group('u.id');

        return $select;
    }

    public function getUserDataGridOption($editLink, $deleteLink, $url, $ProjectId) {
        $sourceObject = $this->_getDepartmentDataSql($ProjectId);
        /* Set department listing options in a grid */
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(
                'id' => array('hidden' => true),
                'first_name' => array('title' => 'First name', 'position' => '1'),
                'last_name' => array('title' => 'Last Name', 'position' => '2'),
                'email_address' => array('title' => 'Email-address', 'position' => '3'),
                'type' => array('title' => 'Type', 'position' => '4'),
                'created_at' => array('title' => 'Created At', 'position' => '6'),
                'created' => array('title' => 'User created By     ', 'position' => '5'),
                'updated_at' => array('title' => 'Updated At', 'position' => '7'),
            ),
            'actions' => array(
                'data' => array(
                    'edit' => array(
                        'title' => 'Edit',
                        'image' => $url . '/images/edit.gif',
                        'link' => $editLink,
                        'param1' => 'id'
                    ),
                    'delete' => array(
                        'title' => 'Delete',
                        'class' => 'deleteDepartmentRecord',
                        'image' => $url . '/images/grid_delete.png',
                        'link' => $deleteLink,
                        'param1' => 'id'
                    ),
                ),
                'position' => 'right',
                'title' => '    ',
            ),
            'filtersText' => array(
                'first_name' => array('class' => 'smallTextFilters'),
                'last_name' => array('class' => 'smallTextFilters'),
                'email_address' => array('class' => 'bigTextFilters', 'width' => '100px'),
            ),
            'filters' => array(),
            'recordPerPage' => '10',
            'setShowOrderImage' => false,
            'setAjax' => 'dataGridDepartment',
            'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
        $uid = explode(',', $_SESSION['Zend_Auth']['storage']->rights);
        #echo $_SESSION['Zend_Auth']['storage']->type;
        # echo '<pre>';print_r($options);echo '</pre>';
        if($_SESSION['Zend_Auth']['storage']->type!='superadmin')
            {
                if (!in_array('11', $uid)) 
                { 
                    unset($options['actions']['data']['edit']);
                }
            }
        return $options;
    }

    public function getChildUser() {
        $auth = Zend_Auth::getInstance();
        $dao = new Application_Model_DbTable_User();
        $select = $dao->select();
        $select->from(array('u' => "users"), array('id'));
        /* ------------------New One Ankit------------- */
        $rights = explode(",", $auth->getIdentity()->rights);
        if (in_array(12, $rights)) {
            $select->where('u.created_by=?', $auth->getIdentity()->id);
        }
        /* ------------------New One Ankit------------- */
        //$select->where('u.created_by=?',$auth->getIdentity()->id);
        $result = $dao->fetchAll($select);
        $ids = '';
        $singleArray = array();
        if (isset($result) && !empty($result)) {
            $r = $result->toArray();
            foreach ($r as $key => $value) {
                $singleArray[$key] = $value['id'];
            }
            $ids = implode(',', $singleArray);
            if ($ids != "") {
                $ids .="," . $auth->getIdentity()->id;
            } else {
                $ids = $auth->getIdentity()->id;
            }
        } else {
            $ids = $auth->getIdentity()->id;
        }

        return $ids;
    }

}
