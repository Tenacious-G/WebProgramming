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

class NodeListHelper extends Helper {

	var $helpers = array('Html','RFRating', 'Time', 'Text');
  
	function getNodeList($nodes = null, $user = false, $top = false) {
	    if(empty($nodes) or $nodes == null) {
			printf(__('There are no listings', true));
		}
	    $ratingInfo['units'] = 5;
	    $ratingInfo['unit_width'] = 25;
	    $ratingInfo['voted'] = true;
	    if($user)  $ratingInfo['votes'] = 1;
    
	    foreach($nodes as $node) {
	    	$ratingInfo['id'] = $node['Node']['id'];
	      	if($user) {
	      		$ratingInfo['rating'] = $node['userVote'][0]['vote'];      
	      	}
	      	else{ 
	        	if(empty($node['Vote'])) {
	          		$ratingInfo['rating'] = 0;
	          		$ratingInfo['votes'] = 0;
	        	}
	        	else {
	          		$ratingInfo['rating'] = $node['Vote'][0]['Vote'][0]['rating'];
	          		$ratingInfo['votes'] = $node['Vote'][0]['Vote'][0]['votes'];
	        	}
	      	}
	      	$ratingInfo['rating_value'] = @number_format($ratingInfo['rating']/$ratingInfo['votes'], 2); 

	    	echo "<div class=\"crestaurant_row\">";
			echo "<h2>";
			echo $this->Html->link($node['Node']['name'],
				array('controller'=>'nodes', 'action'=>'view', $node['City']['slug'], $node['Node']['slug']));
	    	echo '</h2>';
		echo "<div class=\"fstar\">";
		echo $this->RFRating->ratingBar($ratingInfo, false, $user, $top);
		echo '</div>	<div class="clear"></div>';
		
	    	echo "<div class=\"general_info\">";
			if(!$node['User']['id']) $node['User']['photo'] = 'user_photo.jpg';
			echo $this->Html->image('users/'.$node['User']['photo'], 
				array('alt'=>h($node['User']['username']))).' ';
			echo __('Added by',true).' ';
			if($node['User']['username']) {
				echo $this->Html->link($node['User']['username'],
					array('controller'=>'users', 'action'=>'view', $node['User']['slug']));
			}
			else {
				__('Anonymous');
			}
			echo ' '.$this->Time->timeAgoInWords($node['Node']['created']);
			echo ' '.__('in',true).' '.$this->Html->link($node['City']['name'],
				array('controller'=>'nodes', 'action'=>'index', $node['City']['slug']));
			echo ".";
	    	echo "</div>";
	    
			echo "<div class=\"notes\">";
			echo h($this->Text->truncate($node['Node']['notes'], 200, array('ending'=>'...')));
			echo "</div>";
			
			if(!empty($node['Photo'])):
				echo "<div>";
				foreach($node['Photo'] as $p):
					echo $this->Html->link($this->Html->image('nodes/small/'.$p['file'], 
						array('alt'=>h($p['title']))), array('controller'=>'photos', 'action'=>'view',
						$node['Node']['slug'], $p['id']), array('escape'=>false)) .' ';
				endforeach;
				echo "</div>";
			endif;
					
	  		echo "</div>";
	  	}
	}
}
?>