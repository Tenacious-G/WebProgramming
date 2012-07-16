<div class="main_width">
<?php
	if($this->params['action']=="inbox") {
		$title = __('Inbox',true);
		$tableCaption = __('From',true);
	}
	else {
		$title = __('Outbox',true);
		$tableCaption = __('To',true);
	}
?>
	<h1 class="generic_box"><?php echo $title; ?></h1>
	<?php if(!empty($messages)): ?>
	<table id="forum">
		<thead>
				<th class="title"><?php __('Subject'); ?></th>
				<th class="user"><?php echo $tableCaption; ?></th>
				<th class="date"><?php __('Date'); ?></th>
		</thead>
		<tbody>
		<?php
			foreach($messages as $message) {
				$msg_class = "message";
				if($message['Message']['new']) $msg_class = "new_message";
		?>
			<tr>
				<td>
				<?php 
					echo $html->link(ucfirst($message['Message']['subject']),
						array('controller'=>'messages', 'action'=>'read', $message['Message']['id']),
						array('class'=>$msg_class)); 
				?>
				</td>
				<td>
				<?php 
				if($this->params['action']=="inbox"): 
					echo $html->image('users/'.$message['userFrom']['photo']).' ';
  					echo $html->link($message['userFrom']['username'],
						array('controller'=>'users', 'action'=>'view', $message['userFrom']['slug'])); 
  				else:
  					echo $html->image('users/'.$message['userTo']['photo']).' ';
					echo $html->link($message['userTo']['username'],
						array('controller'=>'users', 'action'=>'view', $message['userTo']['slug'])); 
			 	endif; 
				?>
				</td>
				<td>
					<?php echo $time->format('d/n/y H:i', $message['Message']['created']) ?>
				</td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
	
	<div>
	<?php
		echo $this->Element('paginator');
	?>
	</div>
	<?php else: ?>
		<p class="general_info"><?php __('There are no messages.'); ?></p>
	<?php endif;  ?>
	
	<div class="bclear">
	<?php
		if($this->params['action']=="inbox") {
			echo $html->link("<span>".__('Outbox',true)."</span>",
				array('controller'=>'messages', 'action'=>'outbox'),
				array('class'=>'button', 'escape'=>false));
		}
		else {
			echo $html->link("<span>".__('Inbox',true)."</span>",
				array('controller'=>'messages', 'action'=>'inbox'),
				array('class'=>'button', 'escape'=>false));
		}
	?>
	</div>
</div>