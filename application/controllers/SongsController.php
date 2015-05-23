<?php

class SongsController extends Zend_Controller_Action
{
    private $singerSession;
    
    public function init()
    {
        /* Initialize action controller here */
        Zend_Session::start();
        $this->singerSession = new Zend_Session_Namespace('singer');        
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $random        = $request->getParam('random');
        $first         = $request->getParam('first');
        $singerParam   = $request->getParam('singer');    
        $page          = $request->getParam('page');
        $applyReorder  = $request->getParam('apply-reorder');
        $showAllPhotos = $request->getParam('show-photos');
          
        $singersMapper       = new Application_Model_SingerMapper();
        $songGalleriesMapper = new Application_Model_SongGalleriesMapper();
        $songGalleries       = new Application_Model_SongGalleries();
        
        /*
            IF WE LOAD SINGER FIRST TIME .. either by link Gallery from 
            home page or by loading random singer by clicking on image on 
            right panel
        */
        if($random || $first) {
            if($first) {
                $singer = $singersMapper->findFirstRec();
                $this->singerSession->id = $singer->id;                
            }
            if($random) {
                do {
                    $singer = $singersMapper->findRandomRec();                
                } while(
                    $this->singerSession->id == $singer->id
                );
                
                $this->singerSession->id = $singer->id;
            }
        } //Singer is allready uploaded, only we want to apply different
          //features 
        elseif($singerParam != null) {
            $singer = new Application_Model_Singer();
            $singersMapper->find($singerParam, $singer);
        }    
        
        $this->view->singer   = $singer;
        
        /*
         * First fetched a singer, next step is to find all his songs, photos
         */
        $songs = $songGalleriesMapper->fetchAllSongs($singer->id);
        foreach ($songs as $song) {
            $srcFolder = ROOT_PATH.'/img/singers/'.$singer->folder;
            $thumb = $srcFolder.'/thumbs/'.$song->galleryimg;
           
            if (!file_exists($thumb)) {
               $songGalleries->makeThumb($srcFolder,$song->galleryimg,$thumb); 
            }                
        }
        // .. then listing them
        /**
        * In normal state I show a paginator, only when user click Show all photos
        * or wants to reorder ALL photos, all should be shown
        */
        if($applyReorder != null
            || $showAllPhotos != null) {
            $this->view->songs = $songs;
            $this->view->paginate = false;            
        } else {
            $paginator = Zend_Paginator::factory($songs);
            if($page != null) {
                $paginator->setCurrentPageNumber($page);    
            } else {
                $paginator->setCurrentPageNumber(1);    
            }
            
            $paginator->setItemCountPerPage($songGalleries->getImagesPerPage());
            $this->view->songs = $paginator;
            $this->view->paginate = true;
        }
    }
    
    /**
    * Algorithm for reordering photos called by Ajax
    */
    public function reorderAction()
    {
        $singersMapper = new Application_Model_SingerMapper();
        $songGalleriesMapper = new Application_Model_SongGalleriesMapper();
        
        $post = $this->getRequest()->getPost();

        $singer      = $post['singerId'];
        $movedItemId = $post['songId'];
        $startPos    = $post['start_pos'];
        $endPos      = $post['end_pos'];
        
        $error = "";
        try {
            $songGalleriesMapper->reorderSingerSongs($startPos, $endPos, 
                                         $movedItemId, $singer);
        } catch(Exception $e) {
            $error = $e->getMessage();    
        }

        // makes disable renderer
        $this->_helper->viewRenderer->setNoRender();
        //makes disable layout
        $this->_helper->getHelper('layout')->disableLayout();
        
        echo $error;
    }


}

