<div class="search-form form " id="form-block">
<?php
echo $form->create('Searches', array('action'=>'results'));
echo $form->label('city', __('City', true));
echo $form->select('city',$cities,null,array($this->data['Searches']['city']));

echo $form->label('price', __('Price', true));
echo $form->select('price',$prices,null,array($this->data['Searches']['price']));
echo $form->label('category', __('Category', true));
echo $form->select('category', $categories, null, array($this->data['Searches']['category']));
echo $form->input('term', array('label'=>__('Search term', true)));
?>
	<div class="submit">
	<?php
	echo $form->end(array('label'=>__('Search',true), 'div'=>false));
	echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('search by address', true), array('controller'=>'nodes', 'action'=>'map')).".</p>";
	?>
	<div class="clear"></div>	
</div>
</div>