<?php
if($paginator->counter(array('format'=>"%pages%")) > 1) {
	echo '<div id="paginator">';
	echo $paginator->prev('« '.__('Previous',true).' ');
	echo $paginator->numbers();
	echo $paginator->next(' '.__('Next',true).' »');
	echo '</div>';
}
?>