<?php
	$this->set('subtitle', __('Badges', true));
?>
<div id="pages">
<h1 class="generic_box"><?php __('Badges'); ?></h1>
<div id="badges-list">
<a name="reviews"></a>	<h2><?php __('Reviews'); ?></h2>
<div>
<?php echo $html->image('20comments.png'); ?>
<p><?php echo sprintf(__('You get this badge when you submit %d or more reviews.', true), 20)?></p>
</div>
<div>
<?php echo $html->image('50comments.png'); ?>
<p><?php echo sprintf(__('You get this badge when you submit %d or more reviews.', true), 50)?></p>
</div>
<div>
<?php echo $html->image('100comments.png'); ?>
<p><?php echo sprintf(__('You get this badge when you submit %d or more reviews.', true), 100)?></p>
</div>
<div>
<?php echo $html->image('200comments.png'); ?>
<p><?php echo sprintf(__('You get this badge when you submit %d or more reviews.', true), 200)?></p>
</div>

<div class="clear"></div>
<a name="likes"></a><h2><?php __('Review score'); ?></h2>
<div>
<?php echo $html->image('20likes.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have %d peole or more to like your reviews.', true), 20)?></p>
</div>
<div>
<?php echo $html->image('50likes.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have %d peole or more to like your reviews.', true), 50)?></p></div>
<div>
<?php echo $html->image('100likes.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have %d peole or more to like your reviews.', true), 100)?></p></div>
<div>
<?php echo $html->image('200likes.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have %d peole or more to like your reviews.', true), 200)?></p></div>

<div class="clear"></div>
<a name="ratings"></a><h2><?php __('Votes'); ?></h2>
<div>
<?php echo $html->image('20ratings.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have rated %d or more listings.', true), 20); ?></p>
</div>
<div>
<?php echo $html->image('50ratings.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have rated %d or more listings.', true), 50); ?></p></div>
<div>
<?php echo $html->image('100ratings.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have rated %d or more listings.', true), 100); ?></p></div>
<div>
<?php echo $html->image('200ratings.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have rated %d or more listings.', true), 200); ?></p></div>

<div class="clear"></div>
<a name="adds"></a><h2><?php echo __('Listings', true); ?></h2>
<div>
<?php echo $html->image('20adds.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have added %d or more listings.', true), 20); ?></p>
</div>
<div>
<?php echo $html->image('50adds.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have added %d or more listings.', true), 50); ?></p></div>
<div>
<?php echo $html->image('100adds.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have added %d or more listings.', true), 100); ?></p></div>
<div>
<?php echo $html->image('200adds.png'); ?>
<p><?php echo sprintf(__('You get this badge when you have added %d or more listings.', true), 200); ?></p></div>
</div>
</div>