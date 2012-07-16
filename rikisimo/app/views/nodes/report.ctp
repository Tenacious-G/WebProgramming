<div>
<h1><?php printf(__('Report error in %s',true), h($node['Node']['name'])); ?></h1>

<div id="report-form">
<?php

echo $form->create('Node',array('url'=>'/nodes/report/'.$node['Node']['id']));
echo $form->label('report','What\'s wrong with this node?');
echo $form->textarea('report');
echo $form->end('submit');

?>
</div>
<?php echo $html->link(__('Back',true), array('controller'=>'nodes', 'action'=>'view', 
'all-cities', $node['Node']['slug'])); ?>
</div>