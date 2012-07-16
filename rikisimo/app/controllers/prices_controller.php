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

class PricesController extends AppController{

	var $name = 'Prices';

	/**
	 * set prices variables to display the prices table to the admin
	 */

	function admin_index(){    
		$this->set('title_for_layout', __('Manage prices', true));
		$this->set('prices', $this->Price->find('all'));
	}
	
	/**
	 * add new price tag
	 */
	
	function admin_add() {
		if(!empty($this->data) && $this->Price->save($this->data)) {
			$this->redirect(array('admin'=>true, 'action'=>'index'));
		}
		$this->set('title_for_layout', __('Add new price tag', true));
	}
	
	/**
	 * edit price
	 *
	 * @param $price_id
	 */
	
	function admin_edit($price_id) {
		if(!empty($this->data)) {
			if($this->Price->save($this->data)) $this->redirect(array('action'=>'index'));
		}
		else {
			$this->data = $this->Price->find(array('id'=>$price_id));			
		}
		$this->set('title_for_layout', __('Edit price tag', true));		
	}
	
	/**
	 * delete price
	 * 
	 * @param $price_id int
	 */
	
	function admin_delete($price_id) {
		$this->Price->delete($price_id);
		$this->redirect(array('action'=>'index'));
	}
}
?>