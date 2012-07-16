<?php 
	$this->pageTitle = __('Page not found', true); 
?>
<h1>Error 404</h1>
<p>Page not found.</p>
<p style="margin-top: 15px">
	<?php 
		echo $html->link(__('Go to the home page', true), Router::url('/', true)); 
	?>
</p>