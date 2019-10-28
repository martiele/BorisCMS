<?php 
/*
By Joyce Johnston
http://www.uncontentio.us
April 18, 2009
*/
class Photo {
    
    private $tmp_name;
    public $name;
    private $error;
    private $max_size = 500000;
    private $accepted_mime_types = array('image/jpeg','image/png','image/gif');
    private $tmp;
    private $src;
    private $directory;
    private $src_x;
    private $src_y;
    private $dst_x;
    private $dst_y;
    private $orig_width;
    private $orig_height;
    private $src_width;
    private $src_height;
    private $dst_width;
    private $dst_height;
    
    function __construct($file_info, $max_size='') {
        
        $this->tmp_name = $file_info['tmp_name'];
        $this->name = $file_info['name'];
        $this->size = $file_info['size'];
        $this->error = $file_info['error'];
        $info = getimagesize($this->tmp_name);
        $this->mime = $info['mime'];
        $this->orig_width = $info[0];
        $this->orig_height = $info[1];
         if($max_size != '') { $this->max_size = $max_size; }
        
    }
    
    function validate() {
       
      $errors = array();
       if(!isset($error) || $this->error == 0 ) {
           if (!in_array($this->mime,$this->accepted_mime_types)) {
               $errors[] = 'Hold it! You may upload files of type .jpg, .jpeg, .gif, or .png. What you tried to upload was something else.'; 
            }
            if($photo->size > $photo->max_size) {
               $errors[] = 'Whoa! Your photo is too big.  File size cannot exceed '.($this->max_size/1000).'KB.'; 
            }
       }
       else {
          $errors[] = "There was a problem uploading your photo.  
          It may be that your photo is too big or that 
          something bad happened during upload. If your photo is less 
          than ".($this->max_size/1000)." in size, please try again.";
       }
        return $errors;
    }
    
    function getFileType() {
        return $this->mime;
    }
    
    function getWidth() {
        return $this->orig_width; 
    }
    
    function getHeight() {
       return $this->orig_height; 
    }
    
    function getDimensionRatio() {
       return $this->orig_width/$this->orig_height; 
    }
    
     public function doResize($larg,$alte,$directory,$fit=0) {
        
        $errors = array();
        $this->setDimensions($larg,$alte,$fit);
        $success = $this->resize($directory);
        if(!$success) { $errors[] = 'Sorry! I couldn\'t resize your photo.  Please try again later.'; }
        return $errors;    
    }
    
    public function doThumb($thumb_size,$directory) {
        
       $errors = array();
        $this->setThumbDimensions($thumb_size);
        $success = $this->resize($directory);
        if(!$success) { $errors[] =  'Sorry! I couldn\'t make a thumbnail for your image.  Please try again later.'; }
        return $errors;
    }
    
    public function doCenterCrop($x,$y,$directory='') {
        
        $errors = array();
        $success = $this->setCenterCropDimensions($x,$y);
        $success = $success && $this->resize($directory);
        if(!$success) { $errors[] =  'Sorry! I couldn\'t crop your image.  Check your image dimensions.
        If it is less than '.$x.'px by '.$y.'px, it is too small to crop.'; }
        return $errors;
    }
    
    public function doFullCrop($x,$y,$width,$height,$directory='') {
        
        $errors = array();
        $success = $this->setFullCropDimensions($x,$y,$width,$height);
        $success = $success && $this->resize($directory);
        if(!$success) { $errors[] =  'Sorry! I couldn\'t crop your image.  Check your image dimensions.
        If it is less than '.$x.'px by '.$y.'px, it is too small to crop.'; }
        return $errors;
    }
    
    
    public function move($directory) {
       $errors = array();
       $success = @move_uploaded_file($this->tmp_name, $directory.$this->name); 
       if(!$success) { $errors[] = 'Sorry! I couldn\'t store your image.  Please try again later.'; }
       return $errors;
    }
	
    public function copia($directory) {
       $errors = array();
       $success = @copy($this->tmp_name, $directory.$this->name); 	   
       if(!$success) { $errors[] = 'Sorry! I couldn\'t store your image.  Please try again later.'; }
       return $errors;
    }

    
    private function setDimensions($larg,$alte,$fit) {    
		$newH = ($this->orig_height/$this->orig_width)*$larg;
		if($fit){
			//l'immagine deve riempire lo spazio e sforarlo	
			if($newH>$alte){
				$this->dst_width = $larg;
          		$this->dst_height = $newH;
			}else{
				$newW = ($this->orig_width*$alte)/$this->orig_height;
				$this->dst_width = $newW;
          		$this->dst_height = $alte;
			}
		}else{
			//l'immagine deve essere contenuta nel box. Quindi inferiore ad esso.	
			if($newH<$alte){
				$this->dst_width = $larg;
          		$this->dst_height = $newH;				
			}else{
				$newW = ($this->orig_width*$alte)/$this->orig_height;
				$this->dst_width = $newW;
          		$this->dst_height = $alte;				
			}			
		}
        $this->src_width = $this->orig_width;
        $this->src_height = $this->orig_height;
        $this->src_x = 0;
        $this->src_y = 0;
        $this->dst_x = 0;
        $this->dst_y = 0;       
    }
    
    private function setThumbDimensions($thumb_size) {
        
        //find the larger dimension, height or width
        if($this->getDimensionRatio() > 1) {
           //our src image is wider than it is tall 
           $this->src_x = 0 + (($this->orig_width-$this->orig_height)/2);
           $this->src_y = 0;
           $this->src_width = $this->orig_height;
           $this->src_height = $this->orig_height;
           
        }
        else {
            $this->src_x = 0;
            $this->src_y = 0 + (($this->orig_height-$this->orig_width)/2);
            $this->src_height = $this->orig_width;
            $this->src_width = $this->orig_width;
        }
        $this->dst_width = $thumb_size;
        $this->dst_height = $thumb_size;
        $this->dst_x = 0;
        $this->dst_y = 0;
        
    }
    
    private function setCenterCropDimensions($x,$y) {
        
        //if the image is smaller than crop size
        if($this->orig_width < $x || $this->orig_height < $y) {
           return false;
           exit;
        }
        //we're always going to crop from center
        $this->src_x = 0 + (($this->orig_width-$x)/2);
        $this->src_y = 0 + (($this->orig_height-$y)/2);
        $this->dst_x = 0;
        $this->dst_y = 0;
        $this->dst_width = $x;
        $this->dst_height = $y;
        $this->src_width = $x;
        $this->src_height = $y;
        return true;
    }
    
    private function setFullCropDimensions($x,$y,$width,$height) {
        
        $this->src_x = $x;
        $this->src_y = $y;
        $this->dst_x = 0;
        $this->dst_y = 0;
        $this->dst_width = $width;
        $this->dst_height = $height;
        $this->src_width = $width;
        $this->src_height = $height;
        return true;
    }
    
    private function resize($directory) {
        
        // create an Image to resize
         //$this->src = imagecreatefromjpeg($this->tmp_name);
         $this->createImage();
        
         // get all of the sizes
        $width=$this->src_width;
        $height=$this->src_height;
        $newheight=$this->dst_height;
        $newwidth=$this->dst_width;
        $src_x = $this->src_x;
        $src_y = $this->src_y;
        $this->tmp= @imagecreatetruecolor($newwidth,$newheight);

        // resize image
        
        $success = @imagecopyresampled($this->tmp,$this->src,0,0,$src_x,$src_y,$newwidth,$newheight,$width,$height);
        
        // write the resized image to disk. 
        $filename = $directory.$this->name;
        $success =   @imagejpeg($this->tmp,$filename);
		// clean up
        imagedestroy($this->src);
        imagedestroy($this->tmp); 
        return $success;
    }
    
    
    private function createImage() {
        
        switch ($this->mime) {
           case 'image/jpeg':
                $this->src = @imagecreatefromjpeg($this->tmp_name);
                break;
            case 'image/gif':
                $this->src =  @imagecreatefromgif($this->tmp_name);
                break;
            case 'image/png':
                 $this->src = @imagecreatefrompng($this->tmp_name);
                break;
        }
          
    }
    
    
}
?>
