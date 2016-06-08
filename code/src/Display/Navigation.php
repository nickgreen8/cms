<?php
namespace N8G\Grass\Display;

/**
 * This class is used to build and manipulate and build site navigation.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Navigation
{
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

		$this->container->get('logger')->info('Creating navigation object');
	}

	/**
	 * This function build the heirachy of navigation. If there are child pages, these
	 * will be added into the specified object so that it is all contained for when they
	 * are converted to HTML.
	 *
	 * @param  mixed  $parent The ID of the parent page
	 * @return object         The HTML object to be converted
	 */
	public function buildNavHierachy($parent = 0)
	{
		//Create return array
		$links = array();

		//Get all the pages
		$pages = $this->getPages($parent);

		//Itterate through pages
		foreach ($pages as $page) {
			//Create link node
			$link = array();

			//Check if current page
			if (
				$this->container->keyExists('page-id') &&
				in_array($this->container->get('page-id'), array($page['id'], $page['identifier']))
			) {
				$link['current'] = true;
			}

			//Create page link
			if ($page['type'] === 'login') {
				$link = array_merge($link, $this->getLoginOption());
			} else {
				$link['label']   = $page['name'];
				$link['address'] = sprintf('/%s', $page['identifier']);
			}

			//Check for child pages
			if ($page['children'] > 0) {
				//Add child pages
				$link['children'] = $this->buildNavHierachy($page['id']);
			}

			//Add option to object
			array_push($links, $link);
		}

		//Return the object
		return $links;
	}

	/**
	 * This function is used to get the login or logout link. The login cookie is
	 * searched for and then used to determine whether the user is logged in or not.
	 *
	 * @return obj A list item object to be added
	 */
	private function getLoginOption()
	{
		$this->container->get('logger')->info('Checking if the user is logged in');

		if (isset($_SESSION['ng_login'])) {
			//Get the logout data
			return array('label' => 'Logout', 'address' => '/logout');
		} else {
			//Get the login data
			return array('label' => 'Login', 'address' => '/login');
		}
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
		$data = $this->container->get('db')->execProcedure('GetNavigation', array('page' => $parent));
		return $data;
	}
}