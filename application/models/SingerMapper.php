<?php

class Application_Model_SingerMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) { 
            $this->setDbTable('Application_Model_DbTable_Singers');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Singer $singer)
    {
        $data = array(
            'id'     => $singer->getId(),
            'name'   => $singer->getName(),
            'author' => $singer->getAuthor(),
            'mainimg' => $singer->getMainImg()
        );
 
        if (null === ($id = $singer->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Singer $singer)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $singer->setId($row->id)
             ->setName($row->name)
             ->setFolder($row->folder)
             ->setMainImg($row->mainimg);
    }
    
    public function findFirstRec()
    {
        $row = $this->getDbTable()->fetchRow();
        return $row;
    } 
    public function findRandomRec()
    {
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()
                 ->select()
                 ->order('RAND()')
                 ->limit(1)
                 );
        return $row;
    }
    
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Singer();
            $entry->setId($row->id)
                  ->setName($row->name)
                  ->setAuthor($row->author)
                  ->setMainImg($row->mainimg);
            $entries[] = $entry;
        }
        return $entries;
    }

}

