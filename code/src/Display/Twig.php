<?php
namespace N8G\Grass\Display;

use N8G\Grass\Exceptions\Display\TwigException;

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
	 * Application container reference.
	 * @var object
	 */
	private $container;
	/**
	 * The twig enviroment instance
	 * @var object
	 */
	private $twig;

	/**
	 * Default constructor. Initilises a new instance of this class.
	 */
	public function __construct(&$container)
	{
		//Set the container
		$this->container = &$container;

		$this->container->get('logger')->info('New Twig instance created');

		//Build twig system
		$loader = new \Twig_Loader_Filesystem($this->container->get('config')->theme->assets);
		$this->container->get('logger')->debug('Twig file system specified');

		//Initialize Twig environment
		$this->twig = (isset($this->container->get('config')->debug) && $this->container->get('config')->debug === true)
			? new \Twig_Environment($loader)
			: new \Twig_Environment($loader, array('cache' => $this->container->get('config')->cache));
		$this->container->get('logger')->debug('Twig enviroment loaded');
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
		$this->container->get('logger')->info(sprintf('Loading new template: %s', $file));

		//Check the template exists
		if (!file_exists(sprintf('%s%s', $this->container->get('config')->theme->assets, $file))) {
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
		$this->container->get('logger')->notice('Rendering template');
		$this->container->get('logger')->info(sprintf('Render Data: %s', json_encode($data)));

		//Render the page
		return $template->render($data);
	}
}