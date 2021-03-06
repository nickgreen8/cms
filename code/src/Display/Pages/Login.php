<?php
namespace N8G\Grass\Display\Pages;

use N8G\Database\Database;
use N8G\Utils\Log;
use N8G\Utils\Config;

/**
 * This class is used to build a login page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Login extends PageAbstract
{
	/**
	 * Default constructor
	 *
	 * @param string     $id   Page ID
	 * @param array|null $args Page arguments
	 */
	public function __construct($id, $args)
	{
		Log::notice('Login page created');

		//Set the template name
		$this->template = 'login';

		//Call the parent constructor
		parent::__construct($id, $args);
	}

// Public functions

// Private functions
}