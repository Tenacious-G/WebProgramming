<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php __('An error occurred'); ?></title>
	<?php
		echo $html->charset();
		echo $html->meta('icon');
		echo $html->css('error');		
	?>
</head>
<body>
	<!-- CONTAINER START -->
	<div id="container">

		<!-- MAIN START -->
		<div id="main">
			<?php
				// content is inserted here
				echo $content_for_layout;
			?>
		</div>
		<!-- MAIN END -->
		
	</div>
	<!-- CONTAINER END -->
</body>
</html>