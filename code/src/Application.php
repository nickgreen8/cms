<?php
namespace N8G\Grass;

/**
 * This class is used to build a page. The functionality to output the page is also held within this class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Application
{
	/**
	 * The page template
	 * @var object
	 */
	private $page;
	/**
	 * Array of page data
	 * @var array
	 */
	private $data = array();
	/**
	 * Application container reference.
	 * @var object
	 */
	private $container;

	/**
	 * Default constructor.
	 *
	 * @param object $container An instance of the container.
	 */
	public function __construct(&$container)
	{
		//Set the container
		$this->container = &$container;
	}

	/**
	 * Build all the data for the page. This data will then be passed into the template.
	 *
	 * @return void
	 */
	public function build($id)
	{
		//Parse args
		$args = explode('/', $id);
		$id   = $args[0];
		unset($args[0]);

		//Get the page content area
		$this->page = $this->getPageType($id, $args);
		$this->page->build();

		//Add page data
		$this->buildPageData();
	}

	public function render()
	{
		//Load the template
		$template = $this->container->get('twig')->loadTemplate('views/page.tmpl');
		//Add page template
		$this->data['page'] = $this->page->render();

		return $this->container->get('twig')->render($template, $this->data);
	}

	public function renderError($exception)
	{
		//Reset the page
		$this->page = $this->container->get('page.error');
		$this->page->setArgs(array('exception' => $exception));
		$this->page->setId('Error');
		$this->page->build();

		//Ensure all the required data is set
		$this->buildPageData($exception->getCode());

		//Render the page
		return $this->render();
	}

// Private methods

	private function buildPageData($error = false)
	{
		$this->data['domain']           = $this->container->get('config')->options->url;
		$this->data['settings']         = $this->container->get('theme')->getPageSettings();
		$this->data['loggedIn']         = isset($_SESSION['ng_login']);
		$this->data['theme']            = $this->container->get('config')->theme->active;
		$this->data['themeDirectory']   = $this->container->get('theme')->getDirectory();
		$this->data['copyright']        = $this->container->get('config')->options->copyright;
		$this->data['meta']             = $this->getMetaData();
		$this->data['style']            = $this->container->get('theme')->getFiles(
			$this->container->get('config')->extentions->style
		);
		$this->data['script']           = $this->container->get('theme')->getFiles(
			$this->container->get('config')->extentions->script
		);
		$this->data['language']         = $this->container->get('config')->options->language;
		$this->data['favicon']          = $this->container->get('config')->options->favicon;
		$this->data['headerNavigation'] = $this->container->get('navigation')->buildNavHierachy();
		$this->data['footerNavigation'] = $this->container->get('navigation')->buildNavHierachy(-1);
		$this->data['title'] = $this->page->buildTitle();

		if ($error) {
			$this->data['name'] = 'error';
			$this->data['type'] = 'error';
		} else {
			$this->data['name'] = $this->container->get('page-name');
			$this->data['type'] = $this->container->get('page-type');
		}
	}

	/**
	 * Formats all the meta data held within the config into an array of data that can be rendered in the template.
	 *
	 * @return array The formatted meta data.
	 */
	private function getMetaData()
	{
		//Create array of data
		$meta = array(
			array(
				'name' => 'language',
				'content' => $this->container->get('config')->options->language,
			),
			array(
				'name' => 'domain',
				'content' => $this->container->get('config')->options->url,
			),
			array(
				'name' => 'copyright',
				'content' => sprintf(
					'© copyright %s. All rights reserved.',
					$this->container->get('config')->options->copyright
				),
			),
			array(
				'name' => 'revised',
				'content' => 'Sat 4th Jun 2016',	// Should update with platform updates
			),
			array(
				'name' => 'author',
				'content' => 'Nick Green, nick-green@live.co.uk, www.nick8geen.co.uk',	// Should be pulled from my site through an API
			),
			array(
				'name' => 'designer',
				'content' => 'Nick Green, nick-green@live.co.uk, www.nick8geen.co.uk'	// Should be pulled from my site through an API
			)
		);

		//Format the meta data
		foreach($this->container->get('config')->meta as $key => $value) {
			//Append data to array
			array_push(
				$meta,
				array(
					'name'    => $key,
					'content' => $value
				)
			);
		}

		//Return all formatted meta data
		return $meta;
	}

// Getters

    /**
     * Gets the value of pageId.
     *
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

// Setters

    /**
     * Sets the value of page.
     *
     * @param  mixed $page the page id
     * @return self
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Sets the value of data.
     *
     * @param  mixed $data the data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

// Private functions

	/**
	 * This function gets the page type and then returns the relevant class.
	 *
	 * @param  integer|string $id   The ID of the page
	 * @param  array          $args Any arguments to be passed to the page
	 * @return object               The relevant page type object
	 */
	private function getPageType($id, $args)
	{
		$this->container->get('logger')->debug(sprintf('Getting page type for page: %s', $id));

		//Get the page type
		$type = $this->getPageTypeData($id);

		//Check for child pages
		$type = $this->checkChildPage($type, $id, $args);

		$this->container->get('logger')->notice(sprintf('The page type is: %s', $type['type']));

		//Return page object
		$class = $this->container->get(sprintf('page.%s', $type['type']));
		$class->setId($type['id']);
		$class->setArgs($type['args']);
		return $class;
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
		if (
			$type === 'blog' &&
			isset($args[1])
			&& !in_array(
				$args[1], 
				$this->container->get('config')->pages->blog->filters
			)
		) {
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
	 * This function gets the page type data for a specific page. The ID of the specific page is passed and the relevant data from the database is retrieved. The page type is then returned.
	 *
	 * @param  integer|string $id The ID of the page to request
	 * @return string             The page type.
	 */
	private function getPageTypeData($id)
	{
		//Get the data from the DB
		$data = $this->container->get('db')->execProcedure('GetPageType', array('page' => $id));
		return $data[0]['type'];
	}

	private function checkForPost($blog, $post)
	{
		//Check for post in the DB
		$data = $this->container->get('db')->execProcedure('CheckPagePost', array('blog' => $blog, 'post' => $post));
		return count($data) === 1;
	}
}