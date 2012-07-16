<?php
/**
 * Copyright 2010 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Photo extends AppModel {
	var $name = 'Photo';
	var $use = array('Photo','Node');
	var $actsAs = array('Containable', 'Image' => array(
		'settings' => array(
			'titleField' => 'title',
			'fileField' => 'file'),
		'photos' => array(
			'big' => array(
				'destination' => 'nodes',
				'size' => 600,
				'type' => 'resize'), 				
			'small' => array(
				'destination' => 'nodes/small',
				'size' => array('width' => 50, 'height' => 50))
		)));         

	var $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'message' => 'Please write a title',
			));
		
	/**
	 * associations
	 */

	var $belongsTo = array('Node','User');
} 
?>