<?php

class InstallController extends InstallAppController {

	var $name = 'Install';
    var $uses = null;
    var $components = null;

	/**
	 * if database.php already exists throw 404 error
	 */

    function beforeFilter() {
		if (file_exists(APP.'config'.DS.'database.php')) {
			$this->cakeError('error404');
		}	
        parent::beforeFilter();

        $this->layout = 'install';
        App::import('Component', 'Session');
        $this->Session = new SessionComponent;
    }

	/**
	 * installation method checks directories permissions and
	 * creates the database.php file with the user database name, host, username and password.
	 */

    function index() {
        $this->pageTitle = __('Rikisimo Installation', true);
		$this->set('config_is_writable',is_writable(APP.'config'));
		$this->set('tmp_is_writable', is_writable(TMP));
		$this->set('user_images_is_writable', is_writable(IMAGES.'users'));
		$this->set('event_images_is_writable', is_writable(IMAGES.'nodes'));
		
        if (!empty($this->data)) {
            // test database connection
		
            if (@mysql_connect($this->data['Install']['host'], 
				$this->data['Install']['login'], $this->data['Install']['password']) &&
                mysql_select_db($this->data['Install']['database'])) {
				
                copy(APP.'config'.DS.'database.php.install', APP.'config'.DS.'database.php');
			
                // open database.php file
                App::import('Core', 'File');
                $file = new File(APP.'config'.DS.'database.php', true);
                $content = $file->read();

                // write database.php file
                $content = str_replace('{default_host}', $this->data['Install']['host'], $content);
                $content = str_replace('{default_login}', $this->data['Install']['login'], $content);
                $content = str_replace('{default_password}', $this->data['Install']['password'], $content);
                $content = str_replace('{default_database}', $this->data['Install']['database'], $content);

                if($file->write($content) ) {
					App::import('Model', 'ConnectionManager');
					$db = ConnectionManager::getDataSource('default');
					$this->__executeSQLScript($db, CONFIGS.'sql'.DS.'rikisimo.sql');
					$this->redirect(array('plugin'=>null, 'controller'=>'nodes', 'action'=>'home'));
                } else {
                    $this->Session->setFlash(__('Could not write app/config/database.php file. Check permissions!',
 						true));
                }
            } else {
                $this->Session->setFlash(__('Could not connect to database. Please make sure the database exists 
					and the user has permissions to write the tables.', true));
            }
        }
		else {
			$this->data['Install']['database'] = 'rikisimo';
			$this->data['Install']['host'] = 'localhost';			
		}	
    }

	/**
	 * Execute SQL file
	 *
	 * @link http://cakebaker.42dh.com/2007/04/16/writing-an-installer-for-your-cakephp-application/
	 * @param object $db Database
	 * @param string $fileName sql file
	 * @return void
	 */

    function __executeSQLScript($db, $fileName) {
        $statements = file_get_contents($fileName);
        $statements = explode(';', $statements);

        foreach ($statements as $statement) {
            if (trim($statement) != '') {
                $db->query($statement);
            }
        }
    }
}
?>