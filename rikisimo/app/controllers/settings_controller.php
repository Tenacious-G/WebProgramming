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

class SettingsController extends AppController {

	/*
	 * global application settings variable
	 */

	var $name = 'Settings';
	var $uses = array('Settings', 'Country', 'City');

	/**
	 * Dislay admin control panel settings and save changes made by admin users.
	 * Settings are stored in the database using a key / value system.
	 *
	 * Application version number is set in app/app_controller.php
	 */

	function admin_index() {
		$this->set('title_for_layout', 
			sprintf(__('Application settings (%s)', true), Configure::read('appSettings.appVersion')));
		if($this->data) {
			if($this->Settings->save($this->data)) {
				$this->Session->setFlash(__('Settings saved', true));
				$this->redirect(array('admin'=>true, 'controller'=>'nodes', 'action'=>'index'));
			}
		}
		else {
			$this->data['Settings'] = Configure::read('appSettings');		
		}
		
		// do not use languages in cahce, force to read languages in locale directory
		$this->data['languages'] = $this->Settings->getLanguages();
		$this->set('countries', $this->Country->getCountriesList());	
	}
}
?>