<?php
namespace Components\HTML;

use Components\Component,
	Utils\Log;

abstract class HTMLAbstract implements Component
{
	/**
	 * The ID of the element.
	 * @var string
	 */
	protected $id;

	/**
	 * A small array of data about the element. Including the name and tag
	 * @var array
	 */
	protected $data;

	/**
	 * OPTIONAL. An array of elements that make up the overall element.
	 * @var array
	 */
	protected $elements;

	/**
	 * OPTIONAL. Usually a string with the content for the element.
	 * @var mixed
	 */
	protected $content;

	/**
	 * OPTIONAL. An array of attrbutes for the elements. This will include classes.
	 * @var array
	 */
	protected $attributes;

	/**
	 * Default constructor for the class.
	 *
	 * @param string $id        The ID of the element.
	 * @param mixed $content    The content of the class. Can be string or an array.
	 * @param array $elements   Array of other elements making up the overall element
	 * @param array $attributes Array of element attributes
	 */
	public function __construct($id, $content, $elements, $attributes)
	{
		Log::info(ucwords($this->data['name']) . ' element created');

		$this->setId($id);
		$this->setElements($elements);
		$this->setContent($content);
		$this->setAttributes($attributes);

		$this->checkForElements();
	}

	/**
	 * This function is called to get a textual representation of the compnent. Nothing
	 * is passed to the function and a string representing the element is returned.
	 *
	 * @return string
	 */
	public function toString()
	{
		Log::info('Converting ' . $this->data['name'] . ' object to string');

		$str = 'ID: ' . $this->id . '.';
		$str .= ' Elements: ' . (is_array($this->elements) ? implode(', ', $this->elements) : $this->elements) . '.';
		$str .= ' Content: ' . (is_array($this->content) ? implode(', ', $this->content) : $this->content) . '.';
		$str .= ' Attributes: ' . (is_array($this->attributes) ? implode(', ', $this->attributes) : $this->attributes) . '.';

		return $str;
	}

	/**
	 * This function is called to convert the element to HTML so that it can be outout
	 * on a web page. Nothing is passed to this function as it uses the elements array
	 * to render the HTML. This HTML is then returned.
	 *
	 * @return string
	 */
	public function toHtml()
	{
		Log::info('Converting paragraph object to HTML');

		$html = '<' . $this->data['tag'];

		//Check for an ID
		if ($this->id !== null) {
			$html .= ' id="' . str_replace(' ', '_', $this->id) . '"';
		}

		//Add any attributes
		foreach ($this->attributes as $key => $val) {
			$html .= ' ' . $key . '="' . $val . '"';
		}

		$html .= '>';

		//Check for internal elements
		if (is_array($this->elements) && count($this->elements) > 0) {


			//Build the HTML
			foreach ($this->elements as $element) {
				//Check if the content is just a sub class
				if (is_object($element)) {
					$html .= $element->toHtml();
				} else {
					$html .= $element;
				}
			}
		} else {
			//Check if the content is just a sub class
			if (is_object($this->content)) {
				$html .= $this->content->toHtml();
			} else {
				$html .= $this->content;
			}
		}

		return $html . '</' . $this->data['tag'] . '>';
	}

	/**
	 * This function uses the content set in the class to check for embedded elements.
	 * Nothing is returned and nothing is passed to the function.
	 *
	 * @return void
	 */
	public function checkForElements()
	{

	}

	/**
	 * This function is used to return the ID of the element in case it is required.
	 * Nothing is passed and the ID of the component is returned.
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * This function is used to get the elements in the compnent. Nothing is passed and
	 * the array of elements is returned.
	 *
	 * @return array
	 */
	public function getElements()
	{
		return $this->elements;
	}

	/**
	 * This function is used to get the content held within the component class. Nothing
	 * is passed and the content variable is returned.
	 *
	 * @return mixed
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * This function is used to get the attributes held within the component class. Nothing
	 * is passed and the array of attributes are returned.
	 *
	 * @return mixed
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * This function is used to reset the ID of the component. The new ID is passed to the
	 * function and that is then returned.
	 *
	 * @param string
	 */
	public function setId($id)
	{
		Log::notice(sprintf('Setting ID to %s', $id));
		$this->id = $id;
		return $this->id;
	}

	/**
	 * This function is used to reset the elements of the component. The new elements are
	 * passed to the function and these are then returned.
	 *
	 * @param array
	 */
	public function setElements(array $elements)
	{
		Log::notice(sprintf('Setting the elements list for %s%s', $this->data['name'], $this->id !== '' && $this->id !== null ? ' (' . $this->id . ')' : ''));
		$this->elements = $elements;
		return $this->elements;
	}

	/**
	 * This function is used to reset the content of the component. The new content is passed
	 * to the function and that is then returned.
	 *
	 * @param mixed
	 */
	public function setContent($content)
	{
		Log::notice(sprintf('Setting the content of %s%s', $this->data['name'], $this->id !== '' && $this->id !== null ? ' (' . $this->id . ')' : ''));
		$this->content = $content;
		return $this->content;
	}

	/**
	 * This function is used to reset the elements of the component. The new elements are
	 * passed to the function and these are then returned.
	 *
	 * @param array
	 */
	public function setAttributes($attributes)
	{
		Log::notice(sprintf('Setting the attributes of %s%s', $this->data['name'], $this->id !== '' && $this->id !== null ? ' (' . $this->id . ')' : ''));
		$this->attributes = $attributes;
		return $this->attributes;
	}

	/**
	 * This function is used to add a new element to the elements array. The new element is
	 * passed and the full array is returned.
	 *
	 * @param object
	 */
	public function addElement($element)
	{
		Log::notice(sprintf('Adding element: %s', is_object($element) ? $element->toString() : $element));

		//Check for content
		if ($this->getContent() !== NULL) {
			array_push($this->elements, $this->getContent());
			$this->setContent(NULL);
		}

		array_push($this->elements, $element);
		return $this->elements;
	}

	/**
	 * This function is used to add a new attribute to the attributes array. The new attribute is
	 * passed and the full array is returned.
	 *
	 * @param array
	 */
	public function addAttribute(array $attribute)
	{
		Log::notice(sprintf('Adding attribute: %s = %s', key($attribute), implode(', ', $attribute)));
		$this->attributes = array_merge($this->attributes, $attribute);
		return $this->attributes;
	}
}