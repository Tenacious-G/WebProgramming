<div id="admin-content">
<div class="form-box">
<?php
echo $form->create('Settings', array('url'=>array('action'=>'index')));

echo $form->input('appName', array('label'=>__('Application name', true)));
echo $form->input('appSlogan', array('label'=>__('Application slogan', true)));
echo $form->input('adminEmail', array('label'=>__('Admin email', true)));
echo $form->input('systemEmail', array('label'=>__('System email', true)));
?>
</div>

<div class="form-box">
<?php
echo $form->input('appLanguage', array('type'=>'select', 'options'=>$this->data['languages'], 
	'label'=>__('Language', true)));
?>
</div>


<div class="form-box">
<?php
echo $form->input('googleMapKey', array('label'=>__('Google map key', true). ' ('.$html->link('code.google.com', 'http://code.google.com/intl/es-ES/apis/maps/signup.html', array('onclick'=>'window.open(this.href);return false;')).')'));
?>
</div>


<div class="form-box">
<?php
echo $form->input('bitlyUser', array('label'=>__('Bit.ly username', true).' ('.$html->link('bit.ly', 'http://bit.ly/', array('onclick'=>'window.open(this.href); return false;')).')'));
echo $form->input('bitlyKey', array('label'=>__('Bit.ly API key', true)));
?>
</div>

<?php echo $form->end(__('Submit', true)); ?>
</div>