<?php
namespace N8G\Grass\Display\Pages;

use N8G\Grass\Display\Theme,
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
	public function __construct($content)
	{
		//Set the content of the page
		$this->data['content'] = $this->parseContent($content);

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
		if (in_array($key, array('content'))) {
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

		$this->addPageComponent('settings', $theme->getSettings($this->template));
		$this->addPageComponent('theme-directory', $theme->getDirectory());
	}

//Private functions
}