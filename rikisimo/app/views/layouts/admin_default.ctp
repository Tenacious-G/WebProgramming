<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php

		echo $html->css('admin');
				
		echo $html->charset();
		echo $html->meta('icon');

		echo $javascript->link('http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js');
		echo $javascript->link('http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js?load=effects,controls');
		echo $scripts_for_layout;
	?>
		
	<title>
		<?php echo $title_for_layout;?>
	</title>

</head>
<body>
	<!-- CONTAINER START -->
	<div id="container">

		<!-- HEADER START -->
		<h1 id="header"><?php __('Admin control panel'); ?></h1>

		<!-- HEADER END -->
		
		<!-- CONTENT START -->
		<div id="content_home">
		<?php
			echo $this->Element('admin_menu');
			if ($session->check('Message.flash')):
				$session->flash();
			endif;		
			echo $content_for_layout;
		?>
		<div class="clear"></div>
		</div>
		<!-- CONTENT END -->
	</div>
	<!-- CONTAINER END -->
	
	<?php echo $this->element('sql_dump'); ?>

</body>
</html>