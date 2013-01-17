<?php
class Helper_Image
{
    
    protected $thumb        = '50x50';
    protected $medium       = '150x150';
    protected $large        = '350x350';
    protected $_noimagePath = './img/260x180.gif';

    public static function instance()
    {
        return new Helper_Image();
    }
    
    public function buildData($config, $img, $mod, $link_path)
    {
        if(empty($img)){
          $this->imgResourse = $this->_noimagePath;
        }else{
          if($link_path)
            $this->imgResourse = Kohana::$config->load('config')->get($config).$link_path.$img;
          else
            $this->imgResourse = Kohana::$config->load('config')->get($config).$img;
        }

        switch ($mod){
              case ('thumb')  : $this->mode = $this->thumb;  break;
              case ('medium') : $this->mode = $this->medium; break;
              case ('large')  : $this->mode = $this->large;  break;
              default         : $this->mode = $this->medium; break;
        }
    }



    public function getCachePatch($config, $img, $mod = 'medium', $link_path = null)
    { 
            $this->buildData($config, $img, $mod, $link_path);
            echo URL::base().Library_Image::getInstance()->setImage($this->imgResourse)->heightCrop()->setSize($this->mode)->resize();
    }
    
    public function getClearCachePatch($config, $img, $mod = 'medium', $link_path = null)
    {
            $this->buildData($config, $img, $mod, $link_path);
            return URL::base().Library_Image::getInstance()->setImage($this->imgResourse)->heightCrop()->setSize($this->mode)->resize();
    }
  
    
}