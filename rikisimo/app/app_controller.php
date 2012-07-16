<?php
/* SVN FILE: $Id: app_controller.php 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-02 00:33:52 -0600 (Wed, 02 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */

class AppController extends Controller {

    var $uses = array('Message', 'User', 'Settings');
    var $components = array('Auth', 'Session');
	var $helpers = array('Html', 'Form', 'Javascript', 'Session');
    var $appVersion = '2.20110331';

	/**
	 * set some basic configuration defaults
	 */

    function beforeFilter() {
		Configure::write('appSettings', $this->Settings->getSettings());
		Configure::write('appSettings.appVersion', $this->appVersion);
		$this->set('title_for_layout', Configure::read('appSettings.appSlogan'));    
		
		$this->Auth->loginAction = array('admin'=>false, 'controller'=>'users', 'action'=>'login');
		$this->Auth->logoutRedirect = array('controller'=>'nodes', 'action'=>'home');
		$this->Auth->fields = array('username'=>'email', 'password'=>'password');

		if($this->params['controller']=='pages') $this->Auth->allow('*');  
		$this->Session->write('Config.language', Configure::read('appSettings.appLanguage'));
		$this->checkSession();
	}
  
	/**
	 * check user session and look for new private messages
	 */

    function checkSession() { 
		if($this->Auth->user()) {

			// check if user still exists
			$this->User->recursive = -1;
			$user = $this->User->find('first', array('conditions'=>array('User.id'=>$this->Auth->user('id'))));
			if(empty($user)) {
				$this->redirect($this->Auth->logout());
			}

        	$this->set('messages_number',$this->Message->countUserMessages($this->Auth->user('id')));
			if(isset($this->params['admin']) and !$this->Auth->user('admin')) $this->cakeError('error404');
        	if($this->Auth->user('admin')) {
				$this->Auth->allow('*');
				if(isset($this->params['admin'])) $this->layout = "admin_default";
			}
      	}
    }	
}
?>