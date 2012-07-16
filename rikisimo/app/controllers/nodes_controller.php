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
App::import('Sanitize');

class NodesController extends AppController {

	var $name = 'Nodes';
	var $uses = array('Node', 'Tag', 'NodesTag', 'Country', 'City', 'User', 'Comment','Vote', 
		'Price', 'Category');
	var $helpers = array('Html', 'Form', 'Time', 'Text', 'Ajax', 'Javascript', 
		'googleMap', 'RfRating', 'NodeList','bookmark');
	var $components = array('RequestHandler', 'Email','RfRating', 'Bitly');
	var $paginate = array('limit' => 15, 'order' => array('Node.created' => 'desc'));
	
	/**
	 * overload beforeFilter to allow access to some actions
	 */
	
	function beforeFilter() {
		$this->Auth->allow('home','index','view','autoCompleteTags','autoComplete', 
					'report', 'search', 'vvotes', 'map');
		parent::beforeFilter();
	}
   
	/**
	 * Set variables for the homepage
	 */

	function home() {
		$this->set('users', $this->User->getTopUSers(3));
		$this->set('topRated', $this->Node->getTopRated(5));
		$this->set('nodes',$this->Node->getRecent(10));
		$this->set('categories', $this->Category->find('all', array('order'=>'Category.value ASC')));
		$this->set('tags', $this->Tag->getTopTags(null));
		$this->set('countries',$this->City->getCities());		
	}
  
	/**
	* Display a list of nodes 
	*
	* @param city string
	* @param tag string
	* @param $year string
	* @param $month string
	* @param $day string
	*/

	function index($city='all-cities', $category='all-categories', $tag='all-tags') {
		$nodes_id = $city_id = $category_id = false;
		$feed = 'nodes';
		$city_name = 'all-cities';
		$category_name = 'all-categories';
		$tag_name = $tag;

		if($city!='all-cities') {
			$current_city = $this->City->find(array('City.slug'=>$city),
				array('City.id','City.name'), null,(-1));
			if(!$current_city) $this->cakeError('error404');
			$city_name = $current_city['City']['name'];
			$city_id = $current_city['City']['id'];
			$feed = $city;
		}

		if($category!='all-categories') {
			$category_tmp = $this->Category->find(array('slug'=>$category));
			if(empty($category_tmp)) $this->cakeError('error404');
			$category_id = $category_tmp['Category']['id'];
			$category_name = $category_tmp['Category']['value'];
		}

		if($tag!='all-tags') {
			$tag_q = $this->Tag->find(array('Tag.slug'=>$tag), array('id','slug','name'));
			if(empty($tag_q)) $this->cakeError('error404');
			$tag_name = ucfirst($tag_q['Tag']['name']);    
			$nodes_id = $this->NodesTag->find('list',array(
				'conditions'=>array('tag_id'=>$tag_q['Tag']['id']), 'fields'=>array('node_id')));
		}

		$conditions= null;
		if($city_id) $conditions['Node.city_id'] = $city_id; 
		if($nodes_id) $conditions['Node.id'] = $nodes_id;
		if($category_id) $conditions['Node.category_id'] = $category_id;

		$this->paginate['contain'] = array('Photo', 'Tag', 'Category', 'City'=>array('Country'), 
			'Vote', 'User');
		
		$nodes = $this->paginate('Node', $conditions);

		$this->set('categories', $this->Category->find('all', array('order'=>'Category.value ASC')));
		$this->set('category', $category);
		$this->set('category_name', $category_name);		
		$this->set('feed',$feed);
		$this->set('city',$city);
		$this->set('city_name',$city_name);
		$this->set('current_tag',$tag_name);
		$this->set('tag_slug', $tag);
		$this->set('nodes',$nodes);
		$this->set('countries',$this->City->getCities());
		$this->set('tags',$this->Tag->getTopTags($city_id));
		$this->set('title_for_layout', ucfirst(__(Configure::read('appSettings.appSlogan'),true)));
	}

	/**
	 * Display an Node with all it's data.
	 *
	 * @param $city string
	 * @param $slug string
	 **/

	function view($city='all-cities', $slug = null) {
		$this->Node->recursive=2;
		$this->Node->contain(array(
		               'Comment'=>array(
		                        'User'=>array('fields'=>array('username','slug','photo')),
								'Commentvote'),
		                        'User',
		                        'Tag',
		                        'Vote',
		                        'Photo'=> array('limit'=>20),
		                        'City'=>array('Country'),
								'Price',
								'Category'));

		$node = $this->Node->find(array('Node.slug'=>$slug));
		if(!$node || $slug === null) $this->cakeError('error404');
		
		$this->set('title_for_layout', $node['City']['name'].' - '.$node['Node']['name']);
		$this->__checkShortUrl($node);
		
		$this->set('node', $node);
		$this->set('city',$city);
		$this->set('ratingInfo', $this->RfRating->getRatingInfo($node['Node']['id'],
			$this->Auth->user('id')));
		$this->set('me',$this->Auth->user('id'));
		$user_voted = ($this->Auth->user('id'))? 		
			$this->RfRating->userVoted($node['Node']['id'],$this->Auth->user('id')) : false;
		$this->set('user_voted',$user_voted);
		$this->set('nodeView',true);
		$near = $this->Node->getNear($node['Node']['lat'],$node['Node']['lng']);
		array_shift($near);
		$this->set('near', $near);
	}

	/**
	 * Add a new Node. If the Node's city and tags does not exist insert
	 * it all in the database.
	 **/

	function add() {
		$this->set('countries',$this->Country->getCountriesList());
		$prices = $this->Price->find('list', array('fields'=>array('value')));
		$this->set('prices', $prices);
		$this->set('categories', $this->Category->find('list', array('fields'=>array('Category.value'), 
			'order' =>'Category.value ASC')));
		
		if (!empty($this->data)) {
			$this->data['Node']['user_id'] = $this->Auth->user('id');
			$this->data['City']['id'] = $this->City->field('id',array(
                'City.name'=>$this->data['City']['name']));

			if($this->Node->saveAll($this->data)) {
				$node_id = $this->Node->getInsertID();
				$node = $this->Node->find(array('Node.id'=>$node_id),
					array('id', 'Node.slug','City.slug'));
				$tags = explode(',',$this->data['Node']['tags']);
				foreach($tags as $tag):
					$tag = strtolower(trim($tag));
					if($tag!="") {
						$new_tag['Tag']['id']   = $this->Tag->field('id',array('name'=>$tag));
						$new_tag['Tag']['name'] = $tag;
						$new_tag['Node']['id'] = $node_id;
						$this->Tag->create();
						$this->Tag->save($new_tag);     
					}
					endforeach;
					$this->__checkShortUrl($node);
					$this->Session->setFlash(__('Data saved', true));
					$this->redirect(array('action'=>'view', $node['City']['slug'],$node['Node']['slug']));
			}
		}
		$this->set('title_for_layout', __('Add a listing', true));
	}

	/**
	 * Only the user who submited the node can edit it.
	 *
	 * @param $node_id int
	 */

	function edit($node_id=null) {
		$this->Node->recursive = 0;
		$node=$this->Node->find(array('Node.id'=>$node_id),
			array('Node.id','Node.user_id','Node.slug','City.slug'));

		if(empty($node) or $this->Auth->user('id')!=$node['Node']['user_id']) $this->cakeError('error404');

		if($this->__editNode($node_id)) {
			$this->Session->setFlash(__('Data saved', true));
			$this->redirect(array('action'=>'view', $node['City']['slug'], $node['Node']['slug']));
		}
		$this->set('title_for_layout', __('Edit', true));
	}

	/**
	 * Vote for a node and post to user's facebook wall if user is using facebook account
	 * this action is used via ajax and it uses the ajax layout. 
	 *
	 * @param $vote int
	 * @param $node_id int
	 */

	function vote($vote, $node_id) {
		$this->Node->recursive = 0;
		$node=$this->Node->find(array('Node.id'=>$node_id),
			array('Node.id','Node.name','Node.slug','City.slug',));
		if(empty($node)) $this->cakeError('error404');
			
		$this->layout = 'ajax';
		$me = $this->Auth->user('id');
		$info = $this->RfRating->vote($vote, $node_id, $me);
		if(!$info) {
			$info = $this->RfRating->getRatingInfo($node_id, $me);
		}
		if($this->Auth->user('uid')) {
			$msg = sprintf(__n('I gave %d %s star to %s', 'I gave %d %s stars to %s', $vote, true), 
				$vote, Configure::read('appSettings.name'), $node['Node']['name']);
			$link = Router::url(array('controller'=>'nodes', 'action'=>'view', 
				$node['City']['slug'], $node['Node']['slug']), true);
			$this->User->post_facebook_feed($this->Auth->user('uid'), $node['Node']['name'], 
				$msg, $link, $vote.'stars.png');
		}
		$this->set('info',$info);
	}

	/**
	 * remove the current user's vote from a node.
	 *
	 * @param $node_id int
	 */

	function removeVote($node_id) {
		$this->Vote->removeVote($this->Auth->user('id'),$node_id);
		$this->set('info', $this->RfRating->getRatingInfo($node_id, $this->Auth->user('id')));
		$this->render('vote');
	}

	/**
	 * view all votes in node in list user -> vote
	 *
	 * @param $node_id int
	 */
	
	function vvotes($node_id) {
		$this->Node->contain(array('City','userVote'=>array('User')));
		$node = $this->Node->find(array('Node.id'=>$node_id));
		if(!$node) $this->cakeError('error404');
		
		$this->set('node',$node);
		$this->set('title_for_layout', __('Ratings', true));
	}
	
	/**
	 * Function for the Ajax autocompleter used for the city names.
	 * It is used in the add form for the Nodes model.
	 */

	function autoComplete() {
		$conditions = array('City.name LIKE' => $this->data['City']['name'].'%');

		if(isset($this->data['City']['country_id'])) {
			$conditions['country_id'] = $this->data['City']['country_id'];
		}
		$this->set('cities', $this->City->find('all', array(
								'conditions' => $conditions,
								'fields' => array('name')
								)));
		$this->layout = 'ajax';
	}	

	/**
	* Function for the Ajax autocompleter used for the tag names.
	* It gets the last tag the user writes and looks for it in database.
	* Used in the add form for the Nodes model.
	**/

	function autoCompleteTags() {
		if(!strrpos($this->data['Node']['tags'],',')) {
			$tag = $this->data['Node']['tags'];
		}
		else {
			$tag = substr($this->data['Node']['tags'],strrpos($this->data['Node']['tags'],',')+1);
			$tag = trim($tag);
			if(strlen($tag)<1) {
				$this->set('tags',array());
				return false;
			}
		}
    
		$conditions = array('Tag.name LIKE' => $tag.'%');
		$this->set('tags', $this->Tag->find('all', array(
								'conditions' => $conditions,
								'fields' => array('name')
								)));
		$this->layout = 'ajax';
	}

	/**
	 * If something is wrong with an node users can report it to the
	 * admin via email.
	 *
	 * @param $node_id int
	 */

	function report($node_id) {
		$this->set('title_for_layout', Configure::read('appSettings.slogan'));	  
		$this->Node->recursive = -1;
		$node = $this->Node->find(array('Node.id'=>$node_id));
		if(empty($node)) $this->cakeError('error404');
 
		if(!empty($this->data)) {
			$this->Email->from    = Configure::read('appSettings.systemEmail');
			$this->Email->to      = Configure::read('appSettings.adminEmail');
			$this->Email->subject = 'Node Report on '.$node_id.': '.$node['Node']['name'];
			$this->Email->delivery = 'mail';
			$this->Email->send($this->data['Node']['report']);
			$this->Session->setFlash(sprintf(__('%s reported', true), Configure::read('appSettings.node_single')));
			$this->redirect( array('action'=>'index'));
		}

		$this->set('node',$node);
	}
	
	/**
	 * show nodes near a user submited address using google geolocation service
	 */
	
	function map() {
		$this->set('title_for_layout', __('Search by address', true));
		if($this->data) {
			$params = array('q'=>$this->data['Map']['address'], 'output'=>'xml', 'oe'=>'utf-8');
			App::import('HttpSocket');
			App::import('Xml');			
			$http =& new HttpSocket();
			$address =& new XML($http->get('http://maps.google.com/maps/geo?'.http_build_query($params)));	
			$address = Set::reverse($address);
			if($address['Kml']['Response']['Status']['code']==200 and 
				isset(	$address['Kml']['Response']['Placemark']['Point']['coordinates'])) {	
				$coordinates = explode(',', 
					$address['Kml']['Response']['Placemark']['Point']['coordinates']);
				$near = $this->Node->getNear($coordinates[1],$coordinates[0]);
				
				$this->set('near', $near);	
				$this->set('coordinates', $coordinates);
				$this->set('address', $this->data['Map']['address']);
				$this->render('MapSearch');
			}
			else {
				$this->set('addressError', true);
			}
		}
	}
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////// ADMIN
	//////////////////////////////////////////////////////////////////////
 
	/**
	 * Show main admin page with a list of nodes.
	 **/

	function admin_index() {
		$this->set('title_for_layout', sprintf(__('Manage %s', true), sprintf(__('Edit %s', true),
			__(Configure::read('appSettings.node_singular'), true))));
		$this->set('nodes', $this->paginate('Node'));
	}

	/**
	 * Delete node using its id.
	 *
	 * @para id int
	 **/

	function admin_delete($id) {
		$this->Node->delete($id);
		$this->flash(sprintf(__('%s deleted', true), sprintf(__('Edit %s', true),
			__(Configure::read('appSettings.node_singular'), true))), array('action'=>'index'));
	}

	/**
	 * Edit an existing node.
	 *
	 * @param $node_id int
	 */

	function admin_edit($node_id) {
		$this->set('title_for_layout', sprintf(__('Edit %s', true), sprintf(__('Edit %s', true),
			__(Configure::read('appSettings.node_singular'),
		 	true))));
		if($this->__editNode($node_id)) {
			$this->flash(__('Node saved', true), array('action'=>'index'));    
		}
	}

	//////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////// Private Methods
	//////////////////////////////////////////////////////////////////////
  
	/**
	 * private method used to edit a node by users and admin
	 *
	 * @param $node_id int
	 */

	function __editNode($node_id) {
		if(!empty($this->data)) {
			$this->data['Node']['id'] = $node_id;

			$this->data['City']['id'] = $this->City->field('id',array(
				'City.name'=>strtolower($this->data['City']['name'])));
          
			if($this->Node->saveAll($this->data)) {
				$this->Tag->deleteNodeTags($node_id);
				$tags = explode(',',$this->data['Node']['tags']);

				foreach($tags as $tag):
					$tag = strtolower(trim($tag));
					if($tag!="") {
						$new_tag['Tag']['id']   = $this->Tag->field('id',array('name'=>$tag));
						$new_tag['Tag']['name'] = $tag;
						$new_tag['Node']['id'] = $node_id;
						$this->Tag->create();
						$this->Tag->save($new_tag);     
					}
				endforeach;
				return true;
			}
		}
		
		$this->set('prices', $this->Price->find('list', array('fields'=>array('Price.value'))));
		$this->set('categories', $this->Category->find('list', array('fields'=>array('Category.value'),
			'order'=>'Category.value ASC')));
			
		$this->set('countries',$this->Country->getCountriesList());
		$this->Node->recursive = 1;
		$this->data=$this->Node->find(array('Node.id'=>$node_id));
		$tag_list = "";
		foreach($this->data['Tag'] as $tag):
			if(!empty($tag_list)) $tag_list .= ', ';
			$tag_list.= $tag['name'];
		endforeach;		
		$this->data['Node']['tags'] = $tag_list;
	}
	
	/**
	 * check if node has short url, if it doesn't get one at bit.ly
	 * and save it to database
	 *
	 * @param $node array
	 */

	function __checkShortUrl($node) {
		if(!Configure::read('appSettings.bitlyUser')) return false;
		$this->Bitly->setApiInfo(Configure::read('appSettings.bitlyUser'),
			Configure::read('appSettings.bitlyKey'));
		if(!isset($node['Node']['shorturl']) or !$node['Node']['shorturl']) {
			$short = $this->Bitly->shorten(Router::url(array('controller'=>'nodes', 
				'action'=>'view', $node['City']['slug'], $node['Node']['slug']), true));		
			if($node['Node']['shorturl'] = $short) {
				$this->Node->id = $node['Node']['id'];
				$this->Node->saveField('shorturl', $short);
				$node['Node']['short'] = $short;
			}
		}
	}
}
?>