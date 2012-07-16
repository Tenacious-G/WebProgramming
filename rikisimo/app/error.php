<?php
/**
 * Copyright 2010 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class AppError extends ErrorHandler { 
	function error404($params) { 
		$this->controller->layout = "error"; 
		parent::error404($params); 
	} 
}
?>