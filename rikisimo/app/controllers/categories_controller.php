<?php
/**
 * Copyright 2010 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CategoriesController extends AppController{

	var $name = 'Categories';

	/**
	 * set categories variable to display the categpories table to admin
	 */

	function admin_index(){    
		$this->set('title_for_layout', __('Manage categories', true));
		$this->set('categories', $this->Category->find('all', array('order'=>'Category.value ASC')));
	}
	
	/**
	 * add a new category
	 */
	
	function admin_add() {
		if(!empty($this->data) && $this->Category->save($this->data)) {
			$this->redirect(array('action'=>'index'));
		}
		$this->set('title_for_layout', __('Add new category', true));		
	}
	
	/**
	 * edit category
	 *
	 * @param $category_id
	 */
	
	function admin_edit($category_id) {
		if(!empty($this->data)) {
			if($this->Category->save($this->data)) $this->redirect(array('action'=>'index'));
		}
		else {
			$this->data = $this->Category->find(array('id'=>$category_id));			
		}
		$this->set('title_for_layout', __('Edit category', true));
	}
	
	/**
	 * delete category
	 * 
	 * @param $category_id int
	 */
	
	function admin_delete($category_id) {
		$this->Category->delete($category_id);
		$this->redirect(array('action'=>'index'));
	}
}
?>