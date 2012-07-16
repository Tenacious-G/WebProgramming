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

class Vote extends AppModel {
	var $name = 'Vote';
	var $use = array('Vote','Node');
	var $actsAs = array('Containable');
	var $recursive = -1;
       
	var $belongsTo = array('Node','User');

    /**
     * remove vote from node, we need to update rating and votes fields
     * in nodes table.
     *
     * @param $user_id int
     * @param $node_id int
     */
        
	function removeVote($user_id, $node_id) {
		return $this->deleteAll(array('Vote.user_id'=>$user_id, 'Vote.node_id'=>$node_id));
    }
}
?>