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

use N8G\Grass\Display\Pages\Standard;

/**
 * This class is used to build a page. The functionality to output the page is also held
 * within this class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Page
{
	/**
	 * The instance of this class.
	 * @var object
	 */
	private static $page;
	/**
	 * The ID of the page
	 * @var string|integer
	 */
	private $id;
	/**
	 * The theme class for holding all the theme information.
	 * @var object
	 */
	private $theme;
	/**
	 * The navigation object.
	 * @var object
	 */
	private $navigation;
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
	private $type;

	/**
	 * This is the default constructor. Only the page ID is passed. Within, new template,
	 * navigation and theme classes are instantiated.
	 *
	 * @param string|integer $id The ID of the page. Either the name value or numberic ID.
	 */
	private function __construct($id)
	{
		//Set page ID
		$this->id = is_numeric($id) ? intval($id) : strtolower(str_replace(' ', '-', $id));

		//Get twig instance
		$this->twig = Twig::init();
		//Get the theme
		$this->theme = new Theme();
		//Create navigation object
		$this->navigation = new Navigation();

		//Get the page data from the DB
		$this->data = array(
			'meta'		=>	array()
		);
		$this->getData();

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
		if (!isset(self::$page)) {
			self::$page = new Page($id);
		}
		//Return this class
		return self::$page;
	}

	/**
	 * This function outputs the fully rendered HTML page.
	 *
	 * @return void
	 */
	public function render()
	{
		try {
			//Render page elements
			$this->renderHeader();
			$this->renderContent();
			$this->renderFooter();

			//Load the page template
			$template = $this->twig->loadTemplate(VIEWS_DIR . 'page.tmpl');

			echo $this->twig->render($template, $this->data);
		} catch (\Exception $e) {
			echo "render - GO TO ERROR PAGE";
		}
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
	 * This function gets the data related to the page. This is then used to build up the data
	 * array for everything that is to be input into the page. On render, all the data that has
	 * been prepared is output into the page.
	 *
	 * @return void
	 */
	private function getData()
	{
		//Get the data from the database
		$data = $this->getPageData($this->id);

		Log::debug(sprintf('Page name: %s', $data['name']));
		Log::debug(sprintf('Page type: %s', $data['type']));

		//Assign data to page array
		$this->data['name'] = $data['name'];
		// $this->data['title'] = $data['title'];
		$this->data['title'] = $this->buildTitle($data);

		//Create the page object
		$class = sprintf('N8G\Grass\Display\Pages\%s', str_replace(' ', '', ucwords($data['type'])));
		$this->type = new $class($data['content']);
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
		$settings = $this->theme->getSettings($this->type->getTemplateName());
		$template = $this->twig->loadTemplate($this->theme->getPath() . 'header.tmpl');
		$this->data['header'] = $this->twig->render($template, array_merge(
																	array(
																		'navigation' => $this->navigation->buildHeaderNavigation()
																	),
																	$settings
																)
		);
	}

	/**
	 * This function is used to render the page content into HTML. It is then passed into the data
	 * array where it can be inserted into the page as HTML.
	 *
	 * @return void
	 */
	private function renderContent()
	{
		$settings = $this->theme->getSettings($this->type->getTemplateName());
		$template = $this->twig->loadTemplate($this->theme->getPath() . $this->type->getTemplateName() . '.tmpl');
		$this->data['page'] = $this->twig->render($template, array_merge($this->type->getData(), $settings));
	}

	/**
	 * This function is used to render the page footer into HTML. It is then passed into the data
	 * array where it can be inserted into the page as HTML.
	 *
	 * @return void
	 */
	private function renderFooter()
	{
		$settings = $this->theme->getSettings($this->type->getTemplateName());
		$template = $this->twig->loadTemplate($this->theme->getPath() . 'footer.tmpl');
		$this->data['footer'] = $this->twig->render($template, array_merge(
																	array(
																		'copyright' => Config::getItem('copyright'),
																		'navigation' => $this->navigation->buildFooterNavigation()
																	),
																	$settings
																)
		);
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
			array_unshift($title, trim(Config::getItem('site-tag-line')));
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
		return implode(' | ', $title);
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
		$query = 'SELECT
						p.name,
						p.content,
						p.parent,
						t.name as type
					FROM
						page p LEFT JOIN PageType pt
							ON p.id = pt.page
						LEFT JOIN Type t
							ON pt.type = t.id
					WHERE
						%s';
		return Database::getArray(Database::query(sprintf($query, is_numeric($id) ? sprintf('p.id = %d', $id) : sprintf('LCASE(REPLACE(p.name, \' \', \'-\')) = \'%s\'', $id))));
	}
}