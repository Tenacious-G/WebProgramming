<div id="form-block">
	<div class="info">
		<p><?php printf(__('Upload a photo to %s.',true), $node['Node']['name']); ?></p>
	</div>

	<!-- form start -->
	<div class="form">
		<h1 class="generic_box"><?php __('Upload photo'); ?></h1>
		<form method="post" enctype="multipart/form-data" action="<?php echo 	$html->url('/photos/upload/'.$node['Node']['id'])?>" id="PhotoUpload">


		<div style="font-size:12px;margin-top: 10px">
			<?php echo $form->file('Photo.filedata'); ?>
		</div>
		<div>
		<?php
			echo $form->input('Photo.title',array('value'=>$node['Node']['name']));
			echo $form->input('Photo.description', array('type'=>'textarea', 'label'=>__('Description', true)));
		?>
		</div>
		<div class="submit">
			<?php 
			echo "<p class=\"general_info\"> (".__('max size', true).' '.ini_get('upload_max_filesize').")</p>";
			echo $form->end(array('label'=>__('Submit', true), 'div'=>false));
			echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel',true), 
					array('controller'=>'nodes', 'action'=>'view', 
					$node['City']['slug'], $node['Node']['slug'])).".</p>";
			?>
			<div class="clear"></div>
		</div>
		
		</form>
	</div>
	<!-- form end -->

	<div class="clear"></div>
</div>