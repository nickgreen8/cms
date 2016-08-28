<?php
namespace N8G\Grass\Display\Pages;

/**
 * This class is used to build a normal page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Page extends PageAbstract
{
	
	/**
	 * The name of the template to import
	 * @var string
	 */
	protected $template = 'page';

// Public functions

	/**
	 * This function builds the page title. This includes all parent pages as well as the site tag
	 * line if it is the home page. The page data array is passed and the title as a string is
	 * returned.
	 *
	 * @param  array  $data The page data pulled from the database
	 * @return string       The fully formed page title
	 */
	public function buildTitle()
	{
		//Check if the admin page has been requested
		if ($this->id === 'Admin') {
			$title = array(
				$this->container->get('config')->options->title,
				'Admin'
			);

			//Loop through the arguments
			foreach ($this->args as $value) {
				array_push($title, ucwords(str_replace('-',	' ', $value)));
			}

			//Return built title
			return implode(sprintf(' %s ', $this->container->get('config')->options->titleSeperator), $title);
		} else {
			return parent::buildTitle();
		}
	}

// Private functions
}