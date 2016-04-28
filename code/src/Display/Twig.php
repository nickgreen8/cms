<?php
namespace N8G\Grass\Display;

use N8G\Grass\Exceptions\Display\TwigException,
	N8G\Utils\Log;

/**
 * This class is a helper class for the templating system that is being implemented. It holds
 * some additional validation as well as encapsulating some more heavy logic functions. The
 * templating system that I am using is Twig. More details on it can be found at
 * http://twig.sensiolabs.org.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Twig
{
	/**
	 * An instance of this class
	 * @var object
	 */
	private static $instance;
	/**
	 * The twig enviroment instance
	 * @var object
	 */
	private $twig;
	/**
	 * Element container
	 * @var object
	 */
	private $container;

	/**
	 * Default constructor. Initilises a new instance of this class.
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

	/**
	 * This function is used to either create or serve up the one instance of this class.
	 * If it has already been created, a new instance is and returned.
	 *
	 * @return An instance of this class
	 */
	public function init()
	{
		$this->container->get('log')->info('New Twig instance created');

		//Build twig system
		$loader = new \Twig_Loader_Filesystem(ASSETS_DIR);
		$this->container->get('log')->debug('Twig file system specified');

		//Initialize Twig environment
		$this->twig = DEBUG === true ? new \Twig_Environment($loader) : new \Twig_Environment($loader, array('cache' => CACHE_DIR));
		$this->container->get('log')->debug('Twig enviroment loaded');
	}

	/**
	 * This function is used to get a template. It check that the template exists first and
	 * if it does not, an exception is thrown. If the templete exists, the twig load is
	 * returned.
	 *
	 * @return mixed
	 */
	public function loadTemplate($file)
	{
		$this->container->get('log')->info(sprintf('Loading new template: %s', $file));

		//Check the template exists
		if (!file_exists(ASSETS_DIR . $file)) {
			throw new TwigException(sprintf('The template (%s) cannot be found', $file));
		}

		//Load the template
		return $this->twig->loadTemplate($file);
	}

	/**
	 * This function is used to render and then output the page. The page is output within
	 * this function so nothing is returned. An array of data to be input into the page is
	 * passed in.
	 *
	 * @param  array Data to be input into the template
	 * @return void
	 */
	public function render($template, $data = array())
	{
		$this->container->get('log')->notice('Rendering template');
		$this->container->get('log')->info(sprintf('Render Data: %s', json_encode($data)));

		//Convert data to HTML
		foreach ($data as $key => $value) {
			//Check the option
			if (in_array($key, array('meta', 'style', 'script'))) {
				//Convert each element to html
				foreach ($value as $k => $v) {
					//Check for an object
					if (is_object($v)) {
						$data[$key][$k] = $v->toHtml();
					}
				}
			//Check the other values are objects
			} elseif (is_object($value)) {
				$data[$key] = $value->toHtml();
			}
		}

		//Render the page
		return $template->render($data);
	}
}