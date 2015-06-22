<?php

class Application_Model_Client 
{

    public function read() 
    {
		
        $db = new Application_Model_DbTable_Client();
        $result = $db->fetchAll(null);
        return $result;
    }

    public function find($id) 
    {
        $db = new Application_Model_DbTable_Client();
        $result = $db->find($id);
        return $result;
    }

    public function save($data) 
    {
      if($data['id']==0)
      {
        unset($data['id']);
      }
        $db = new Application_Model_DbTable_Client();
        if ($data['id']>0 )
          $db->update($data, "id = " . $data['id']);  
        else
        {$result = $db->insert($data);}
        return $result;
    }

    public function delete($id) 
    {
        $db = new Application_Model_DbTable_Client();
        $db->delete('id=' . $id);
    }
    public function ajax($PID=0,$SID=0,$PARKID=0,$LOCKERID=0)
    {
       
        $table = new Application_Model_DbTable_Client();
        #$dao = new Application_Model_DbTable_Client();
        $select = $table->select();
        $select->from(array("c"=>"client"));
        $select->where("project_id=$PID");
        $select->where("suit_number=$SID OR parking_number=$PARKID OR  locker_number=$LOCKERID");
        #echo $select;
        #print_r($table); 
        $row=$table->fetchAll($select);
        return count($row);
      }
    public function _getClientDataSql($projectId)
    { 
        if($projectId>=0 && !empty($projectId))
        {
             $_SESSION['p']=$projectId;
        }
        $dao = new Application_Model_DbTable_Client();
		$auth = Zend_Auth::getInstance();
	        $select = $dao->getDefaultAdapter()->select();
                $select->from(array("c"=>"client"), array('id','project_id',
            'first_name',
			'last_name',
			'email_address',
			'purchase_price',
			'sin_number',
			'address',
			'suit_number',
			'parking_number',
			'locker_number',
			'status',
            'created_by',
           ));
                   $select->JoinInner(array('p' => 'project'),'c.project_id = p.id','p.name');
                  
//$select->where("project_id= ? ", $_SESSION['p']);
		   $rights = explode(",",$auth->getIdentity()->rights);
		   if($auth->getIdentity()->type=="salesagent"   && in_array(34, $rights)){ 
                   $select->where("c.created_by= ? ", $auth->getIdentity()->id);
		   }
		   if($auth->getIdentity()->type=="admin"){
				$user = new Application_Model_User();
				$ids = $user->getChildUser();
				 $rights=explode(',',$_SESSION['Zend_Auth'][storage]->rights);
				if(in_array(34, $rights) ) 
				{
					$select->where("c.created_by IN ($ids)");
				}
		   }
                   
                   if($auth->getIdentity()->type=="superadmin"){
                       if(isset($_SESSION['p']) && $_SESSION['p']>0)
                            {
                              $select->where("project_id= ? ", $_SESSION['p']);  // Project Show Using Project ID IN Super User
                            }
                   }
                  
                    if($_SESSION['p']!="" && in_array(33, $rights) && !in_array(34, $rights) )
                        {
                            if(isset($_SESSION['p']) && $_SESSION['p']>0)
                            {
                              $select->where("project_id= ? ", $_SESSION['p']);  
                            }

                        }
          #echo $select;  
          #$select="SELECT `c`.`id`, `c`.`project_id`, `c`.`first_name`, `c`.`last_name`, `c`.`email_address`, `c`.`purchase_price`, `c`.`sin_number`, `c`.`address`,  `p`.`name`,`c`.`suit_number`, `c`.`parking_number`, `c`.`locker_number`, `c`.`status`, `c`.`created_by` FROM `client` AS `c` INNER JOIN `project` AS `p` ON c.project_id = p.id WHERE (project_id= '2' ) ";
         #echo $select; 
      return $select;
    }

    public function getClientDataGridOption($editLink, $deleteLink, $viewLink,$projectId ,$url) 
    {
        #echo $deleteLink;
        #die;
       $sourceObject = $this->_getClientDataSql($projectId);
	#print_r($sourceObject);	
        /* Set department listing options in a grid */
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(
                'id' => array('hidden' => true),
                'project_id' => array('hidden' => true),
		'first_name' => array('title' => 'First Name'),
                'last_name' => array('title' => 'Last Name'),
		'email_address' => array('title' => 'E-mail'),
		'purchase_price' => array('title' => 'Price'),
                'sin_number' => array('title' => 'Social Insurance Number'),
                'address' => array('title' => 'Current Mailing Address'),
                'name' => array('title' => 'Project Name'),
                'suit_number' => array('title' => 'Suite'),
                'suit_unit_number' => array('title' => 'Unit'),
                'suit_level' => array('title' => 'Suite Level'),
                'parking_number' => array('title' => 'Park'),
                'parking_unit_number' => array('title' => 'Park Unit'),
                'parking_level_number' => array('title' => 'Park Level'),
                'locker_number' => array('title' => 'Locker'),
                'locker_unit_number' => array('title' => 'Locker Unit'),
                'locker_level_number' => array('title' => 'Locker level'),
                'status' => array('hidden' => true),
                'created_by' => array('hidden' => true),
            ),
            'actions' => array(
                'data' => array(
					
					
					'view' => array(
                        'title' => 'View',
                        'image' => $url.'/images/text.png',
                        'link' => $viewLink,
                        'param1' => 'id',
						'status'=>''
                    ),
                    'edit' => array(
                        'title' => 'Edit',
                        'image' => $url.'/images/edit.gif',
                        'link' => $editLink,
                        'param1' => 'id',
						'status'=>''
                    ),
                    'delete' => array(
                        'title' => 'Delete',
                        'class' => 'deleteDepartmentRecord',
                        'image' => $url.'/images/grid_delete.png',
                        'link' => $deleteLink,
                        'param1' => 'id',
						'status'=>''
                    ),
                ),
                'position' => 'right',
                'title' => '',
            ),
            'recordPerPage' => '10',
            'setShowOrderImage' => false,
            'filters' => array(),
            'filtersText' => array(),
            'setAjax' => 'dataGridDepartment',
			 'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
		$auth = Zend_Auth::getInstance();
		if($auth->getIdentity()->type=='salesagent'){
			$options['actions']['data']['view']['status']='status';
			$options['actions']['data']['edit']['status']='status';
			$options['actions']['data']['delete']['status']='status';
		}
                #echo '<pre>';print_r($options);
		return $options;
    }
	public function clientInformation($clientId){
		$table = new Application_Model_DbTable_Client();
		$auth = Zend_Auth::getInstance();
        $select = $table->select();
		$select->setIntegrityCheck(false);
		$select->from(array("client"), array('client.*'));
        $select->joinleft(array('p'=>'project'),'client.project_id = p.id',array('name as project_name'));
		//if($auth->getIdentity()->type!="superadmin"){
			$select->where("client.id= ? ", $clientId);
		//}	
		$clientInfo = $table->fetchAll($select);
	    return $clientInfo;
	}
	public function checksuitavailabel($project_id,$suit_number,$suit_unit_number){
		$table = new Application_Model_DbTable_Client();
		$select = $table->select();
		$select->setIntegrityCheck(false);
		$select->from(array("client"), array('client.id'));
      	$select->where("client.project_id= ? ", $project_id);
      	$select->where("client.suit_number= ? ", $suit_number);
      	$select->where("client.suit_unit_number= ? ", $suit_unit_number);
		$clientInfo = $table->fetchAll($select);
	
		$clientId=0;
	    if(isset($clientInfo[0])){
			$clientId = $clientInfo[0]->id;
		}
		return $clientId;
	}
    
    public function searchClients($data)
    {
        $table = new Application_Model_DbTable_Client();
		$select = $table->select();
		$select->setIntegrityCheck(false);
		$select->from(array("client"));
        if($data['suit_number'] != '')
            $select->where("client.suit_number= ? ", $data['suit_number']);
			
        if($data['suit_unit_number'] != '')
            $select->where("client.suit_unit_number= ? ", $data['suit_unit_number']);
        
		if($data['first_name'] != '')
      	$select->where("client.first_name= ? ", $data['first_name']);
      	if($data['last_name'] != '')
      	$select->where("client.last_name= ? ", $data['last_name']);
        if($data['projectlist'] >0)
      	$select->where("client.project_id= ? ", $data['projectlist']);
        
		$clients = $table->fetchAll($select);
		return $clients;
    }
	public function clientData($pid){
			$dao1 = new Application_Model_DbTable_Client();
			$select1 = $dao1->select();
			$select1->distinct();
			$select1->from(array('client'), array('first_name','last_name','email_address','address','phone_number','sin_number','date_of_birth'));
			$select1->where("id=?",$pid);
			$searchData = $dao1->fetchAll($select1);
			return $searchData;
	}
	public function search($email){
		$searchData= array();
		$table = new Application_Model_DbTable_Client();
		$select = $table->select();
		$select->setIntegrityCheck(false);
		$select->from(array("client"));
        $select->where("email_address= ? ", $email);
        $select->limit(1);
        #echo $select;
        
		$clients = $table->fetchAll($select);
        #echo '<pre>';print_r($clients[0]);
        #die;
		
		if(isset($clients[0])){
			$searchData['first_name']    =   $clients[0]['first_name'];
			$searchData['last_name']     =   $clients[0]['last_name'];
			$searchData['email_address'] =   $clients[0]['email_address'];
			$searchData['address']       =   $clients[0]['address'];
			$searchData['phone_number']  =   $clients[0]['phone_number'];
			$searchData['sin_number']    =   $clients[0]['sin_number'];
			$searchData['date_of_birth'] =   $clients[0]['date_of_birth'];
			$searchData['pid']           =   $clients[0]['id'];
			$searchData['t']             =   1;
		}
		else{
			$table = new Application_Model_DbTable_Secondary();
			$select1 = $table->select();
			$select1->setIntegrityCheck(false);
			$select1->from(array("secondary_client"));
			$select1->where("secondary_client.email_address= ? ", $email);
			$select1->limit(1);
			$clients = $table->fetchAll($select1);
			if(isset($clients[0])){
				$searchData['first_name']=$clients[0]['first_name'];
				$searchData['last_name']=$clients[0]['last_name'];
				$searchData['email_address']=$clients[0]['email_address'];
				$searchData['address']=$clients[0]['address'];
				$searchData['phone_number']=$clients[0]['phone_number'];
				$searchData['sin_number']=$clients[0]['sin_number'];
				$searchData['date_of_birth']=$clients[0]['date_of_birth'];
				$searchData['pid']=$clients[0]['id'];
				$searchData['t']=2;	
			}
		}
		return $searchData;
	}
}

