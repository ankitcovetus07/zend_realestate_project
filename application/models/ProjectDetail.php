<?php
class Application_Model_ProjectDetail 
{

    public function save($data) 
    {
        print_r($data);
        $db = new Application_Model_DbTable_ProjectDetail();
        if(isset($data['projectid']))
        {
            $db->delete("projectid = " . $data['projectid']);
        }
        #die;
        $result = $db->insert($data);        
        return $result;
    }
    
    public function find($id) 
    {
        $db = new Application_Model_DbTable_ProjectDetail();
        $result = $db->fetchAll("projectid = " . $id);
        return $result;
    }
     
    public function deleteUserProject($id) 
    {
        $db = new Application_Model_DbTable_ProjectDetail();
        
        $db->delete('id = ' . $id);
    }
}