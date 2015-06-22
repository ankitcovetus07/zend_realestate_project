<?php
class Application_Model_ProjectUser 
{

    public function save($data) 
    {
      #print_r($data);die; 
        $db = new Application_Model_DbTable_ProjectUser();
        
        
        $db->delete(array('user_id = ?' => $data['user_id'],'project_id=?'=>$data['project_id']));
        #echo $data;
        $result = $db->insert($data);
        #die;
        return $result;
    } 
    public function find($id) 
    {
        $db = new Application_Model_DbTable_ProjectUser();
        $result = $db->find($id);
        return $result;
    }
     public function deleteUserProject($id) 
     {
        $db = new Application_Model_DbTable_ProjectUser();
        $db->delete('id = ' . $id);
        
    }
}