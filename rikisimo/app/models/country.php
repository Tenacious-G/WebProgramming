<?php
/**
 * Copyright 2008 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Country extends AppModel {

	var $name = 'Country';
	var $useTable = 'countries';
	var $hasMany = array('City'=>array('className'=>'City'));                   

	/**
	 * returns a list of countries to use in forms
	 * it will display countries already in use first
	 */

	function getCountriesList() {
		$countries = $this->query(
	   'select countries.id, countries.name, count(nodes.id) as nodes from cities left join countries 
	    on cities.country_id = countries.id left join nodes on nodes.city_id = cities.id group by
		countries.name order by nodes desc');
	  
		$new_array = array();
		$ids[] = 0;
	  
		foreach($countries as $country):
			$new_array[$country['countries']['id']] = __($country['countries']['name'],true);
			$ids[] = $country['countries']['id'];
		endforeach;
		if(!empty($new_array)) $new_array['--'] = '---------------';
		$countries = $this->find('list',array('conditions'=>array('NOT'=>array('Country.id'=>$ids))));
	  
		foreach($countries as $key => $country ):
			$new_array[$key]=__($country,true);
		endforeach;
		return $new_array;
	}
}
?>