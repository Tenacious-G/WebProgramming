<?php echo sprintf(__('Hi %s',true),ucfirst($user['User']['username'])); ?>,


<?php echo sprintf(__('You have a new private message in %s, you can read it by visiting this page:',true),Configure::read('appSettings.name')); ?>


<?php echo Router::url(array('controller'=>'messages', 'action'=>'read', $message_id),true); ?>


<?php __('If you don\'t want to recive email notifications you can configure it in your user profile.'); ?>

