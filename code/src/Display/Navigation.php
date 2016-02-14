<?php
namespace N8G\Grass\Display;

use N8G\Grass\Components\Html\Anchor,
	N8G\Grass\Components\Html\ListItem,
	N8G\Grass\Components\Html\UnorderedList,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

/**
 * This class is used to build and manipulate and build site navigation.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Navigation
{
	/**
	 * An array of options
	 * @var array
	 */
	private $options;
	/**
	 * The opject that will form the navigation
	 * @var Object
	 */
	private $navigation;

	/**
	 * Default constructor
	 * The one argument passed is the type of navigation to be built. This will
	 * dictate the building of the navigation as well as any features that should
	 * be added.
	 *
	 * @param int $type The type of navigation to be built.
	 */
	public function __construct($options = array())
	{
		Log::info('Creating navigation object');

		//Set the options
		$this->options = $options;
		//Create navigation HTML list object
		$this->navigation = new UnorderedList();
	}

	/**
	 * This function builds the main header navigation.
	 *
	 * @return string The HTML string to be input into the page template
	 */
	public function buildHeaderNavigation()
	{
		//Reset the current object
		$this->resetNavigation();

		//Get all pages and child pages
		$navigation = $this->buildNavHierachy(new UnorderedList());

		//Convert and return
		return $navigation->toHtml();
	}

	/**
	 * This function builds the main footer navigation.
	 *
	 * @return string The HTML string to be input into the page template
	 */
	public function buildFooterNavigation()
	{
		//Reset the current object
		$this->resetNavigation();

		//Get all pages and child pages
		$navigation = $this->buildNavHierachy(new UnorderedList(), -1);

		//Convert and return
		return $navigation->toHtml();
	}

	/**
	 * This function build the heirachy of navigation. If there are child pages, these
	 * will be added into the specified object so that it is all contained for when they
	 * are converted to HTML.
	 *
	 * @param  object $obj    The HTML object for the navigation to be built into
	 * @param  mixed  $parent The ID of the parent page
	 * @return object         The HTML object to be converted
	 */
	private function buildNavHierachy($obj, $parent = 0)
	{
		//Get all the pages
		$pages = $this->getPages($parent);

		//Itterate through pages
		foreach ($pages as $page) {
			//Check if current page
			$class = array();
			if (in_array(Config::getItem('page-id'), array($page['id'], $page['identifier']))) {
				$class['class'] = 'current-page';
			}

			//Create page link
			if ($page['type'] === 'login') {
				$li = $this->getLoginOption();
			} else {
				$li = $this->linkToObject($page['name'], sprintf('%s%s', Config::getItem('url'), $page['identifier']), $class);
			}

			//Check for child pages
			if ($page['children'] > 0) {
				//Add child pages
				$li->addElement($this->buildNavHierachy(new UnorderedList(), $page['id']));
			}

			//Add option to object
			$obj->addElement($li);
		}

		//Return the object
		return $obj;
	}

	/**
	 * This function is used to convert the name and the link to be added to
	 * navigation into HTML objects. These can then be used to build the navigation
	 * bar once it has been converted to HTML and output.
	 *
	 * @param  string $name The text to be output to the user
	 * @param  string $link The link to the page
	 * @return Object       ListItem object to be added to the navigation list.
	 */
	private function linkToObject($name = 'Click Here', $link = '#', array $atts = array())
	{
		Log::debug(sprintf('Creating navigation link to %s (%s)', $name, $link));

		return new ListItem(new Anchor(trim(ucwords($name)), null, array(), array_merge(array('href' => $link, 'title' => trim(ucwords($name))), $atts)));
	}

	/**
	 * This function is used to reset the navigation object
	 * @return void
	 */
	private function resetNavigation()
	{
		Log::notice('Resetting navigation object');

		//Empty the elements
		$this->navigation->setElements(array());
		//Empty the attributes
		$this->navigation->setAttributes(array());
	}

	/**
	 * This function is used to get the login or logout link. The login cookie is
	 * searched for and then used to determine whether the user is logged in or not.
	 *
	 * @return obj A list item object to be added
	 */
	private function getLoginOption()
	{
		log::info('Checking if the user is logged in');

		if (isset($_SESSION['ng_login'])) {
			//Create the logout link
			$link = $this->linkToObject('Logout', sprintf('%slogout', Config::getItem('url')));
		} else {
			//Create the login link
			$link = $this->linkToObject('Login', sprintf('%slogin', Config::getItem('url')));
		}

		//Return the link
		return $link;
	}

	/**
	 * This function gets all the pages under the specified parent
	 *
	 * @param  mixed  $parent The ID of the parent page
	 * @return object         The query object
	 */
	private function getPages($parent = 0)
	{
		//Get the pages
		$data = Database::execProcedure('GetNavigation', array('page' => $parent));
		return $data;
	}
}