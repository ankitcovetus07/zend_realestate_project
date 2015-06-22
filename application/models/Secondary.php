<?php

class Application_Model_Secondary {

    public function findAll() {
        $db = new Application_Model_DbTable_Secondary();
        $result = $db->fetchAll();
        return $result;
    }
  
    public function find($id) {
        $db = new Application_Model_DbTable_Secondary();
        $result = $db->find($id);
        return $result;
    }

    public function save($data) {
        $db = new Application_Model_DbTable_Secondary();
	    if (isset($data['id']) && $data['id'] != "" && $data['id'] != 0) {
            $db->update($data, "id = " . $data['id']);
            $result = $data['id'];
        }
        else {
		    $result = $db->insert($data);
			//$result= $db->getAdapter()->lastInsertId();
	    }
        return $result;
    }

    public function delete($id) {
        $db = new Application_Model_DbTable_Secondary();
        $db->delete('id=' . $id);
        return $result;
    }
    
    public function findPurchaserByProjectClient($clientId)
    {
        $db = new Application_Model_DbTable_Secondary();
        $select = $db->select()->where("client_id=?", $clientId);
        return $db->fetchAll($select);
    }
    public function secondPurchaserByProjectClient($clientId)
    {
        $db = new Application_Model_DbTable_Secondary();
        $select = $db->select()->where("id=?", $clientId);
        return $db->fetchAll($select);
    }

}

