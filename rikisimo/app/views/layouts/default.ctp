<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<?php
	echo $html->css('style');
	echo $html->charset();
	echo $html->meta('icon');
	echo $javascript->link('http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js');
	echo $javascript->link('http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js?load=effects,controls');
	echo $scripts_for_layout;

	// create a link if there is a feed available for the view
	if(isset($feed)) {
?>
	<link rel="alternate" type="application/atom+xml" title="Atom feed" href="/feeds/<?php echo $feed; ?>.xml" /> 
<? 
	} 

	// if the view is a node view create meta tags automatically based on node's data
 	if(isset($nodeView)) {
?>
	<meta name="title" content="<?php echo h($node['Node']['name']); ?>" />
	<meta name="description" content="<?php echo h($text->truncate($node['Node']['notes'],150,array('ending'=>'...'))); ?>" />
<?php 
	}
	// if it is not a node view use the default settings to create meta tags
	else {
?>
	<meta name="title" content="<?php echo h(Configure::read('appSettings.appName')).' | '.h(__(Configure::read('appSettings.appSlogan'), true)); ?>" />
	<meta name="description" content="<?php echo h(__(Configure::read('appSettings.appSlogan'), true)); ?>" />
<?php } ?>
	<title><?php  echo h(Configure::read('appSettings.appName')).' | '.$title_for_layout; ?></title>
</head>
<body>	
	<!-- HEADER START -->
	<div id="header">
		<div class="content">
		    <h1 id="logo">
			<?php echo $this->Html->link(Configure::read('appSettings.appName'), Router::url('/', true)); ?>
			</h1>
			<?php echo $this->Element('menu'); ?>
			<div style="clear:both;"></div>
		</div>
	</div>
	<!-- HEADER END -->

	<!-- MAIN START -->
	<div id="main" class="content">
	<?php
		if ($session->check('Message.flash')):
			echo "<div id=\"flash-container\">";
				echo $session->flash();
			echo "</div>";
						
			echo $javascript->codeBlock('
				$("flashMessage").fade({delay: 3, duration: 3.0});
			');					
		endif;

		echo $content_for_layout;
	?>
	</div>
	<!-- MAIN END -->

	<!-- FOOTER START -->
	<div id="footer">
		<div class="content">
		<?php 
			echo $html->link(sprintf(__('About %s',true), Configure::read('appSettings.appName')),
				array('controller'=>'pages', 'action'=>'display', 'about'));
			echo $html->link(__('Terms of service',true),
				array('controller'=>'pages', 'action'=>'display', 'terms'), array('rel'=>'nofollow'));
			echo $html->link(__('Badges', true), array('controller'=>'pages', 'action'=>'display', 'badges'));
		?>
		</div>
	</div>
	<!-- FOOTER END -->
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>