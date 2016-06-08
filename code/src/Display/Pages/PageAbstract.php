<?php
namespace N8G\Grass\Display\Pages;

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
	protected $id;
	/**
	 * The array of data ready to be output into the page. This MUST be an associative
	 * array and the keys must match the keys in the template.
	 * @var array
	 */
	private $data = array();
	/**
	 * The aruments that are passed to the page.
	 * @var array|null
	 */
	protected $args;
	/**
	 * An instance of the current theme object
	 * @var object
	 */
	protected $theme;
	/**
	 * Application container reference.
	 * @var object
	 */
	protected $container;

	/**
	 * Default constructor
	 *
	 * @param object     $container An instance of the container.
	 * @param string     $id        Page ID
	 * @param array|null $args      Page arguments
	 */
	public function __construct(&$container)
	{
		//Set the container
		$this->container = &$container;
	}

//Public functions

	/**
	 * This function gets the name of the template that should be used.
	 *
	 * @return string The filename of the template
	 */
	public function getTemplateName()
	{
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
		//Return the page data
		return $this->data;
	}

	/**
	 * This function gets the arguments for the page.
	 * @return array|null The arguements for the page
	 */
	public function getArgs()
	{
		//Return the page arguments
		return $this->args;
	}

	/**
	 * This function sets the ID for the page.
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;
		//Return the page instance
		return $this;
	}

	/**
	 * This function sets the arguments for the page.
	 * @return $this
	 */
	public function setArgs($args)
	{
		$this->args = $args;
		//Return the page instance
		return $this;
	}

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
		$this->container->get('logger')->debug('Building title');

		//Get page data
		$data = $this->getPageData($this->id);
		//Create title array
		$title = array();

		//Check if it is the home page
		if ($data['type'] === 'index') {
			//Add the site tag line
			array_unshift(
				$title,
				isset($this->container->get('config')->options->tagline)
				? $this->container->get('config')->options->tagline
				: $data['name']
			);
		} else {
			//Get parent
			while ($data['parent'] !== null && (is_numeric($data['parent']) && $data['parent'] > 0)) {
				//Add page name
				array_unshift($title, $data['name']);
				//Get parent data
				$data = $this->getPageData($data['parent']);
			}
			//Add final parent
			array_unshift($title, $data['name']);
		}

		//Prepend the site title
		array_unshift($title, $this->container->get('config')->options->title);

		//Return built title
		return implode(sprintf(' %s ', $this->container->get('config')->options->titleSeperator), $title);
	}

	/**
	 * This function builds up the content for the page. Data is built into the page data array for
	 * display when the page is rendered. This is the parent function and can be overriden within
	 * the child page classes.
	 *
	 * @return void
	 */
	public function build()
	{
		$this->container->get('logger')->info('Building page data');
		$this->container->get('logger')->notice('Creating a page (%s) with the arguments: %s', $this->id, implode(', ', $this->args));
		$this->container->get('logger')->debug('Page type: %s', $this->getTemplateName());

		//Get the page settings
		$this->addPageComponent('settings', $this->container->get('theme')->getPageSettings($this->getTemplateName()));

		//Get all page content
		$this->getPageComponents();
	}

	public function render()
	{
		//Load the template
		$template = $this->container->get('twig')->loadTemplate(sprintf(
			'themes/%s/%s.tmpl',
			$this->container->get('config')->theme->active,
			$this->getTemplateName()
		));

		return $this->container->get('twig')->render($template, $this->data);
	}

// Protected functions

	/**
	 * This function is used to parce the content of the page. The content is parsed as a string
	 * before being converted into HTML and returned as a HTML string.
	 *
	 * @param  string $content The content that is pulled from the database.
	 * @return string          The page content as a HTML string.
	 */
	protected function parseContent($content)
	{
		$this->container->get('logger')->debug('Converting content to HTML.');
		$this->container->get('logger')->info(sprintf('Converting string: %s', $content));

		//Convert to HTML and return
		return $this->container->get('markdown')->text($content);
	}

	/**
	 * This function adds an additional element and the associated data to the page data array.
	 *
	 * @param  string $key  The element name in the template
	 * @param  mixed  $data The data that will be output on the page
	 * @return void
	 */
	protected function addPageComponent($key, $data)
	{
		//Validate key
		if ($key === null || (is_string($data) && trim($key) === '')) {
			//Throw error
			throw new PageException('No key specified!');
		}

		//Check the key is not protected
		if (in_array($key, array())) {
			//Throw error
			throw new PageException('The key that you have tried to use is protected. This cannot be used!', $this->container->get('logger')->WARN);
		}

		//Add to the page data
		$this->data[$key] = $data;
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

		$this->container->get('logger')->debug(sprintf('Page name: %s', $data['name']));
		$this->container->get('logger')->debug(sprintf('Page type: %s', $data['type']));

		//Set page identifiers in Config
		$this->container->add('page-id', $data['id']);
		$this->container->add('page-name', $data['name']);
		$this->container->add('page-type', $data['type']);

		//Assign data to page array
		$this->addPageComponent('name', $data['name']);
		$this->addPageComponent('type', $data['type']);
		$this->addPageComponent('title', $this->buildTitle($data));
		$this->addPageComponent('content', $this->parseContent($data['content']));

		//Add page utility vars
		$this->addPageComponent('pageLink', $this->calcPageLink($data['name']));
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
	protected function getPageData($id)
	{
		//Get the data from the DB
		$data = $this->container->get('db')->execProcedure('GetPageData', array('page' => $id));
		return $data[0];
	}
}