<?php
namespace N8G\Cms\Components;

/**
 * This is the interface that MUST be implemented in each and all of the different components
 * that are implemented across the full CMS.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
interface Component
{
	/**
	 * This function is called to get a textual representation of the compnent. Nothing
	 * is passed to the function and a string representing the element is returned.
	 *
	 * @return string
	 */
	public function toString();

	/**
	 * This function is called to convert the element to HTML so that it can be outout
	 * on a web page. Nothing is passed to this function as it uses the elements array
	 * to render the HTML. This HTML is then returned.
	 *
	 * @return string
	 */
	public function toHtml();

	/**
	 * This function uses the content set in the class to check for embedded elements.
	 * Nothing is returned and nothing is passed to the function.
	 *
	 * @return void
	 */
	public function checkForElements();

	/**
	 * This function is used to return the ID of the element in case it is required.
	 * Nothing is passed and the ID of the component is returned.
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * This function is used to get the elements in the compnent. Nothing is passed and
	 * the array of elements is returned.
	 *
	 * @return array
	 */
	public function getElements();

	/**
	 * This function is used to get the content held within the component class. Nothing
	 * is passed and the content variable is returned.
	 *
	 * @return mixed
	 */
	public function getContent();

	/**
	 * This function is used to get the attributes held within the component class. Nothing
	 * is passed and the array of attributes are returned.
	 *
	 * @return mixed
	 */
	public function getAttributes();

	/**
	 * This function is used to reset the ID of the component. The new ID is passed to the
	 * function and that is then returned.
	 *
	 * @param string
	 */
	public function setId($id);

	/**
	 * This function is used to reset the elements of the component. The new elements are
	 * passed to the function and these are then returned.
	 *
	 * @param array
	 */
	public function setElements(array $elements);

	/**
	 * This function is used to reset the content of the component. The new content is passed
	 * to the function and that is then returned.
	 *
	 * @param mixed
	 */
	public function setContent($content);

	/**
	 * This function is used to reset the elements of the component. The new elements are
	 * passed to the function and these are then returned.
	 *
	 * @param array
	 */
	public function setAttributes($attributes);

	/**
	 * This function is used to add a new element to the elements array. The new element is
	 * passed and the full array is returned.
	 *
	 * @param array
	 */
	public function addElement($element);

	/**
	 * This function is used to add a new attribute to the attributes array. The new attribute is
	 * passed and the full array is returned.
	 *
	 * @param array
	 */
	public function addAttribute(array $attribute);
}