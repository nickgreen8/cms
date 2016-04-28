<?php
namespace N8G\Grass\Display\Pages;

use N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config,
	N8G\Grass\Display\Output,
	N8G\Grass\Display\Theme;

/**
 * This class is used to build the admin page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Admin extends PageAbstract
{
	/**
	 * Default constructor
	 *
	 * @param string     $id   Page ID
	 * @param array|null $args Page arguments
	 */
	public function __construct($id, $args)
	{
		Log::notice('Admin page created');

		//Set the template name
		$this->template = 'index';

		//Get theme settings and data
		$this->theme = Theme::init('admin');

		//Set the admin value
		Output::setAdmin(true);

		//Build the page data
		$this->build();
	}

// Public functions

// Protected functions

	protected function build()
	{
		Log::info('Building page data');

		//Add page data
		$this->addPageComponent('domain', Config::getItem('url'));
		$this->addPageComponent('settings', $this->theme->getPageSettings($this->template));
		$this->addPageComponent('loggedIn', isset($_SESSION['ng_login']));
		$this->addPageComponent('themeDirectory', sprintf('%sthemes/admin/', str_replace(ROOT_DIR, './', ASSETS_DIR)));
		$this->addPageComponent('copyright', Config::getItem('copyright'));

		//Get all page content
		$this->getPageComponents();
	}

// Private functions
}