<div id="admin-menu">
<ul>
<?php
echo '<li>'.$html->link(__('Settings',true), array('controller'=>'settings', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Listings', true), array('controller'=>'nodes', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Photos',true), array('controller'=>'photos', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Reviews',true), array('controller'=>'comments', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Users',true), array('controller'=>'users', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Categories',true), array('controller'=>'categories', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Prices',true), array('controller'=>'prices', 'action'=>'index')).'</li>';
echo '<li>'.$html->link(__('Close admin control panel',true),
			array('controller'=>'nodes', 'action'=>'index', 'admin'=>false)).'</li>';

?>
</ul>
</div>