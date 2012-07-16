<?php
/**
 * Copyright 2008 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Comment extends AppModel {

	var $name = 'Comment';
	var $useTable = 'comments';
	var $actsAs = array('Containable');
	var $recursive = -1;
	var $validate = array(
		'comment' => array(
			'rule' => array('minLength', 2),
			'message' => 'Review must be at least 2 characters long',
			));

	var $belongsTo = array(
		'Node'=>array('className'=>'Node'),
		'User'=>array('className'=>'User')
	);     
	
	var $hasMany = array('Commentvote'=>array('className'=>'Commentvote', 'dependent'=>true));
}
?>