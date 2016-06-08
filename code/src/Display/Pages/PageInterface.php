<?php
namespace N8G\Grass\Display\Pages;

/**
 * This interface defines all functions that must be present in the different pages. These are used to
 * display that details in the them templates.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
interface PageInterface
{
	/**
	 * This function gets the name of the template that should be used.
	 *
	 * @return string The filename of the template
	 */
	public function getTemplateName();

	/**
	 * This function returns the data array of the page. This will be an associative array
	 * that will have the keys that match the template.
	 *
	 * @return array The data array
	 */
	public function getData();

	/**
	 * This function renders the page content. This will be the partial template that is specific for the page type.
	 *
	 * @return string The rendered page section to be injected.
	 */
	public function render();
}