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

class Settings extends AppModel {

	var $name = 'Settings';

	/**
	 * global application settings variable
	 */

	var $appSettings = null;

	/**
	 * overwrite before save function
	 * update new settings values and delete settings cache
	 *
	 * @return boolean
	 */
	
	function beforeSave() {
		foreach($this->data['Settings'] as $key => $value) {
			$this->updateKeyValue($key, $value);
		}
		Cache::delete('app_settings');
		$this->appSettings = null;
		return parent::beforeSave();
	}

	/**
	 * if $appSettings is not set read settings from cache or database and return settings.
	 * if param $key is specified return its value.
	 *
	 * @param $key string
	 * @return mixed
	 */

	function getSettings($key = null) {
		if($this->appSettings===null and ($this->appSettings=Cache::read('app_settings'))===false) {
			$this->appSettings = Set::combine($this->find('all'), 
				'{n}.Settings.key', '{n}.Settings.value');
			$this->appSettings['languages'] = $this->getLanguages();
			Cache::write('app_settings', $this->appSettings);
		}

		if($key) {
			return $this->appSettings[$key];
		}
		else {
			return $this->appSettings;
		}
	}

	/**
	 * update settings with $key and $value
	 *
	 * @param $key string
	 * @param $value string
	 */

	function updateKeyValue($key, $value) {
		$value = '\''.mysql_real_escape_string($value).'\'';
		$this->updateAll(array('value'=>$value), array('key'=>$key));		
	}
	
	/**
	 * return an array with the languages installed in the app/locale folder
	 *
	 * @return array
	 */
	
	function getLanguages() {
		$locale_path = APP.'locale';
		$languages = array();
		if(is_dir($locale_path) and $handler = opendir($locale_path)) {
			while(($file = readdir($handler)) !== false) {
				if(strlen($file)==3) $languages[$file] = __($file, true);
			}
		}
		return $languages;
	}
}
?>