<?php

class Application_Model_Country
{
  public function findAll()
  {
        $fileCache=Zend_Registry::get('fileCache');
        //$fileCache->save(null , 'countryList');
        if(!$countryList = $fileCache->load('countryList')){
            $db = new Application_Model_DbTable_Country();
            $result = $db->fetchAll(null);
            $countryList=array();
            foreach($result as $country){
                $countryList[$country->id]=$country->name;
            }
            $fileCache->save($countryList , 'countryList');
            echo "Not from caching";
        }
        return $countryList;
  }
}

