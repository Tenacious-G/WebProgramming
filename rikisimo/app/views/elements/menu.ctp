<div id="menu">
<?php
		echo $html->link(__('Listings', true), array('controller'=>'nodes', 'action'=>'index'));
		echo $html->link(__('Search', true), array('controller'=>'searches', 'action'=>'index'));
	  	echo $html->link(__('Users', true), array('controller'=>'users', 'action'=>'index'));			
		
		if($this->Session->read('Auth.User.id')) {	
			echo $html->link(__('Messages',true).'('.$messages_number.')', array('controller'=>'messages',
				'action'=>'inbox'));
		  	echo $html->link(__('My Profile',true), array('controller'=>'users', 'action'=>'view'));
			if($this->Session->read('Auth.User.admin')) {
		  		echo $html->link(__('Admin',true), array('controller'=>'nodes', 'action'=>'index', 'admin'=>true));
			}		
		  	echo $html->link(__('Logout',true), array('controller'=>'users', 'action'=>'logout'));	
		}
		else {
		  echo $html->link(__('Login',true), array('controller'=>'users', 'action'=>'login'),
			array('rel'=>'nofollow'));
		  echo $html->link(__('Register',true), array('controller'=>'users', 'action'=>'register'),
			array('rel'=>'nofollow'));
		}
?>
</div>