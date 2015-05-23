<?php

class Application_Model_SongGalleriesMapper
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
            $this->setDbTable('Application_Model_DbTable_SongGalleries');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_SongGalleries $songGallery)
    {
        $data = array(
            'imgid'     => $songGallery->getImgId(),
            'gallerysongid'   => $songGallery->getGallerySongId(),
            'galleryimg' => $songGallery->getGalleryImg(),
            'galleryimgalt' => $songGallery->getGalleryImgAlt()
        );
 
        if (null === ($id = $song->getId())) {
            unset($data['imgid']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('imgid = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_SongGalleries $songGallery)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $songGallery->setId($row->imgid)
                    ->setName($row->name)
                    ->setAuthor($row->author)
                    ->setMainImg($row->mainimg);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll()->order('sort ASC');
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_SongGalleries();
            $entry->setImgId($row->imgid)
                  ->setGallerySongId($row->gallerysongid)
                  ->setGalleryImg($row->galleryimg)
                  ->setGalleryImgAlt($row->galleryimgalt);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAllSongs($songId)
    {
        $resultSet = $this->getDbTable()
                          ->fetchAll('gallerysongid = '.$songId, 
                          'sort ASC');
                          
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_SongGalleries();
            $entry->setImgId($row->imgid)
                  ->setGallerySongId($row->gallerysongid)
                  ->setGalleryImg($row->galleryimg)
                  ->setGalleryImgAlt($row->galleryimgalt);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function reorderSingerSongs($startPos, $endPos, 
        $movedItemId, $singer           
    )
    {
        //new Zend_Db_Expr('order - 1')
        if($startPos < $endPos) {
            $this->getDbTable()->update(array('sort' => new Zend_Db_Expr('sort - 1')),
                                        array('sort <= ?' => $endPos,
                                              'sort > ?' => $startPos,
                                              'gallerysongid = ?' => $singer
                                    ));            
            $this->getDbTable()->update(array('sort' => $endPos),
                                        array('imgid = ?' => $movedItemId,                                              
                                              'gallerysongid = ?' => $singer
                                    ));            

        }
        if($startPos > $endPos) {
            $this->getDbTable()->update(array('sort' => new Zend_Db_Expr('sort + 1')),
                                        array('sort < ?' => $startPos,
                                              'sort >= ?' => $endPos,
                                              'gallerysongid = ?' => $singer
                                    ));            
            $this->getDbTable()->update(array('sort' => $endPos),
                                        array('imgid = ?' => $movedItemId,                                              
                                              'gallerysongid = ?' => $singer
                                    ));            

        }
    }
}

