<?php
		$error = false;
		
		if(!$config_is_writable) {
			echo '<p class="error">app/config must be writable</p>';
			$error = true;
		}

		if(!$tmp_is_writable) {
			echo '<p class="error">app/tmp and its subfolders must be writable.</p>';
			$error = true;
		}

		if(!$user_images_is_writable) {
			echo '<p class="error">app/webroot/img/users and its subfolders must be writable.</p>';
			$error = true;
		}
		
		if(!$event_images_is_writable) {
			echo '<p class="error">app/webroot/img/nodes and its subfolders must be writable.</p>';
			$error = true;
		}
		
		if($error) {
			echo '<p class="error">Please check your permissions!</p>';
		}
		else {
	        echo $form->create('Install', 
				array('url' => array('plugin' => 'install', 'controller' => 'install', 
				'action' => 'index')));
	        echo $form->input('Install.host', array('label' => 'Host name'));
	        echo $form->input('Install.database', array('label' => 'Database name'));
	        echo $form->input('Install.login', array('label' => 'Database user name'));
	        echo $form->input('Install.password', array('label' => 'Database user password'));

	        echo $form->end('Submit');			
		}
    ?>