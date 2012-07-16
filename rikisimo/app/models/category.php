<?php
/**
 * Copyright 2008 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Category extends AppModel {

	var $name = 'Category';
	var $useTable = 'categories';
	var $validate = array(
		'value' => array(
			'rule' => array('minLength', 2),
			'message' => 'Name must be at least 2 characters long',
			'required' => true,
			'allowEmpty' => false,
		)								
	);
		
	var $hasMany = array('Node'=>array('className'=>'Node'));

	/**
	 * create the category slug before saving
	 */

	function beforeSave() {
		$category_slug = Inflector::slug($this->data['Category']['value']);
		if(!$category_slug) {
			$this->data['Category']['slug'] = $this->data['Category']['value'];
		}
		else {
			$this->data['Category']['slug'] = $category_slug;
		}
		return parent::beforeSave();
	}

	/**
	 * update nodes category to null before deleting a category
 	 * 
 	 * @param $category_id int
	 */

	function delete($category_id) {
		$this->Node->updateAll(array('category_id'=>null), array('category_id'=>$category_id));
		parent::delete($category_id);
	}

}
?>