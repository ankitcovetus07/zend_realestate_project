<?php
class Application_Model_bank 
{
     public function save($data) 
    {
         $db = new Application_Model_DbTable_bank();
         if(!empty($data['id']))
         {
             $db->update($data, "id = " . $data['id']);
         
         }
         else
         {
            $result = $db->insert($data);
         }
        return $result;
    }
    public function read($id) {
		
        $db = new Application_Model_DbTable_bank();
        $select=null;
        if($id>0)
        {
        $select = $db->select()->where('project_id = ?', $id);
        }
        $result = $db->fetchAll($select);
		
		
        return $result;
    }
    public function delete($id) {
		
        $db = new Application_Model_DbTable_bank();
        $db->delete('id=' . $id);
        
    }
    public function Find($id) {
		
        $db = new Application_Model_DbTable_bank();
       $result = $db->find($id);
        
        return $result;
        
    }
}
