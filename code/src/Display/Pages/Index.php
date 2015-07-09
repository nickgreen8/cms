<?php
namespace N8G\Grass\Display\Pages;

use N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

/**
 * This class is used to build an index page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Index extends PageAbstract
{
	/**
	 * Default constructor
	 *
	 * @param string $content Page content
	 */
	public function __construct($content)
	{
		Log::notice('Index page created');

		//Set the template name
		$this->template = 'index';

		//Call the parent constructor
		parent::__construct($content);
	}

// Public functions

// Protected functions

// Private functions
}