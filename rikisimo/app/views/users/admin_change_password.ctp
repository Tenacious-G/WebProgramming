<div id="admin-content">
<div>
<?php 
echo $form->create('User',array('action'=>'change_password/'.$user_id,'admin'=>1));
echo $form->label('password',__('New password',true));
echo $form->password('password');
echo $form->error('password');
echo $form->label('password_confirm',__('New Password Confirmation',true));
echo $form->password('password_confirm');
echo $form->error('password_confirm');
echo $form->end(__('Save',true));
?>
</div>
</div>