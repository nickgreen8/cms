<?php
namespace N8G\Grass\Display\Pages;

use N8G\Grass\Display\Theme,
	N8G\Grass\Display\Navigation,
	N8G\Grass\Exceptions\Display\PageException,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config,
	Parsedown;

/**
 * This class is used to build an index page. This is a singleton class that is used
 * to load the relevant index page from the relevant theme. All relevant data will
 * also be parsed ready to be inserted into the page template. This will then be
 * requested from the page and output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
abstract class PageAbstract implements PageInterface
{
	/**
	 * The ID of the page
	 * @var string|integer
	 */
	private $id;
	/**
	 * The array of data ready to be output into the page. This MUST be an associative
	 * array and the keys must match the keys in the template.
	 * @var array
	 */
	protected $data = array();
	/**
	 * The name of the template to import
	 * @var string
	 */
	protected $template;

	/**
	 * Default constructor
	 *
	 * @param string $content Page content
	 */
	public function __construct($id)
	{
		//Set the content of the page
		$this->id = $id;

		//Build the page data
		$this->build();
	}

//Public functions

	/**
	 * This function is used to parce the content of the page. The content is parsed as a string
	 * before being converted into HTML and returned as a HTML string.
	 *
	 * @param  string $content The content that is pulled from the database.
	 * @return string          The page content as a HTML string.
	 */
	public function parseContent($content)
	{
		Log::debug('Converting content to HTML.');
		Log::info(sprintf('Converting string: %s', $content));

		//Create converter
		$markdown = new Parsedown();

		//Convert to HTML
		$html = $markdown->text($content);

		//Set the content and return
		return $html;
	}

	/**
	 * This function gets the name of the template that should be used.
	 *
	 * @return string The filename of the template
	 */
	public function getTemplateName()
	{
		Log::info('Getting the page template name');
		//Return the name of the template file
		return $this->template;
	}

	/**
	 * This function returns the data array of the page. This will be an associative array
	 * that will have the keys that match the template.
	 *
	 * @return array The data array
	 */
	public function getData()
	{
		Log::info('Getting page content');
		//Return the page data
		return $this->data;
	}

	/**
	 * This function adds an additional element and the associated data to the page data array.
	 *
	 * @param  string $key  The element name in the template
	 * @param  mixed  $data The data that will be output on the page
	 * @return void
	 */
	public function addPageComponent($key, $data)
	{
		//Validate key
		if ($key === null || (is_string($data) && trim($key) === '')) {
			//Throw error
			throw new PageException('No key specified!', Log::WARN);
		}

		//Check for empty data
		if ($data === null || (is_string($data) && trim($data) === '')) {
			//Throw error
			throw new PageException(sprintf('No data has been specified for the key (%s)', $key), Log::WARN);
		}

		//Check the key is not protected
		if (in_array($key, array())) {
			//Throw error
			throw new PageException('The key that you have tried to use is protected. This cannot be used!', Log::WARN);
		}

		//Add to the page data
		$this->data[$key] = $data;
	}

// Protected functions

	/**
	 * This function builds up the content for the page. Data is built into the page data array for
	 * display when the page is rendered. This is the parent function and can be overriden within
	 * the child page classes. THIS MUST BE CALLED FROM THE CHILD OVERRIDEN FUNCTION.
	 *
	 * @return void
	 */
	protected function build()
	{
		//Get theme settings and data
		$theme = Theme::init();
		//Create navigation object
		$navigation = new Navigation();

		//Add page data
		$this->addPageComponent('settings', $theme->getSettings($this->template));
		$this->addPageComponent('themeDirectory', $theme->getDirectory());
		$this->addPageComponent('headerNavigation', $navigation->buildHeaderNavigation());
		$this->addPageComponent('footerNavigation', $navigation->buildFooterNavigation());
		$this->addPageComponent('copyright', Config::getItem('copyright'));

		//Get all page content
		$this->getPageComponents();
	}

//Private functions

	/**
	 * This function gets the data related to the page. This is then used to build up the data
	 * array for everything that is to be input into the page. On render, all the data that has
	 * been prepared is output into the page.
	 *
	 * @return void
	 */
	private function getPageComponents()
	{
		//Get the data from the database
		$data = $this->getPageData($this->id);

		Log::debug(sprintf('Page name: %s', $data['name']));
		Log::debug(sprintf('Page type: %s', $data['type']));

		//Set page identifiers in Config
		Config::setPageId($data['id']);
		Config::setPageName($data['name']);

		//Assign data to page array
		$this->addPageComponent('name', $data['name']);
		$this->addPageComponent('type', $data['type']);
		$this->addPageComponent('title', $this->buildTitle($data));
		$this->addPageComponent('content', $this->parseContent($data['content']));

		//Add page utility vars
		$this->addPageComponent('pageLink', $this->calcPageLink($data['name']));
	}

	/**
	 * This function builds the page title. This includes all parent pages as well as the site tag
	 * line if it is the home page. The page data array is passed and the title as a string is
	 * returned.
	 *
	 * @param  array  $data The page data pulled from the database
	 * @return string       The fully formed page title
	 */
	private function buildTitle($data)
	{
		//Create title array
		$title = array();

		//Check if it is the home page
		if ($data['type'] === 'index') {
			//Add the site tag line
			array_unshift($title, Config::inConfig('site-tag-line') ? trim(Config::getItem('site-tag-line')) : trim(ucwords($data['name'])));
		} else {
			//Get parent
			while ($data['parent'] !== null && (is_numeric($data['parent']) && $data['parent'] > 0)) {
				//Add page name
				array_unshift($title, trim(ucwords($data['name'])));
				//Get parent data
				$data = $this->getPageData($data['parent']);
			}
			//Add final parent
			array_unshift($title, trim(ucwords($data['name'])));
		}

		//Prepend the site title
		array_unshift($title, trim(Config::getItem('site-title')));

		//Return built title
		return implode(sprintf(' %s ', Config::getNavSeparator()), $title);
	}

	/**
	 * This function works out the page link for the page.
	 *
	 * @param  string $name The page name
	 * @return string       The converted page name to link
	 */
	private function calcPageLink($name)
	{
		return strtolower(str_replace(' ', '-', preg_replace("/\'/", '', $name)));
	}

	/**
	 * This function gets all the data for a page. The ID which is set within the class is used to get
	 * the relevant data from the database. The query object is returned.
	 *
	 * @param  integer|string $id The ID of the page to request
	 * @return object             The query object containing the page data.
	 */
	private function getPageData($id)
	{
		//Get the data from the DB
		$data = Database::execProcedure('GetPageData', array('page' => $id));
		return $data[0];
	}
}