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

class Price extends AppModel {

	var $name = 'Price';
	var $useTable = 'prices';
	var $use = array('Price', 'Node');
	var $validate = array(
		'value' => array(
			'rule' => array('minLength', 2),
			'message' => 'Price must be at least 2 characters long',
			'required' => true,
			'allowEmpty' => false,
		)								
	);
		
	var $hasMany = array('Node'=>array('className'=>'Node'));

	/**
	 * update nodes price before deleting node price
 	 * 
 	 * @param $price_id int
	 */

	function delete($price_id) {
		$this->Node->updateAll(array('price_id'=>null), array('price_id'=>$price_id));
		return parent::delete($price_id);
	}
}
?>