<div class="info">
	<?php 
		if($this->params['action']=='add'):
			printf(__('To add a new listing just fill the form with the required data.', true));		
		endif;
		?>
</div>
<div class="form">
	<h1 class="generic_box"><?php __('Add a new listing'); ?></h1>
<?php
	
	if($this->params['action']=='add'):
		echo $form->create('Node', array('action'=>'add'));
	else:
		echo $form->create('Node',array('edit/'.$this->data['Node']['id']));	
	endif;
	
	echo $form->hidden('lat',array('value'=>null));
	echo $form->hidden('lng',array('value'=>null));
	
    echo $form->label('City.country_id',__('Country',true));
	echo $form->select('City.country_id',$countries,null,array($this->data['City']['country_id']));
    echo $form->error('City.country_id');
	  
	echo $form->label('city.name', __('City',true)); 
	echo $ajax->autoComplete('City.name', '/nodes/autoComplete',array('callback'=>'
	  function(element, entry) {
      return entry+"&"+Form.Element.serialize("CityCountryId");
    }'));
    echo $form->error('City.name');
		
	echo $form->input('name',array('label'=>__('Name',true)));
	echo $form->input('notes',array('label'=>__('Description',true)));
//	echo $form->input('price',array('label'=>__('Price',true)));
	echo $form->label('Node.price_id', __('Price', true));
	echo $form->select('Node.price_id', $prices, null, array($this->data['Node']['price_id']));
	echo $form->error('Node.price_id');
	echo $form->label('Node.category_id', __('Category', true));
	echo $form->select('Node.category_id', $categories, null, array($this->data['Node']['category_id']));
	echo $form->error('Node.category_id');
	echo $form->input('address');
	

	echo __('Click on the map to place the marker',true);									
	echo "<div id=\"node-map\" style=\"overflow:hidden;padding:0px;border:1px solid #000;\">";
    
	//google map
    $default = array('type'=>'0','zoom'=>15,'lat'=>0,'long'=>0);
    echo $googleMap->map($default, $style = 'width:100%; height: 320px' );
    echo $googleMap->showAddressEdit();
    echo "</div>";
    
    if($this->data['Node']['lat'] and $this->data['Node']['lng']) {
      echo $googleMap->showLatLngEdit($this->data['Node']['lat'],$this->data['Node']['lng']);
    }

	echo $form->input('phone');	
	echo $form->input('web');

	echo $form->label('tags',__('Tags',true));
	echo $ajax->autoComplete('tags', '/nodes/autoCompleteTags',array('updateElement'=>
	  'function(item)
	   {
	    tags = document.getElementById("Tags");
	    if(tags.value.lastIndexOf(",")>0) item.innerHTML=", "+item.innerHTML;
	    tags.value = tags.value.substr(0,tags.value.lastIndexOf(","))+item.innerHTML;
	    tags.focus();
	   }'));	  
	  __('Separate each tag with a comma.');
	?>
	<div class="submit">
	<?php
	echo $form->end(array(__('Submit',true), 'div'=>false));
	echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel', true), array('action'=>'index')).".</p>";
	?>
	<div class="clear"></div>	
</div>
</div>