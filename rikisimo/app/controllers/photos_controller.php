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

class PhotosController extends AppController{

	var $name = 'Photos';
	var $uses = array('Photo','Node');
	var $helpers = array('Html', 'Time', 'Javascript');
	var $paginate = array('limit' => 25, 'order' => array('Node.created' => 'desc'));    

	/**
	 * overload beforeFilter to allow public access to the view action
	 */

	function beforeFilter() {
		$this->Auth->allow('view');
		return parent::beforeFilter();
	}

	/**
	 * upload photo
	 *
	 * @param $node_id int
	 */

	function upload($node_id) {
		$this->Node->contain(array('City.slug'));
		$node = $this->Node->find(array('Node.id'=>$node_id));    
		if(empty($node)) $this->cakeError('error404');
		
		if(!empty($this->data)) {
			$this->data['Photo']['node_id'] = $node_id;
			$this->data['Photo']['user_id'] = $this->Auth->user('id');
			if($this->Photo->save($this->data)) {
				$this->Session->setFlash(__('Photo uploaded', true));
				$this->redirect(array('controller'=>'nodes', 'action'=>'view',
					$node['City']['slug'],$node['Node']['slug']));
			}
		}
		$this->set('node', $node);
		$this->set('title_for_layout', __('Upload photo', true));
	}

	/**
	 * Only the owner of the photo can delete it.
	 *
	 * @param $photo_id int
	 */

	function delete($photo_id) {
		$this->Photo->recursive = -1;
		$photo = $this->Photo->find(array('id'=>$photo_id, 'user_id'=>$this->Auth->user('id')));
		if(empty($photo)) $this->cakeError('error404');
		$this->Photo->delete($photo_id);
		$this->Session->setFlash(__('The photo has been deleted', true));
		$this->redirect(array('controller'=>'users', 'action'=>'view'));
	}

	/**
	 * View photo
	 * 
	 * @param $node_slug string
	 * @param $photo_id int
	 */

	function view($node_slug, $photo_id) {
		$this->Photo->contain(array('User'=>array('fields'=>array('id','username','slug')),
			'Node'=>array('City', 'Photo', 'fields'=>array('Node.slug', 'Node.name'))));
		$photo = $this->Photo->find(array('Photo.id'=>$photo_id));
      	if(empty($photo)) $this->cakeError('error404');

		$this->set('title_for_layout', $photo['Node']['name'].' - '.$photo['Photo']['title']);
		$this->set('photo', $photo);
		$this->set('me', $this->Auth->user('id'));
	}

	///////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////// ADMIN
	///////////////////////////////////////////////////////////////////

	/**
	 * Show all photos to admin
	 */

	function admin_index() {
		$this->set('title_for_layout', __('Manage photos', true));
		$this->set('photos',$this->paginate('Photo'));
	}   

	/**
	 * admin can delete photos uploaded by users
	 * 
	 * @param $photo_id int
	 */

	function admin_delete($photo_id) {
		$this->Photo->delete($photo_id);
		$this->flash(__('Photo deleted',true), array('action'=>'index'));
	}
}
?>