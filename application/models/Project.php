<?php

class Application_Model_Project {

    public function read() {
		
        $db = new Application_Model_DbTable_Project();
        $result = $db->fetchAll(null);
		
		#print_r($result);
        return $result;
    }

    public function find($id) {
        $db = new Application_Model_DbTable_Project();
        $result = $db->find($id);
        return $result;
    }

    public function save($data) {
        $db = new Application_Model_DbTable_Project();
        if (isset($data['id']) && $data['id'] != "")
            $db->update($data, "id = " . $data['id']);
        else
            $result = $db->insert($data);
        return $result;
    }

    public function delete($id) {
        $db = new Application_Model_DbTable_Project();
        $db->delete('id=' . $id);
        return $result;
    }

    public function _getDepartmentDataSql()
    {
        $dao = new Application_Model_DbTable_Project();
		//$adapter = $this->_getAuthAdapter();
		$auth = Zend_Auth::getInstance();
	    $select = $dao->getDefaultAdapter()->select();
               
        
		   
		if($auth->getIdentity()->type=='superadmin'){
			$select->from(array("project"), array('id','name',));
            $select->joinLeft(array('u'=>'users'),'u.id = project.created_by',array('concat(u.first_name," ",u.last_name) as uname'));

		}
		/*if($auth->getIdentity()->type=='admin') {
		   $select->where("project.created_by= ? ", $auth->getIdentity()->id);
		}*/
		if($auth->getIdentity()->type=="salesagent" || $auth->getIdentity()->type=="admin")
            {
                $rights = explode(",",$auth->getIdentity()->rights); 
                $select->from(array("p"=>"project",), array('p.id','p.name',));
                $select->join(array("u"=>"project_user"),'u.user_id ='.$auth->getIdentity()->id.' AND u.project_id=p.id',array());
               
		   }
        
            $rights = explode(",",$auth->getIdentity()->rights);
           if(in_array(24,$rights) && $auth->getIdentity()->type=="admin")
           {
                $select = $dao->getDefaultAdapter()->select();
            	$select->from(array("project"), array('id','name',));
                $select->joinLeft(array('u'=>'users'),'u.id = project.created_by',array('concat(u.first_name," ",u.last_name) as uname'));
           }
		  
        return $select;
    }
	  public function getUserProjectData($userId)
    {
        $auth = Zend_Auth::getInstance();
        
        $table = new Application_Model_DbTable_Project();
        $select = $table->select();
		$select->from(array("project"), array('id','name'));
        if($auth->getIdentity()->type!='superadmin'){
        $select->join(array('pu'=>'project_user'),'project.id = pu.project_id',array());
    	$select->where("pu.user_id= ? ", $userId);
        }
		
		$projectList = $table->fetchAll($select);
	 
	    return $projectList;
    }
    
      public function _getUserProjectDataSql($userId)
    {
        $dao = new Application_Model_DbTable_Project();
        $select = $dao->getDefaultAdapter()->select();
	    $select->from(array("project"), array(
            'name',
            'created_by',
           ));
		
        $select->join(array('pu'=>'project_user'),'project.id = pu.project_id',array('id'=>'pu.id'));
        $select->where("pu.user_id= ? ", $userId);
		
        return $select;
    }
    
    #public function getProjectDataGridOption($editLink,$clientLink ,$deleteLink,$projectdetailLink,$projectRelease,$url) 
    public function getProjectDataGridOption($editLink,$clientLink ,$deleteLink,$projectRelease,$url)
    {    
        $sourceObject = $this->_getDepartmentDataSql();
       
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(
                'id' => array('hidden' => true),
                'name' => array('title' => 'Project name'),
                'created_by' => array('hidden' => true),
            ),
            'actions' => array(
                'data' => array(
                    'edit' => array(
                        'title' => 'Edit',
                        'image' => '/public/images/edit.gif',
                        'link' => $editLink,
                        'param1' => 'id'
                    ),
					'client' => array(
                        'title' => 'List',
                        'image' => '/public/images/text.png',
                        'link' => $clientLink,
                        'param1' => 'id'
                    ),
                    'delete' => array(
                        'title' => 'Delete',
                        'class' => 'deleteDepartmentRecord',
                        'image' => '/public/images/grid_delete.png',
                        'link' => $deleteLink,
                        'param1' => 'id'
                    ),
                    /*'projectdetail' => array(
                        'title' => 'Project Detail',
                        'class' => 'deleteDepartmentRecord',
                        'image' => '/public/images/detail.png',
                        'link' => $projectdetailLink,
                        'param1' => 'id'
                    ),*/
                    'amountRelease' => array(
                        'title' => 'Release',
                        'class' => 'Releases',
                        'image' => '/public/images/icon_payment.png',
                        'link' => $projectRelease,
                        'param1' => 'id'
                    ),
                ),
                'position' => 'right',
                'title' => '',
            ), 'filtersText' => array(
                'name' => array('class'=>'smallTextFilters'),
            ),
            'filters' => array(),
            'setKeyEventsOnFilters' => false,
            'recordPerPage' => '50',
            'setShowOrderImage' => false,
            'setAjax' => 'dataGridDepartment',
			'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
		$auth = Zend_Auth::getInstance();
		if($auth->getIdentity()->type=='superadmin'){
			$options['columns']['uname']= array('title' => 'Created By');
			//$options['filtersText']['uname'] = array('class'=>'smallTextFilters');
		}
        if($_SESSION['Zend_Auth'][storage]->type=='salesagent')
        {
           # unset($options['actions']['data']['amountRelease']);
        }
		 
        return $options;
    }
    
    public function getUserProjectDataGridOption($deleteLink, $userId, $url) 
    {
        $sourceObject = $this->_getUserProjectDataSql($userId);
     
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(
                'id' => array('hidden' => true),
                'name' => array('title' => 'Project name'),
                'created_by' => array('hidden' => true),
            ),
            'actions' => array(
                'data' => array(
                    'delete' => array(
                        'title' => 'Delete',
                        'class' => 'deleteDepartmentRecord',
                        'image' => $url.'/images/grid_delete.png',
                        'link' => $deleteLink,
                        'param1' => 'id',
                    ),
                ),
                'position' => 'right',
                'title' => '',
            ),
            'filtersText' => array(),
            'filters' => array(),
            'recordPerPage' => '50',
            'setShowOrderImage' => false,
            'setAjax' => 'dataGridDepartment',
            'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
        return $options;
    }
    public function Project_User_show_List($u)
    {
        $auth = Zend_Auth::getInstance();
		$table1 = new Application_Model_DbTable_ProjectUser();
		$select2 = $table1->select();
		$select2->from(array("project_user"), array('project_id'));
        $select2->where("user_id= ? ", $auth->getIdentity()->id);
        $select3 = $table1->select();
        $select3->from(array("project_user"), array('project_id'));
        $select3->where("user_id= ? ", $userId);
        
        
      
        $table = new Application_Model_DbTable_Project();
        $select = $table->select();
		$select->from(array("project"), array('id','name'));
         if($auth->getIdentity()->type=="admin")
        {
              $select->where('id in (?)', $select2);
        }
        else
        {
		    $select->where('id  in (?)', $select2);
        }
        if($u==0)
        {
            $select = $table->select();
		    $select->from(array("project"), array('id','name'));
        }
        $projectList = $table->fetchAll($select);
	    return $projectList; 
    }
    
	public function projectUser($userId){
	    $auth = Zend_Auth::getInstance();
		$table1 = new Application_Model_DbTable_ProjectUser();
		$select2 = $table1->select();
		$select2->from(array("project_user"), array('project_id'));
        $select3 = $table1->select();
        $select3->from(array("project_user"), array('project_id'));
       
        if($auth->getIdentity()->type=="admin")
        {
		  $select2->where("user_id= ? ", $auth->getIdentity()->id);
          $select3->where("user_id= ? ", $userId);
        }
        else
        {
          $select2->where("user_id= ? ", $userId);  
        }
        $table = new Application_Model_DbTable_Project();
        $select = $table->select();
        
		$select->from(array("project"), array('id','name'));
         if($auth->getIdentity()->type=="admin")
        {
            
              $select->where('id in (?)', $select2);
        }
        else
        {
		    $select->where('id  in (?)', $select2);
        }
    
          if($auth->getIdentity()->id==0)
        {
            $select = $table->select();
		    $select->from(array("project"), array('id','name'));
        }
        #echo $select;
        #die;
        $projectList = $table->fetchAll($select);
	    return $projectList; 
	}
	public function addprojectUser($userId){
	    $auth = Zend_Auth::getInstance();
		$table1 = new Application_Model_DbTable_ProjectUser();
		$select2 = $table1->select();
		$select2->from(array("project_user"), array('project_id'));
                $select3 = $table1->select();
                $select3->from(array("project_user"), array('project_id'));
        if($auth->getIdentity()->type=="admin")
        {
		  $select2->where("user_id= ? ", $auth->getIdentity()->id);
          $select3->where("user_id= ? ", $userId);
        }
        else
        {
          $select2->where("user_id= ? ", $userId);  
        }
        $table = new Application_Model_DbTable_Project();
        $select = $table->select();
	$select->from(array("project"), array('id','name'));
        #echo $auth->getIdentity()->rights;
        #echo $_SESSION['Zend_Auth'][storage]->rights;
         #if(strpos($_SESSION['Zend_Auth'][storage]->rights;,'13')>0)
         if($auth->getIdentity()->type=="admin" || ($auth->getIdentity()->type=="salesagent" && strpos($_SESSION['Zend_Auth'][storage]->rights,'13')>0))
        {
            
              $select->where('id in (?)', $select2);
              #$select->where('id not in (?)', $select3);
               
        }
        else
        {
		    $select->where('id NOT in (?)', $select2);
        }
        #echo $select2;
        #echo '<br>'.$select3;
        #echo '<br>'.$select;
        #die(); 
		$projectList = $table->fetchAll($select);
	    return $projectList; 
	}
    
    
    
    
    
    
	public function projectDetail()
    {
        
	}
	
	public function projectUser1($userId,$formshow)
    {
        
		$auth = Zend_Auth::getInstance();
        
        if($formshow=='edit' || $auth->getIdentity()->type=="admin")
        {
          $userId=$userId;  
        }
        
		$table1 = new Application_Model_DbTable_ProjectUser();
		$select2 = $table1->select();
		$select2->from(array("project_user"), array('project_id'));
		$select2->where("user_id= ? ", $userId);
		
        if($auth->getIdentity()->type=="superadmin")
        {
    		$table = new Application_Model_DbTable_Project();
            $select = $table->select();
    		$select->from(array("project"), array('id','name'));
    		$select->where('id NOT in (?)', $select2);
        }
        $auth->getIdentity()->type;
		if($auth->getIdentity()->type=="salesagent")
            {
                $select->where("created_by= ? ", $auth->getIdentity()->id);
		    }
		if($auth->getIdentity()->type=="admin")
        {
            $table = new Application_Model_DbTable_Project();
            $select = $table->select();
    		$select->from(array("project"), array('id','name'));
    		$select->where('id in (?)', $select2);
            
			$user = new Application_Model_User();
			$ids = $user->getChildUser();
			$select->orWhere("created_by IN (132)");
     	}
        
      #  echo $select; 
	

		$projectList = $table->fetchAll($select);
       
	    return $projectList;
	}
	
	public function findTarion($tarionId){
		$table = new Application_Model_DbTable_Tarion();
		$select = $table->select();
		#$select->from(array("tarion"), array());
		$select->where("tarion.id= ? ", $tarionId);
		$tarionList = $table->fetchAll($select);
		return $tarionList;
	}
	 public function tarionsave($data) {
        $db = new Application_Model_DbTable_Tarion();
        if (isset($data['id']) && $data['id'] != "")
            $db->update($data, "id = " . $data['id']);
        else
            $result = $db->insert($data);
        return $result;
    }
	public function tarionread() {
		
        $db = new Application_Model_DbTable_Tarion();
        $result = $db->fetchAll(null);
        return $result;
    }

}

