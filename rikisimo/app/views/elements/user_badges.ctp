<?php 
$comments_badge = null;
$ratings_badge = null;
$adds_badge = null;
$likes_badge = null;

if($user['totalComments'] >= 200) $comments_badge = '200comments.png';
elseif($user['totalComments'] >= 100) $comments_badge = '100comments.png';
elseif($user['totalComments'] >= 50) $comments_badge = '50comments.png';
elseif($user['totalComments'] >= 20) $comments_badge = '20comments.png';

if($user['totalRatings'] >= 200) $ratings_badge = '200ratings.png';
elseif($user['totalRatings'] >= 100) $ratings_badge = '100ratings.png';
elseif($user['totalRatings'] >= 50) $ratings_badge = '50ratings.png';
elseif($user['totalRatings'] >= 20) $ratings_badge = '20ratings.png';

if($user['totalAdds'] >= 200) $adds_badge = '200adds.png';
elseif($user['totalAdds'] >= 100) $adds_badge = '100adds.png';
elseif($user['totalAdds'] >= 50) $adds_badge = '50adds.png';
elseif($user['totalAdds'] >= 20) $adds_badge = '20adds.png';

if($user['totalCommentvotes'] >= 200) $likes_badge = '200likes.png';
elseif($user['totalCommentvotes'] >= 100) $likes_badge = '100likes.png';
elseif($user['totalCommentvotes'] >= 50) $likes_badge = '50likes.png';
elseif($user['totalCommentvotes'] >= 20) $likes_badge = '20likes.png';

		
	if($comments_badge) echo $html->link($html->image($comments_badge, array('alt'=>__('Comments', true))),
		array('controller'=>'pages', 'action'=>'display', 'badges#comments'), array('escape'=>false));

	if($ratings_badge) echo $html->link($html->image($ratings_badge, array('alt'=>__('Votes', true))),
		array('controller'=>'pages', 'action'=>'display', 'badges#ratings'), array('escape'=>false));

	if($likes_badge) echo $html->link($html->image($likes_badge, array('alt'=>__('Review score', true))),
		array('controller'=>'pages', 'action'=>'display', 'badges#likes'), array('escape'=>false));

	if($adds_badge) echo $html->link($html->image($adds_badge, array('alt'=>__(Configure::read('appSettings.node_plural'), true))),
		array('controller'=>'pages', 'action'=>'display', 'badges#adds'), array('escape'=>false));
?>