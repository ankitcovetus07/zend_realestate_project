<?php

class Application_Model_Payment {

    public function read() 
    {
		$db = new Application_Model_DbTable_Payment();
        $result = $db->fetchAll(null);
        return $result;
    }

    public function find($id) 
    {
        $db = new Application_Model_DbTable_Payment();
        $result = $db->find($id);
        return $result;
    }

    public function save($data) 
    {
        $db = new Application_Model_DbTable_Payment();
        if (isset($data['id']) && $data['id'] != "")
            $db->update($data, "id = " . $data['id']);
        else
            $result = $db->insert($data);
        return $result;
    }

    public function delete($id) 
    {
        $db = new Application_Model_DbTable_Payment();
        $db->delete('id=' . $id);
    }
	
    public function _getDepartmentDataSql($client_id,$project_id,$suit_number)
    {
        $dao = new Application_Model_DbTable_Payment();
	    $select = $dao->getDefaultAdapter()->select();
        $select->from(array("payment_log"), array('id',
            'amount',
			'payment_date',
			'transit_number',
			'status',
			'created_by'
           ));
		$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.project_id= ? ", $project_id);
		#$select->where("payment_log.suit_number= ? ", $suit_number);
		$select->where("payment_log.type= 'payment'");  
        
        return $select;
    }
	public function _getWithdrawDepartmentDataSql($client_id,$project_id,$suit_number){
		 $dao = new Application_Model_DbTable_Payment();
	    $select = $dao->getDefaultAdapter()->select();
        $select->from(array("payment_log"), array('id',
            'amount',
			'payment_date',
			'created_by'
           ));
		$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.project_id= ? ", $project_id);
		#$select->where("payment_log.suit_number= ? ", $suit_number);
		$select->where("payment_log.type= 'withdraw'");  
        return $select;
	}
	public function getClientWithdrawDataGridOption($editLink, $deleteLink, $client_id, $project_id, $url,$suit_number){
    
        $sourceObject = $this->_getWithdrawDepartmentDataSql($client_id,$project_id,$suit_number);
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(
                'id' => array('hidden' => true),
				'amount' => array('title' => 'Amount'),
                'payment_date' => array('title' => 'Payment Date'),
				'created_by' => array('hidden' => true),
            ),
            'actions' => array(
                'data' => array(
					'edit' => array(
                        'title' => 'Edit',
                        'image' => $url.'/images/edit.gif',
                        'link' => $editLink,
                        'param1' => 'id'
                    ),
                    'delete' => array(
                        'title' => 'Delete',
                        'class' => 'deleteDepartmentRecord',
                        'image' => $url.'/images/grid_delete.png',
                        'link' => $deleteLink,
                        'param1' => 'id'
                    ),
                ),
                'position' => 'right',
                'title' => '',
            ),
            'recordPerPage' => '50',
            'setShowOrderImage' => false,
            'setAjax' => 'dataGridDepartment',
            'filters' => array(),
            'filtersText' => array(),
            'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
		$auth = Zend_Auth::getInstance();
		
		if($auth->getIdentity()->type=='salesagent'){
			$options['actions']='';
		}
		return $options;
		
	}	
    public function getClientPaymentDataGridOption($editLink, $deleteLink, $client_id, $project_id, $url,$status,$suit_number) 
    {
        $sourceObject = $this->_getDepartmentDataSql($client_id,$project_id,$suit_number);
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(
                'id' => array('hidden' => true),
				'amount' => array('title' => 'Amount'),
                'payment_date' => array('title' => 'Payment Date'),
                'transit_number' => array('title' => 'Transit Number'),
				'status' => array('title' => 'Status'),
                'type' => array('title' => 'Type'),
                'NSF_fee' => array('title' => 'NSF Fee'),
               'created_by' => array('hidden' => true),
            ),
            'actions' => array(
                'data' => array(
					'edit' => array(
                        'title' => 'Edit',
                        'image' => $url.'/images/edit.gif',
                        'link' => $editLink,
                        'param1' => 'id'
                    ),
                    'delete' => array(
                        'title' => 'Delete',
                        'class' => 'deleteDepartmentRecord',
                        'image' => $url.'/images/grid_delete.png',
                        'link' => $deleteLink,
                        'param1' => 'id'
                    ),
                ),
                'position' => 'right',
                'title' => '',
            ),
            'recordPerPage' => '50',
            'setShowOrderImage' => false,
            'setAjax' => 'dataGridDepartment',
            'filters' => array(),
            'filtersText' => array(),
            'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
		$auth = Zend_Auth::getInstance();
		
		if($status=='deactive'  && $auth->getIdentity()->type=='salesagent'){
			$options['actions']='';
		}
		return $options;
    }
    public function totalpaidamount($project_id)
    {
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalTarionAmount'=>'SUM(amount)'));
		$select->where("payment_log.type= 'payment' ");
		$select->where("payment_log.project_id= ? ", $project_id);
        #echo $select;
		$totalamount = $table->fetchAll($select);	 
        if(!empty($totalamount))
        {
			if(!empty($totalamount[0]))
            {
				return $totalamount[0]['totalTarionAmount'];
			}
            else
			{	
                return 0;
            }
        }	
		else
        {
			return 0;
        }
	}
    
    public function totalwithdrawamount($project_id){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalTarionwithdrawAmount'=>'SUM(tarion_amount)'));
		$select->where("payment_log.type= 'withdraw' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		$totalamount = $table->fetchAll($select);		
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalTarionwithdrawAmount'];
			}else
				return 0;
		}	
		else
			return 0;
	
	}
    public function withdrawamounttest($amount,$project_id,$data=array(),$obj)
    {
       
       
        
        $table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('count'=>'COUNT(payment_log.amount)'));
		$select->where("payment_log.type= 'payment' ");
		$select->where("payment_log.project_id= ? ", $project_id);
        $select->group("payment_log.client_id");
        $totalamount = $table->fetchAll($select);
        
         
        $select1 = $table->select();
        $select1->setIntegrityCheck(false);
        $select1->
        from(array("payment_log"), array('suit_number'=>'suit_number','client_id'=>'client_id','amount'=>'sum(amount)'));
		$select1->where("payment_log.type= 'payment' ");
		$select1->where("payment_log.project_id= ? ", $project_id);
        $select1->group("payment_log.client_id");
        
        $totalamountpay = $table->fetchAll($select1); 
        
        
        
        $select2 = $table->select();
        $select2->setIntegrityCheck(false);
		$select2->from(array("payment_log"), array('suit_number'=>'suit_number','client_id'=>'client_id','amount'=>'sum(amount)'));
		$select2->where("payment_log.type= 'withdraw' ");
		$select2->where("payment_log.project_id= ? ", $project_id);
        $select2->group("payment_log.client_id");
        
        $totalamountwithdraw = $table->fetchAll($select2);
        
        $w=$v=$u=array();
        for($i=0;$i<count($totalamountpay);$i++)
        {
            $w[$i]=$totalamountpay[$i]->suit_number;
            $id=$totalamountpay[$i]->client_id;
            $v[$id]=$totalamountpay[$i]->amount;
        }
        for($i=0;$i<count($totalamountwithdraw);$i++)
        {
            $id=$totalamountwithdraw[$i]->client_id;
            $u[$id]=$totalamountwithdraw[$i]->amount;
        }
        $j=0;
        foreach($v as $k=>$vv)
        {
            
            if(isset($u[$k]))
            {
                $value=$v[$k]-$u[$k];
            }
            else
            {
                $value=$v[$k]-0;
            }
            if($value>round($amount/count($totalamount)))
            {
                $j++;
             
            }
            
        }
        
        if($j==count($totalamount))
        { $z=0;
            foreach($v as $k=>$vv)
            {
               echo  $data['suit_number']=$w[$z];
                $data['amount']=round($amount/count($totalamount));
                $data['client_id']=$k;
                $data['tarion_amount']=round($amount/count($totalamount)); 
                echo $obj->save($data);
                $z++;
            }
            #die();
          return 1;
        }
        else
        {
            return 0;
        }
        
    }
    
    
    /*
    public function totalpaidamount($client_id,$project_id,$suit_number){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalTarionAmount'=>'SUM(amount)'));
		//$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.type= 'payment' ");
		$select->where("payment_log.payment_type!= 'upgrade' ");
		$select->where("payment_log.status!= 'refunded' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		//$select->where("payment_log.suit_number= ? ", $suit_number);
		//$select->where("payment_log.status= 'clear'")
		$select->group('client_id'); 
       // echo $select; 
                
		$totalamount = $table->fetchAll($select);	 	
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalTarionAmount'];
			}else
				return 0;
	}	
		else
			return 0;
	
	}
    */
	public function totalregularamount($client_id,$project_id,$suit_number){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalRegularAmount'=>'SUM(regular_amount)'));
		$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.type= 'payment' ");
		$select->where("payment_log.payment_type!= 'upgrade' ");
		$select->where("payment_log.status!= 'refunded' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		$select->where("payment_log.suit_number= ? ", $suit_number);
		$select->where("payment_log.status= 'clear'")
				->group('client_id');
		$totalamount = $table->fetchAll($select);		
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalRegularAmount'];
			}else
				return 0;
		}	
		else
			return 0;
	
	}
	public function totalupgradeamount($client_id,$project_id,$suit_number){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalUpgradeAmount'=>'SUM(amount)'));
		$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.payment_type= 'upgrade' ");
		$select->where("payment_log.type= 'payment' ");
		$select->where("payment_log.status!= 'refunded' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		$select->where("payment_log.suit_number= ? ", $suit_number);
		$select->where("payment_log.status= 'clear'")
				->group('client_id');
		
		$totalamount = $table->fetchAll($select);		
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalUpgradeAmount'];
			}else
				return 0;
		}	
		else
			return 0;
	
	}
    
    
    
    
    
    
    
    
    
    
    
    
	/* public function totalwithdrawamount($client_id,$project_id,$suit_number){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalTarionwithdrawAmount'=>'SUM(tarion_amount)'));
		//$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.type= 'withdraw' ");
		//$select->where("payment_log.widthdraw_type!= 'tarion' ");
		//$select->where("payment_log.status!= 'refunded' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		//$select->where("payment_log.suit_number= ? ", $suit_number)
		$select->group('client_id');
			//echo $select;
		$totalamount = $table->fetchAll($select);		
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalTarionwithdrawAmount'];
			}else
				return 0;
		}	
		else
			return 0;
	
	}*/
	public function totalregularwithdrawamount($client_id,$project_id,$suit_number){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalRegularwithdrawAmount'=>'SUM(regular_amount)'));
		$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.type= 'withdraw' ");
		$select->where("payment_log.widthdraw_type= 'regular' ");
		$select->where("payment_log.status!= 'refunded' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		$select->where("payment_log.suit_number= ? ", $suit_number)
				->group('client_id');
		$totalamount = $table->fetchAll($select);		
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalRegularwithdrawAmount'];
			}else
				return 0;
		}	
		else
			return 0;
	}
    public function totalupgraderwithdrawamount($client_id,$project_id,$suit_number){
		$table= new Application_Model_DbTable_Payment();
	    $select = $table->select();
        $select->setIntegrityCheck(false);
		$select->from(array("payment_log"), array('totalUpgradewithdrawAmount'=>'SUM(amount)'));
		$select->where("payment_log.client_id= ? ", $client_id);
		$select->where("payment_log.type= 'withdraw' ");
		$select->where("payment_log.widthdraw_type= 'upgrade' ");
		$select->where("payment_log.status!= 'refunded' ");
		$select->where("payment_log.project_id= ? ", $project_id);
		$select->where("payment_log.suit_number= ? ", $suit_number)
				->group('client_id');
		
		$totalamount = $table->fetchAll($select);		
		if(!empty($totalamount)){
			if(!empty($totalamount[0])){
				return $totalamount[0]['totalUpgradewithdrawAmount'];
			}else
				return 0;
		}	
		else
			return 0;
	}
    
    public function getPaymentDetails($clientId = null, $fromDate = null, $toDate = null,$projectlist= 0)
    {
       
        
        $dao = new Application_Model_DbTable_Payment();
		$select = $dao->select();
        $select->setIntegrityCheck(false);
        $select->from(array('p' => 'payment_log'));
        $select->join(array('c' => 'client'), 
                'c.id = p.client_id and c.suit_number = p.suit_number and p.project_id = c.project_id',
                array('id', 'suit_number', 'suit_unit_number', 'suit_level', 'first_name', 'last_name', 'purchase_date', 'purchase_price')
         );
        if($clientId != '' && $clientId != null){
            $select->where('c.id = ?' , $clientId);
        }
        if($fromDate != '' && $fromDate != null){
            $select->where('p.created_date >= "' . $fromDate . '"');
        }
        if($toDate != '' && $toDate != null){
            $select->where('p.created_date <= "' . $toDate . '"');
        }
        if($fromDate != '' && $fromDate != null && $toDate != '' && $toDate != null && $projectlist>0)
        {
             $select->where('p.project_id =' . $projectlist);
        }
        if($projectlist>0 && $fromDate == null && $toDate == null){
            $select->where('p.project_id =' . $projectlist);
        }
        $select->order('p.client_id');
     
        $paymentData = $dao->fetchAll($select);
        return $paymentData->toArray();
    }
}