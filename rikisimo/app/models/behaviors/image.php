<?php
/**
 * Copyright 2008 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class ImageBehavior extends ModelBehavior {

	var $_name;
	var $__uniqueName = null;

	var $errors = array();
	var $__Model;
	var $__destinations = array();
	var $__deletedPhotos = array();

	var $__settings = array(
						'filedata' =>'filedata',
						'fileField' => 'filename',
						'titleField' => 'title',
						'defaultFile' => null,
						'filename' => null,
						'plugin' => null,
						'allowed' => array('jpg','jpeg','gif','png')
						);

	var $__rules = array(
						'default' => array(
										'destination' => 'photos',
										'type' => 'resizecrop', 
										'size' => array('width'=>'800', 'height' => '600'), 
										'quality' => 100,
										'output' => 'jpg')
										);
			                              
    var $__default_validation = array(
/*
		  'fileNotEmpty' => array (
		    'rule' => array('fileNotEmpty'),
		    'message' => 'No file upload'
		  ),
*/
		  'validType' => array (
		    'rule' => array('validType'),
		    'message' => 'File type is not valid'
		  ),
		  'uploadError' => array(
		    'rule' => array('uploadError'),
		    'message' => 'Error uploading file',
		  ));
		
    /**
     * behavior setup
     */

	function setup(&$Model, $settings = null) {
		$this->__Model = &$Model;

		if (!function_exists("imagecreatefromjpeg")) {
			trigger_error("GD library not installed");
		}
							
		if(is_array($settings)) {
			if(isset($settings['settings']) and is_array($settings['settings'])) {
				$this->__settings = array_merge($this->__settings,$settings['settings']);
			}
			if(!isset($settings['photos']) or !is_array($settings['photos'])) {
				$this->__settings['photos'] = $this->__rules;
			}
			else {
				$this->__settings['photos'] = $settings['photos'];
			}
		}        

		if(!$Model->hasField($this->__settings['fileField'])) {
			trigger_error('ImageBehavior Error: The field "'.$this->__settings['fileField'].'" doesn\'t exists in the model "'.$Model->name.'".', E_USER_WARNING);
		}

		if(!isset($this->__Model->validate['filedata'])) {
			$this->__Model->validate['filedata'] = array();
		}
      
      $this->__Model->validate['filedata'] =
		array_merge($this->__default_validation,$this->__Model->validate['filedata']);
	}

    /** 
     * If filedata field is not empty upload the file using the settings in 
	 * $this->__settings['photos']. If it is empty set the fileField to the default.
     */

	function beforeSave(&$Model) {
		$return = parent::beforeSave($Model);
		if(isset($Model->data[$Model->alias][$this->__settings['filedata']])) { 
			if(!empty($Model->data[$Model->alias][$Model->primaryKey])){
				array_push($this->__deletedPhotos, $this->__getCurrentPhoto($Model->id));
			}
			if(!empty($Model->data[$Model->alias][$this->__settings['filedata']])) {
				foreach($this->__settings['photos'] as $settings):
					$result = $this->__upload(array_merge($this->__rules['default'],$settings));                          
				endforeach;
			}
			else {
				$this->__Model->data[$this->__Model->alias][$this->__settings['fileField']] =
					$this->__settings['defaultFile'];        
			}
		}
		return $return;
	}

    /**
     * Upload the file and proccess it using the settings
	 *
	 * @access private
	 * @param $settings mixed
     **/

	function __upload($settings) {
		$file = $this->__Model->data[$this->__Model->alias][$this->__settings['filedata']];
		$destination = WWW_ROOT.'img/'.$settings['destination'];

		if (substr($destination,-1) != '/') {
			$destination .= '/';
		}

		if(!$this->__uniqueName) {
			$this->__uniqueName = $this->__uniqueName($this->__settings['filename']);
		}

		$this->__Model->data[$this->__Model->alias][$this->__settings['fileField']] =
			$this->__uniqueName;
		$this->_name = $destination.$this->__uniqueName;
					
		$this->__image($file, $settings['type'], $settings['size'], $settings['output'],
			$settings['quality']);
	}

	/** 
	 * set image size and save it to destination
	 *
	 * @access private
	 * @param $file mixed
	 * @param $type string
	 * @param $size int
	 * @param $output string
	 * @param $quality int
	 */
     
		function __image($file, $type = 'resize', $size = 100, $output = 'jpg', $quality = 75) {
			$type = strtolower($type);
			$output = strtolower($output);

			if (is_array($size)) {
				$maxW = intval($size['width']);
				$maxH = intval($size['height']);
			} 
			else {
				$maxScale = intval($size);
			}

			// -- check sizes
			if (isset($maxScale)) {
				if (!$maxScale) {
					$this->error("Max scale must be set");
				}
			} else {
				if (!$maxW || !$maxH) {
					$this->error("Size width and height must be set");
					return;
				}
				if ($type == 'resize') {
					$this->error("Provide only one number for size");
				}
			}

			if (is_numeric($quality)) {
				$quality = intval($quality);
				if ($quality > 100 || $quality < 1) {
					$quality = 75;
				}
			} else {
				$quality = 75;
			}
			
			$this->setMemoryForImage($file['tmp_name']);
		
			// -- get some information about the file
			$uploadSize = getimagesize($file['tmp_name']);
			$uploadWidth  = $uploadSize[0];
			$uploadHeight = $uploadSize[1];
			$uploadType = $uploadSize[2];

			if ($uploadType != 1 && $uploadType != 2 && $uploadType != 3) {
				$this->error("File type must be GIF, PNG, or JPG to resize");
			}
			
			switch ($uploadType) {
				case 1: $srcImg = imagecreatefromgif($file['tmp_name']); break;
				case 2: $srcImg = imagecreatefromjpeg($file['tmp_name']); break;
				case 3: $srcImg = imagecreatefrompng($file['tmp_name']); break;
				default: $this->error ("File type must be GIF, PNG, or JPG to resize");
			}
						
			switch ($type) {
				case 'resize':
					# Maintains the aspect ration of the image and makes sure that it fits
					# within the maxW and maxH (thus some side will be smaller)
					// -- determine new size
					if ($uploadWidth > $maxScale || $uploadHeight > $maxScale) {
						if ($uploadWidth > $uploadHeight) {
							$newX = $maxScale;
							$newY = ($uploadHeight*$newX)/$uploadWidth;
						} else if ($uploadWidth < $uploadHeight) {
							$newY = $maxScale;
							$newX = ($newY*$uploadWidth)/$uploadHeight;
						} else if ($uploadWidth == $uploadHeight) {
							$newX = $newY = $maxScale;
						}
					} else {
						$newX = $uploadWidth;
						$newY = $uploadHeight;
					}
					
					$dstImg = imagecreatetruecolor($newX, $newY);
					imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
					
					break;
					
				case 'resizemin':
					# Maintains aspect ratio but resizes the image so that once
					# one side meets its maxW or maxH condition, it stays at that size
					# (thus one side will be larger)
					#get ratios
					$ratioX = $maxW / $uploadWidth;
					$ratioY = $maxH / $uploadHeight;

					#figure out new dimensions
					if (($uploadWidth == $maxW) && ($uploadHeight == $maxH)) {
						$newX = $uploadWidth;
						$newY = $uploadHeight;
					} else if (($ratioX * $uploadHeight) > $maxH) {
						$newX = $maxW;
						$newY = ceil($ratioX * $uploadHeight);
					} else {
						$newX = ceil($ratioY * $uploadWidth);		
						$newY = $maxH;
					}

					$dstImg = imagecreatetruecolor($newX,$newY);
					imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
				
					break;
				
				case 'resizecrop':
					// -- resize to max, then crop to center
					$ratioX = $maxW / $uploadWidth;
					$ratioY = $maxH / $uploadHeight;

					if ($ratioX < $ratioY) { 
						$newX = round(($uploadWidth - ($maxW / $ratioY))/2);
						$newY = 0;
						$uploadWidth = round($maxW / $ratioY);
						$uploadHeight = $uploadHeight;
					} else { 
						$newX = 0;
						$newY = round(($uploadHeight - ($maxH / $ratioX))/2);
						$uploadWidth = $uploadWidth;
						$uploadHeight = round($maxH / $ratioX);
					}
					
					$dstImg = imagecreatetruecolor($maxW, $maxH);
					imagecopyresampled($dstImg, $srcImg, 0, 0, $newX, $newY, $maxW, $maxH, $uploadWidth, $uploadHeight);
					break;
				
				case 'crop':
					// -- a straight centered crop
					$startY = ($uploadHeight - $maxH)/2;
					$startX = ($uploadWidth - $maxW)/2;

					$dstImg = imageCreateTrueColor($maxW, $maxH);
					ImageCopyResampled($dstImg, $srcImg, 0, 0, $startX, $startY, $maxW, $maxH, $maxW, $maxH);
				
					break;
				}	

			$write = false;
			// -- try to write
			switch ($output) {
				case 'jpg':
					$write = @imagejpeg($dstImg, $this->_name, $quality);
					break;
				case 'png':
					$write = @imagepng($dstImg, $this->_name . ".png", $quality);
					break;
				case 'gif':
					$write = @imagegif($dstImg, $this->_name . ".gif", $quality);
					break;
			}

			// -- clean up
			imagedestroy($dstImg);
			
			if ($write) {
				$this->result = basename($this->_name);
			}
			else {
			  $this->error("Error saving file");
			}
		}
	
	// return the extension of a file	
	function __ext($file) {
		$ext = trim(substr($file,strrpos($file,".")+1,strlen($file)));
		return $ext;
	}
		
	// add a message to stack (for outside checking)
	function error($message) {
		array_push($this->errors, $message);
	}
	  
	/**
	 * returns unique name for the file looking into the provided destination directory
	 *
	 * @access private
	 * @param $file string
	 */

	function __uniqueName($name) {
		if(!$name) {
			$name =
				$this->__Model->data[$this->__Model->alias][$this->__settings['filedata']]['name'];
		}

		$parts = pathinfo($name);
		$ext = $parts['extension'];
		$name = $parts['filename'];
		$exists = true;
		$n = 1;

		if($this->__settings['filename']==null and
			isset($this->__Model->data[$this->__Model->alias][$this->__settings['titleField']])) {
				$name = strtolower(Inflector::slug($this->__Model->data[$this->__Model->alias][$this->__settings['titleField']],'-'));
		}
		while($exists) {
			foreach($this->__getDestinations() as $destination):
				$full_name = $name.'-'.$n.'.'.$ext;
				$full_path = WWW_ROOT.'img/'.$destination.'/'.$full_name;
				$exists = file_exists($full_path);
			endforeach;
			$n++;
		}
		return $full_name;
	}
		
	/**
	 * file can't be empty
	 *
	 * @param $data mixed 
	 */

	function fileNotEmpty($data) {
		return (!empty($this->__Model->data[$this->__Model->alias]['filedata']));
	}

	/**
	 * check valid file type
	 * 
	 * @param $data mixed
	 */

	function validType($data) {
		if(isset($data->data[$this->__Model->alias]['filedata']['name'])):
			return (in_array($this->__ext(strtolower($data->data[$this->__Model->alias]['filedata']['name'])),$this->__settings['allowed']));
		endif;
		return true;
	}
	
	/**
	 * check upload error
	 *
	 * @param $data mixed
	 */

	function uploadError($data) {
		if(isset($data->data[$this->__Model->alias]['filedata']['name'])):
			return !($data->data[$this->__Model->alias]['filedata']['error']);
		endif;
		return true;
	}

	/**
	 * delete photos in filesystem before deleting the model
	 */

	function beforeDelete(&$Model) {
		$Model->read(null,$Model->id);
		$photo = $Model->data;
		if($photo[$this->__Model->alias][$this->__settings['fileField']] !=
			$this->__settings['defaultFile']):
				array_push($this->__deletedPhotos,
					$photo[$this->__Model->alias][$this->__settings['fileField']]);
			$this->__purgePhotos();		
		endif;
		return true;
	}

	/**
	 * get the directory names where the photos and thumbnails are being saved
	 *
	 * @access private
	 */

	function __getDestinations() {
		if(!empty($this->__destinations)) return $this->__destinations;
		$behavior_name = "Image";
		if($this->__settings['plugin']) {
			$behavior_name = $this->__settings['plugin'].".Image";
		}

		if(isset($this->__Model->actsAs[$behavior_name]['photos']) and
			is_array($this->__Model->actsAs[$behavior_name]['photos'])) {
				foreach($this->__Model->actsAs[$behavior_name]['photos'] as $photo) {
					$destination = isset($photo['destination'])? $photo['destination'] : $this->__rules['default']['destination'];
					array_push($this->__destinations,$destination);
				}
		}
		else {
			array_push($this->__destinations,$this->__rules['default']['destination']);
		}
		return $this->__destinations;
	}

	/**
	 * get the photo for the current model
	 * 
	 * @access private
	 * @param $id int
	 **/
	
	function __getCurrentPhoto($id) {
		$this->__Model->recursive = -1;
		$photo_model = $this->__Model->find(array('id'=>$id),array($this->__settings['fileField']));
		return $photo_model[$this->__Model->alias][$this->__settings['fileField']];   
	}
    
	/**
	 * Delete all photos in  $this->__deletePhotos array
	 */

	function __purgePhotos() {
		foreach($this->__deletedPhotos as $photo):
			if($photo != $this->__settings['defaultFile']):
				foreach($this->__getDestinations() as $destination):
					if(is_file(WWW_ROOT.'img/'.$destination.'/'.$photo)) {
						unlink(WWW_ROOT.'img/'.$destination.'/'.$photo);
					}
				endforeach; 
			endif;
		endforeach;
	}
  
	/**
	 * Delete photos after saving
	 */

	function afterSave(&$Model)  {
		$this->__purgePhotos();
	}
	
	/**
	 * set memory
	 */

	function setMemoryForImage( $filename ){
	    $imageInfo = getimagesize($filename);
	    $MB = 1048576;  // number of bytes in 1M
	    $K64 = 65536;    // number of bytes in 64K
	    $TWEAKFACTOR = 3;  // Or whatever works for you
		$channels = (isset($imageInfo['channels']))? $imageInfo['channels'] : 4;
		$bits = (isset($imageInfo['bits']))? $imageInfo['bits'] : 8;		
	    $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
	                                           * $bits
	                                           * $channels / 8
	                             + $K64
	                           ) * $TWEAKFACTOR
	                         );
	    //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
	    //Default memory limit is 8MB so well stick with that. 
	    //To find out what yours is, view your php.ini file.

		$memoryLimitMB = 8;	
		$memoryLimit = $memoryLimitMB * $MB;
	    
	if (function_exists('memory_get_usage') && 
	        memory_get_usage() + $memoryNeeded > $memoryLimit) 
	    {
	        $newLimit = $memoryLimitMB + ceil( ( memory_get_usage()
	                                            + $memoryNeeded
	                                            - $memoryLimit
	                                            ) / $MB
	                                        );
	        ini_set( 'memory_limit', $newLimit . 'M' );
	        return true;
	    }else
	        return false;
	    }
}
?>