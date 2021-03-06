<?php
namespace N8G\Grass\Display;

use N8G\Grass\Components\Html\Body,
	N8G\Grass\Components\Html\Heading,
	N8G\Grass\Components\Html\Link,
	N8G\Grass\Components\Html\Meta,
	N8G\Grass\Components\Html\Script,
	N8G\Grass\Components\Html\Style,
	N8G\Grass\Components\Html\Title,
	N8G\Grass\Components\Html\Paragraph,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

/**
 * This class is used to build a page. The functionality to output the page is also held
 * within this class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Output
{
	/**
	 * The instance of this class.
	 * @var object
	 */
	private static $output;
	/**
	 * The theme class for holding all the theme information.
	 * @var object
	 */
	private $theme;
	/**
	 * The twig object to handle all template related functionality.
	 * @var object
	 */
	private $twig;
	/**
	 * The page data to be output.
	 * @var array
	 */
	private $data;
	/**
	 * The page type.
	 * @var string
	 */
	private $page;

	/**
	 * This is the default constructor. Only the page ID is passed. Within, new template,
	 * navigation and theme classes are instantiated.
	 *
	 * @param string|integer $id The ID of the page. Either the name value or numberic ID.
	 */
	private function __construct($id)
	{
		//Parse args
		$args = explode('/', $id);
		$id   = $args[0];
		unset($args[0]);

		//Get twig instance
		$this->twig = Twig::init();
		//Get the theme
		$this->theme = Theme::init();
		//Get the page type
		$this->page = $this->getPageType(is_numeric($id) ? intval($id) : strtolower(str_replace(' ', '-', $id)), $args);

		//Get the page data from the DB
		$this->data = array(
			'meta'		=>	array()
		);

		//Build page sections
		$this->buildHead();
	}

// Public Functions

	/**
	 * This function generates the single instance of the page. As this class is treated as a
	 * singlton class, this is the function that will return the class.
	 *
	 * @param  string|integer $id The ID of the page. Either the name value or numberic ID.
	 * @return object             An instance of this class.
	 */
	public static function init($id = 1)
	{
		Log::debug('Initilising Page object');

		//Check for instance of class
		if (!isset(self::$output)) {
			self::$output = new self($id);
		}
		//Return this class
		return self::$output;
	}

	/**
	 * This function outputs the fully rendered HTML page.
	 *
	 * @return void
	 */
	public function render()
	{
		//Render page elements
		$this->data['header']	= $this->renderHeader();
		$this->data['page']		= $this->renderContent();
		$this->data['footer']	= $this->renderFooter();

		//Load the page template
		$template = $this->twig->loadTemplate(VIEWS_DIR . 'page.tmpl');

		return $this->twig->render($template, array_merge(array('base_url' => Config::getUrl()), $this->data, $this->page->getData()));
	}

	/**
	 * This function is used to add an additional style sheet to the page.
	 *
	 * @param string $file The path to the new style sheet
	 */
	public function addStylesheet($file)
	{
		//Add file to styles array
		array_push($this->data['style'], new Link(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $file)));
	}

	/**
	 * This function is used to add an additional script file to the page.
	 *
	 * @param string $file The path to the new script file
	 */
	public function addScript($file)
	{
		//Add file to script array
		array_push($this->data['script'], new Script(array('async' => '', 'type' => 'text/javascript', 'href' => $file)));
	}

// Private Functions

	/**
	 * This function gets the page type and then returns the relevant class.
	 *
	 * @param  integer|string $id   The ID of the page
	 * @param  array          $args Any arguments to be passed to the page
	 * @return object               The relevant page type object
	 */
	private function getPageType($id, $args)
	{
		Log::debug(sprintf('Getting page type for page: %s', $id));

		//Get the page type
		$type = $this->getPageTypeData($id);

		//Check for child pages
		$type = $this->checkChildPage($type, $id, $args);

		Log::notice(sprintf('The page type is: %s', $type['type']));

		//Create the page object
		$class = sprintf('N8G\Grass\Display\Pages\%s', str_replace(' ', '', ucwords($type['type'])));
		return new $class($type['id'], $type['args']);
	}

	/**
	 * This function is used to check to see if the page that is requested is a child page. This
	 * is a consiquence of implementing pretty URLs. The function takes the parent page type, the
	 * ID of the parent and the arguments that have been passed to the page. The page variables
	 * are passed back in the form of an array.
	 *
	 * @param  string $type The parent page type
	 * @param  string $id   The ID of the parent
	 * @param  array  $args The array of arguments passed to the page
	 * @return array        An array with the page type, new page ID and the new page args
	 */
	private function checkChildPage($type, $id, $args)
	{
		//Create return array
		$page = array(
			'type'	=>	$type,
			'id'	=>	$id,
			'args'	=>	$args
		);

		//Check for blog post
		if ($type === 'blog' && isset($args[1]) && !in_array($args[1], Config::getBlogFilters())) {
			//Check if a post has been specified
			if ($this->checkForPost($id, $args[1])) {
				//Change page return array
				$page['type'] = 'post';
				$page['id']   = $args[1];
				$page['args'] = array();
			}
		}

		//Return page data
		return $page;
	}

	/**
	 * This function is used to build up all the page header data. This includes all the meta data,
	 * as well as other fundamentals such as language and favicon.
	 *
	 * @return void
	 */
	private function buildHead()
	{
		//Add mandatory data
		$this->data['favicon'] = Config::inConfig('favicon') ? Config::getItem('favicon') : null;
		$this->data['language'] = Config::inConfig('language') ? Config::getItem('language') : 'en';

		//Get the theme head data
		$this->data = array_merge($this->data, $this->theme->getHeadData());

		//Meta data to look for
		$meta = array('keywords', 'description', 'subject', 'revised', 'summary', 'copyright', 'url', 'author', 'designer');
		//Build meta data
		foreach ($meta as $data) {
			if (Config::inConfig('meta-' . $data)) {
				array_push($this->data['meta'], new Meta(array('name' => $data, 'content' => Config::getItem('meta-' . $data))));
			}
		}
	}

	/**
	 * This function is used to render the page header into HTML. It is then passed into the data
	 * array where it can be inserted into the page as HTML.
	 *
	 * @return void
	 */
	private function renderHeader()
	{
		$template = $this->twig->loadTemplate(sprintf('%sheader.tmpl', $this->theme->getPath()));
		return $this->twig->render($template, $this->page->getData());
	}

	/**
	 * This function is used to render the page content into HTML. It is then passed into the data
	 * array where it can be inserted into the page as HTML.
	 *
	 * @return void
	 */
	private function renderContent()
	{
		$template = $this->twig->loadTemplate(sprintf('%s%s.tmpl', $this->theme->getPath(), $this->page->getTemplateName()));
		return $this->twig->render($template, $this->page->getData());
	}

	/**
	 * This function is used to render the page footer into HTML. It is then passed into the data
	 * array where it can be inserted into the page as HTML.
	 *
	 * @return void
	 */
	private function renderFooter()
	{
		$template = $this->twig->loadTemplate(sprintf('%sfooter.tmpl', $this->theme->getPath()));
		return $this->twig->render($template, $this->page->getData());
	}

	/**
	 * This function gets the page type data for a specific page. The ID of the specific page is passed and the relevant data from the database is retrieved. The page type is then returned.
	 *
	 * @param  integer|string $id The ID of the page to request
	 * @return string             The page type.
	 */
	private function getPageTypeData($id)
	{
		//Get the data from the DB
		$data = Database::execProcedure('GetPageType', array('page' => $id));
		return $data[0]['type'];
	}

	private function checkForPost($blog, $post)
	{
		//Check for post in the DB
		$data = Database::execProcedure('CheckPagePost', array('blog' => $blog, 'post' => $post));
		return count($data) === 1;
	}
}