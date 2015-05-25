<?php

class Application_Model_SongGalleries
{
    private $thumb_height;
    private $thumb_width;
    private $images_per_page;
    
    protected $_imgid;
    protected $_gallerysongid;
    protected $_galleryimg;
    protected $_galleryimgalt;
 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/config.ini', 
        'gallery');
        $this->thumb_width  = $config->thumbnail_width;
        $this->thumb_height = $config->thumbnail_height;        
        $this->images_per_page = $config->images_per_page;        
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid gallery singers property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid gallery singers property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setImgId($id)
    {
        $this->_imgid = (string) $id;
        return $this;
    }
 
    public function getImgId()
    {
        return $this->_imgid;
    }
 
    public function setGallerySongId($id)
    {
        $this->_gallerysongid = (string) $id;
        return $this;
    }
 
    public function getGallerySongId()
    {
        return $this->_gallerysongid;
    }
 
    public function setGalleryImg($img)
    {
        $this->_galleryimg = $img;
        return $this;
    }
 
    public function getGalleryImg()
    {
        return $this->_galleryimg;
    }
 
    public function setGalleryImgAlt($alt)
    {
        $this->_galleryimgalt = $alt;
        return $this;
    }
 
    public function getGalleryImgAlt()
    {
        return $this->_galleryimgalt;
    }
    
    public function getImagesPerPage()
    {
        return $this->images_per_page;
    }    
    
    public function makeThumb($folder,$song,$dest)
    {
        // image source
        $imgExt = pathinfo(basename($song),PATHINFO_EXTENSION); 
        // image extension
        $imgExt = strtolower($imgExt);        

        if(($imgExt == "jpg") || ($imgExt == "jpeg")){ 
            $sourceImage = imagecreatefromjpeg($folder.'/'.$song); 
        }
        if($imgExt == "gif"){ 
            $sourceImage = imagecreatefromgif($folder.'/'.$song); 
        }
        if($imgExt == "png"){ 
            $sourceImage = imagecreatefrompng($folder.'/'.$song); 
        }
        
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);
        $x      = 0;
        $y      = 0;

        // Calculate ratios
        $srcRatio   = $width / $height;
        $thumbRatio = $this->thumb_width / $this->thumb_height;

        if ($srcRatio > $thumbRatio) {

            // Preserver original width
            $originalWidth = $width;

            // Crop image width to proper ratio
            $width = $height * $thumbRatio;

            // Set thumbnail x offset
            $x = ceil(($originalWidth - $width) / 2);

        } elseif ($srcRatio < $thumbRatio) {

            // Preserver original height
            $originalHeight = $height;

            // Crop image height to proper ratio
            $height = ($width / $thumbRatio);

            // Set thumbnail y offset
            $y = ceil(($originalHeight - $height) / 2);

        }

        $virtualImage = imagecreatetruecolor($this->thumb_width,$this->thumb_height);
        
        imagecopyresampled($virtualImage,$sourceImage,0,0,$x,$y, 
            $this->thumb_width,$this->thumb_height,$width,$height);
        
        if($imgExt == "jpg"){ 
            imagejpeg($virtualImage, $dest, 100); 
        }
        if($imgExt == "gif"){ 
            imagegif($virtualImage, $dest); 
        }
        if($imgExt == "png"){ 
            imagepng($virtualImage, $dest); 
        }

        
    }


}

