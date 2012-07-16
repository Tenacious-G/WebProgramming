<?php echo sprintf(__('Hi %s',true),$user['User']['username']); ?>,

<?php echo sprintf(__('If you need to change your %s user password login in this url:',true),Configure::read('appSettings.name')); ?>

<?php echo Router::url(array('controller'=>'users', 'action'=>'code_login', $code),true); ?>


<?php __('If you don\'t need to change your password just ignore this email.'); ?>

