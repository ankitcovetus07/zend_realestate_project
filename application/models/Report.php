<?php

class Application_Model_Report 
{

    public function _getClientReportDataSql()
    {
        $dao = Zend_Db_Table::getDefaultAdapter();
		$select = $dao->select();
        $columns = array();
        $columns['S.N.'] = 'suit_number';
        $columns['S.U.N.'] = 'unit_number';
        $columns['S.L.N'] = 'suit_level';
        $columns['First name'] = 'first_name';
        $columns['Last name'] = 'last_name';
        $columns['Purchase price'] = 'purchase_price';
        $columns['Purchase date'] = 'purchase_date';
        
        $select->from(array("client"), $columns)->order('suit_number');
		return $select;
    }

    public function getReportData($name) 
    {
        $sourceObject = $this->_getDataSql($name);
        /* Set department listing options in a grid */
        $options = array(
            'sourceObject' => $sourceObject,
            'columns' => array(),
            'recordPerPage' => '50',
            'filters' => array(),
            'filtersText' => array(),
            'setShowOrderImage' => false,
            'setAjax' => 'dataGridDepartment',
            'setKeyEventsOnFilters' => false,
            'paginationInterval' => array('50' => '50', '100' => '100', '200' => '200'),
        );
		return $options;
    }
    
    private function _getDataSql($name)
    {
        switch ($name){
            case 'client_report':
                return $this->_getClientReportDataSql();
                break;
            default:
                return $this->_getClientReportDataSql();
                break;
        }
    }

}

