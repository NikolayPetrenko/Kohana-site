<?php
/**
 * @author kurijov
 * @version 1.0
 * @todo поворот, чоп
 * Представляет класс управления картинками,
 * намереваюсь сделать удобный кэш
 * 
 * @lastEdit 8.04.2011
 */
class Library_Image {
    protected $_noimagePath		    = 'images/no_image.png';
    protected $_cacheFolder                 = 'images/cache/';
    protected $_cacheIsActive		    = true;
    protected $_resizeIfSmaller		    = false;
    protected $_manualDestinationImagePath  = false;
    protected $_resourceImagePath           = '';
    protected $_destinationImagePath        = '';
    
    protected $_originalImageWidth          = 0;
    protected $_originalImageHeight         = 0;
    protected $_originalImageType           = 0;
    
    protected $_destinationImageWidth       = 0;
    protected $_destinationImageHeight      = 0;
    
    protected $_heightCrop		    = TRUE;
    protected $_typePosition		    = 'vert';//указывет как будет происходить склейка изображений vert - вертикально; hor- горизонтально
    
    protected $_resourceResource;
    protected $_destinationResource;
    protected $_errors			    = array();
    protected $_quality			    = 60;
    protected $_smartRatio		    =   FALSE;
    
    
    /**
     * картинка никогда не будет превышать заданных размеров 
     * при этом сохраняет пропорции
     * @return Default_Lib_Image
     */
    public function smartRatio($smart = TRUE)
    {
    	$this->_smartRatio = $smart;
    	return $this;
    }
    
    /**
     * Устанавливаем качество
     */
    public function setQualiy($quality = 60) {
    	$this->_quality = $quality;
    	return $this;
    }
    
    /**
     *
     * @return Default_Lib_Image
     */
    public static function getInstance()
    {
    	return new Library_Image();
    }
    
    /**
     * @return Default_Lib_Image
     */
    public function reset()
    {
    	$this->_resourceResource 		= NULL;
    	$this->_destinationResource 		= NULL;
    	$this->_manualDestinationImagePath 	= false;
    	$this->_errors				= array();
    	$this->_cacheIsActive			= TRUE;
    	$this->_heightCrop			= FALSE;
    	return $this;
    }
    
    /**
     * @return Default_Lib_Image
     */
    public function resizeIfSmaller()
    {
    	$this->_resizeIfSmaller = true;
    	return $this;
    }
    
    /**
     * говорим классу о том что нужно отрезать картинку в случае если она больше указанной высоты,
     * обычно применятеся когда хотим получить квадратную картинку
     *  
     * @return Default_Lib_Image
     */
    public function heightCrop()
    {
    	$this->_heightCrop = TRUE;
    	return $this;
    }
    
    /**
     * @return Default_Lib_Image
     */
    public function cacheTurnOff()
    {
    	$this->_cacheIsActive = false;
    	return $this;
    }
    
    /**
     * @return Default_Lib_Image
     */
    public function cacheTurnOn()
    {
    	$this->_cacheIsActive = true;
    	return $this;
    }
    
    /**
     * setting sizes
     *
     * @param string $size
     * @return Default_Lib_Image
     */
    public function setSize($size)
    {
    	if (count($this->_errors))
    		return $this;
        //@todo по идее тут надо продумать установку размера не испортив пропорции картинки
        $sizeArray = explode('x', $size);
        $width    = intval($sizeArray[0]);
        $height   = intval($sizeArray[1]);
        $this->_originalDestinationImageHeight = $height;
        if ($this->_originalImageWidth < $width && !$this->_resizeIfSmaller)
        	$width = $this->_originalImageWidth;
        	
        if ($this->_originalImageHeight < $height && !$this->_resizeIfSmaller)
        	$height = $this->_originalImageHeight;
        
        $ratio    = $this->_originalImageWidth / $width;
        $this->_destinationImageWidth =  $width;
        $this->_destinationImageHeight = intval($this->_originalImageHeight / $ratio);//меняем размеры в пропорциях
        
        /**
         * картинка никогда не будет превышать заданных размеров 
         * при этом сохраняет пропорции
         */
        if ($this->_smartRatio) {
        	if ($this->_destinationImageHeight > $height) {
	        	$ratio    = $this->_originalImageHeight / $height;
		        $this->_destinationImageWidth =  intval($this->_originalImageWidth / $ratio);
		        $this->_destinationImageHeight = $height;
        	}
        }
        return $this;
    }
    
    /**
     *
     * @param string $pos :: hor vert
     * @return default_Lib_Image
     */
    public function setPosition($pos) {
    	$this->_typePosition = $pos;
    	return $this;
    }
    
    /**
     * Склеиваем картинки
     * TODO надо будет еще добавить чтобы можно было делать ресайз и продумать че-нить с сохранением в файл
     * @param array $images
     */
	public function joinTogether($images, $destination) {
    	$totalHeight 	= 0;
    	$totalWidth		= 0;
    	$maping			= '';
    	$tempHeight		= 0;
    	$distance		= 7;
		switch ($this->_typePosition ) {
    		case 'vert':
		    	foreach ($images as $img) {
		    		$imageProperties = @getimagesize($img);
			        if (!$imageProperties)
			        	$this->_errors[] = "Can't get image properties";
		    		list($width[], $height, $type) = $imageProperties;
		    		$totalHeight += $height+$distance;
		    		$totalWidth	= max($width);
		    	}
		    	$temp  = @imagecreatetruecolor ($totalWidth,$totalHeight);
		    	
		    	if (!$temp)
        			$this->_errors[] = "Cannot Initialize new GD image stream";
		    	$offset = 0;
		    	foreach ($images as $key=>$img) { 
		    		$maping .= ',0,'.$tempHeight;
		    		$imageProperties = @getimagesize($img);
			        if (!$imageProperties)
			        	$this->_errors[] = "Can't get image properties";	
		    		list($width,$height, $type) = $imageProperties;
		    		$tempHeight += $height;  
		    		$maping .= ','. $width .','. $tempHeight;
		    		
		    		switch ($type) {
			        	case 1:
			        		$src = imagecreatefromgif($img);
			        		break;
			        	
			        	case 2:
			        		$src = @imagecreatefromjpeg($img);
			        		break;
			        		
			        	case 3:
			        		$src = imagecreatefrompng($img);
			        		break;
			        }
		    		 
		    		
		    		if(!$src)
		    			$this->_errors[] = "Cannot Initialize image stream";
		    			
		    		
		    		 		
		    		@imagecopy($temp, $src, 0, $offset, 0, 0, $totalWidth, $totalHeight);
		    		$offset += $height+$distance;
		    		$wite = imagecolorallocate($temp, 255, 255, 255);
		    		imagefilledrectangle  ( $temp  ,  0  ,  $offset-$distance  ,  $totalWidth  ,  $offset  ,  $wite  );
		    		
		    		@imagedestroy($src);
		    	}
		    	break;
    		case 'hor':
    			foreach ($images as $img) {
		    		$imageProperties = @getimagesize($img);
			        if (!$imageProperties)
			        	$this->_errors[] = "Can't get image properties";
		    		list($width, $height[], $type) = $imageProperties;
		    		$totalHeight 	= max($height);
		    		$totalWidth		+= $width;
		    	}
		    	$temp  = @imagecreatetruecolor ($totalWidth,$totalHeight);
		    	if (!$temp)
        			$this->_errors[] = "Cannot Initialize new GD image stream";
		    	$offset = 0;
		    	foreach ($images as $key=>$img) {  
		    		$src = imagecreatefromjpeg($img);
		    		list($width,$height) = getimagesize($img);    		
		    		imagecopy($temp, $src, $offset, 0, 0, 0, $totalWidth, $totalHeight);
		    		$offset += $width;
//		    		imagedestroy($src);
		    	}
		    	break;
    	}
    	
    	$this->_destinationResource = $temp;
    	$this->_originalImageHeight	= $totalHeight;
    	$this->_originalImageWidth	= $totalWidth;
    	$this->_originalImageType	= 2;
    	
    	// Output and free from memory
		@imagejpeg($temp, $destination);
		
		@imagedestroy($temp);
		//imagedestroy($src);
		return substr($maping, 1);
    }
    
    public function getErrors()
    {
    	return $this->_errors;
    }
    
    /**
     * Собственно говоря сам ресайз
     * предполагаем что setSize и setImage уже были вызваны
     *
     */
    public function resize()
    {
    	if (count($this->_errors))
    		return false;
    		
        $this->_setDestinationImage();
        if (!$this->_cacheIsActive || !file_exists($this->_destinationImagePath)) {
	            imagecopyresampled($this->getDestinationResource(), $this->getResource(), 0, 0, 0, 0, $this->_destinationImageWidth, 
	                                  $this->_destinationImageHeight, $this->_originalImageWidth, $this->_originalImageHeight);
	            $this->output();
        } 
        
        $this->reset();//сбрасываем все к хуям
        return $this->_destinationImagePath;
    }
    
    /**
     * cropping image, no cache
     *
     * @todo написана на коленке, надо будет за ней следить внимательно
     * @param int $x
     * @param int $y
     * @param width $width
     * @param height $height
     * @return string image path
     */
    public function crop($x, $y, $width, $height)
    {
    	if (count($this->_errors))
    		return false;
    		
    	$this->_destinationImageHeight 	= $height;
    	$this->_destinationImageWidth 	= $width;
    		
    	imagecopyresampled($this->getDestinationResource(), $this->getResource(), 0, 0, $x, $y, $this->_destinationImageWidth, 
	                                  $this->_destinationImageHeight, $width, $height)
	                                  ;
    	$this->output();
    	return $this->_destinationImagePath;
    }
    
    /**
     * Выводим картинку
     *
     */
    public function output()
    {
    	switch ($this->getDestinationFileType()) {
    		case 'gif':
    			imagegif($this->getDestinationResource(), $this->_destinationImagePath);
    			break;
    			
    		case 'jpg':
    			imagejpeg($this->getDestinationResource(), $this->_destinationImagePath, $this->_quality);
    			break;
    			
    		case 'png':
    			imagepng($this->getDestinationResource(), $this->_destinationImagePath)	;
    			break;
    	}
    }
    
    /**
     * установить путь к картинке с которой будем производить операции
     *
     * @param string $imagePath
     * @return Default_Lib_Image
     */
    public function setImage($imagePath)
    {
        //@todo надо будет продумать мысль с отствутсвием источника
        //if (!file_exists($imagePath))
        //	$imagePath = $this->_noimagePath;
        	
        $this->_resourceImagePath = $imagePath;
        $imageProperties = @getimagesize($this->_resourceImagePath);
        if (!$imageProperties)
        	$this->_errors[] = "Can't get image properties";
        else {
	        list($sourceImageWidth, $sourceImageHeight, $sourceImageType) = $imageProperties;
	        $this->_originalImageWidth    = $sourceImageWidth;
	        $this->_originalImageHeight   = $sourceImageHeight;
	        $this->_originalImageType     = $sourceImageType;
        }
	    return $this;
    }
    
    /**
     * Установка точки назначения
     * делаем проверку устанавливал ли пользователь точку назначения
     * если нет, то заводим кэш пути
     *
     * @return Default_Lib_Image
     */
    private function _setDestinationImage()
    {
    	if (!$this->_manualDestinationImagePath) {
	        $resourceImagePath    = basename($this->_resourceImagePath);
	        $params               = $this->_destinationImageHeight . $this->_destinationImageWidth . $this->_heightCrop;
	        $imagePath            = $this->_cacheFolder . $resourceImagePath[0] . '/' . md5($params);
	        if (!file_exists($imagePath)) {
	          mkdir($imagePath, 0777, true);
	        }
	        $this->_destinationImagePath = $imagePath . '/' . $resourceImagePath . '.' . $this->getDestinationFileType();
    	}
	    return $this;
    }
    
    /**
     * Даем пользователю возможность установить путь к новой картинке
     *
     * @param string $imagePath
     * @return Default_Lib_Image
     */
    public function setDestinationImage($imagePath)
    {
    	$this->_manualDestinationImagePath = true;
    	$this->_destinationImagePath = $imagePath . '.' . $this->getDestinationFileType();
    	return $this;
    }
    
    /**
     * Получаем расширение файла назначения по типу файла источника
     *
     * @return string
     */
    public function getDestinationFileType()
    {
        $imageType = '';
        switch ($this->_originalImageType) {
            case 1:
                $imageType = 'gif';
                break;
                
            case 2:
                $imageType = 'jpg';
                break;
                
            case 3:
                $imageType = 'png';
                break;
        }
        
        return $imageType;
    }
    
    /**
     * Получить ресурс точки назначения
     *
     * @return Default_Lib_Image
     */
    private function getDestinationResource()
    {
        if ($this->_destinationResource == NULL)
          $this->createDestinationResource();
          
        return $this->_destinationResource;
    }
    
    /**
     * Создаем ресурс точки назначения
     *
     * @return Default_Lib_Image
     */
    private function createDestinationResource()
    {
    	$heightResource = 0;
    	if ($this->_heightCrop) {
    		$heightResource = $this->_originalDestinationImageHeight;
    		if ($this->_originalDestinationImageHeight > $this->_destinationImageHeight)
    			$heightResource = $this->_destinationImageHeight;
    	} else 
    		$heightResource = $this->_destinationImageHeight;
    		
        $destinationResource = imagecreatetruecolor($this->_destinationImageWidth, $heightResource);
        imagealphablending($destinationResource, FALSE);
        imagesavealpha($destinationResource, TRUE);
        $this->_destinationResource = $destinationResource;
        return $this;
    }
    
    private function getResource()
    {
        if ($this->_resourceResource == null)
          $this->createResource();
          
        return $this->_resourceResource;
    }
    
    /**
     * Создаем ресурс картинки (источника)
     *
     * @return Default_Lib_Image
     */
    private function createResource()
    {
        //@todo продумать все расширения картинок
        /**
         * @todo я молодец?
         */
        switch ($this->_originalImageType) {
        	case 1:
        		$resource = imagecreatefromgif($this->_resourceImagePath);
        		break;
        	
        	case 2:
        		$resource = imagecreatefromjpeg($this->_resourceImagePath);
        		break;
        		
        	case 3:
        		$resource = imagecreatefrompng($this->_resourceImagePath);
        		break;
        }
        
        if (!$resource) {
        	$this->_errors[] = "Cant create resource";
        } else 
        	$this->setResource($resource);
        return $this;
    }
    
    /**
     * Установить ресурс картинки источника
     *
     * @param resource $resource
     * @return Default_Lib_Image
     */
    private function setResource($resource)
    {
        $this->_resourceResource = $resource;
        return $this;
    }
}

?>