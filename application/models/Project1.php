<?php

class Application_Model_Project {

    public function read() {
		
        $db = new Application_Model_DbTable_Project();
        $result = $db->fetchAll(null);
		echo '<pre>';
		//print_r($result);
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
        $select->from(array('p'=>"project"), array('id',
            'name',       
        ));
		   
		if($auth->getIdentity()->type=='superadmin'){
        
            
           
	#$select->joinLeft(array('u'=>'users'),'u.id = project.created_by',array('concat(u.first_name," ",u.last_name) as uname'));  Comment by ankit
             echo 'step1';
		}
		/*if($auth->getIdentity()->type=='admin') {
		   $select->where("project.created_by= ? ", $auth->getIdentity()->id);   Comment by ankit
		}*/
            $rights = explode(",",$auth->getIdentity()->rights); 
            if($auth->getIdentity()->type=="salesagent" )
            {
                $select->join('project_user',('p.id = project_user.project_id'), array());
                $select->where("project_user.user_id= ? ", $auth->getIdentity()->id);
                $ids=$auth->getIdentity()->id;
                $select->where("created_by IN ($ids)");
		    }
            
		   if($auth->getIdentity()->type=="admin"){
				$user = new Application_Model_User();
				$ids = $user->getChildUser();
				
				$rights=explode(',',$_SESSION['Zend_Auth'][storage]->rights);
				
                if(in_array(22, $rights))
				{
					$select->where("created_by IN ($ids)");
                    echo 'step3';
				}
              echo 'step4';  
		   }
          #echo $select;

        return $select;
    }
	  public function getUserProjectData($userId)
    {
        $table = new Application_Model_DbTable_Project();
        $select = $table->select();
		$select->from(array("project"), array('id','name'));
        $select->join(array('pu'=>'project_user'),'project.id = pu.project_id',array());
    	$select->where("pu.user_id= ? ", $userId);
		
		$projectList = $table->fetchAll($select);
		//print_r($projectList);
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
    public function getProjectDataGridOption($editLink,$clientLink ,$deleteLink,$projectdetailLink,$projectRelease,$url) 
    {
        $sourceObject = $this->_getDepartmentDataSql();
        /* Set department listing options in a grid */
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
                    'projectdetail' => array(
                        'title' => 'Project Detail',
                        'class' => 'deleteDepartmentRecord',
                        'image' => '/public/images/detail.png',
                        'link' => $projectdetailLink,
                        'param1' => 'id'
                    ),
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
		
        return $options;
    }
    
    public function getUserProjectDataGridOption($deleteLink, $userId, $url) 
    {
        $sourceObject = $this->_getUserProjectDataSql($userId);
        /* Set department listing options in a grid */
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
	/*public function projectUser($userId){
	
		$table1 = new Application_Model_DbTable_ProjectUser();
		$select2 = $table1->select();
		$select2->from(array("project_user"), array('project_id'));
		$select2->where("user_id= ? ", $userId);
		
		$table = new Application_Model_DbTable_Project();
        $select = $table->select();
		$select->from(array("project"), array('id','name'));
		$select->where('id NOT in (?)', $select2);
	
		$projectList = $table->fetchAll($select);
	    return $projectList;
	}*/
	
	public function projectDetail(){
	}
	
	public function projectUser($userId){ 
	
		$auth = Zend_Auth::getInstance();
		$table1 = new Application_Model_DbTable_ProjectUser();
		$select2 = $table1->select();
		$select2->from(array("project_user"), array('project_id'));
		$select2->where("user_id= ? ", $userId);
		
		$table = new Application_Model_DbTable_Project();
        $select = $table->select();
		$select->from(array("project"), array('id','name'));
		$select->where('id NOT in (?)', $select2);
		if($auth->getIdentity()->type=="salesagent")
        {
                $select->where("created_by= ? ", $auth->getIdentity()->id);
		}
		   if($auth->getIdentity()->type=="admin"){
				$user = new Application_Model_User();
				$ids = $user->getChildUser();
				$select->where("created_by IN ($ids)");
     	   }
		  
	
		$projectList = $table->fetchAll($select);  
	    return $projectList;
	}
	
	public function findTarion($tarionId){
		$table = new Application_Model_DbTable_Tarion();
		$select = $table->select();
		$select->from(array("tarion"), array());
		$select->where("tarion.id= ? ", $tarionId);
		//$tarionList = $table->fetchAll($select);
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

