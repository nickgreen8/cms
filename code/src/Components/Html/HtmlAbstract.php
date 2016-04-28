<?php
namespace N8G\Grass\Components\Html;

use N8G\Grass\Components\Component,
	N8G\Grass\Exceptions\Components\ComponentMissingRequiredAttributesException,
	N8G\Grass\Exceptions\Components\AttributeInvalidException,
	N8G\Grass\Exceptions\Components\ElementInvalidException,
	N8G\Utils\Log;

/**
 * This is an abstract class that should be extended by all HTML object elements. All methods
 * that should be required have been specified in the component interface and are implemented
 * within this class. If there is anything that is missing, or a method does not fit into the
 * pattern, it can be overloaded within its class.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
abstract class HtmlAbstract implements Component
{
	/**
	 * A small array of data about the element. Including the name and tag
	 * @var array
	 */
	protected $data;

	/**
	 * OPTIONAL. The ID of the element.
	 * @var string
	 */
	protected $id;

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
	 * An array of types and elements that are accepted when adding an element to
	 * this component.
	 * @var array
	 */
	protected $acceptedElements = array('types' => array('string', 'int'), 'elements' => array());

	/**
	 * Element container
	 * @var object
	 */
	protected $container;

	/**
	 * Default constructor for the class.
	 *
	 * @param mixed $content    The content of the class. Can be string or an array.
	 * @param string $id        The ID of the element.
	 * @param array $elements   Array of other elements making up the overall element
	 * @param array $attributes Array of element attributes
	 */
	public function __construct($container, $content, $id, $elements, $attributes)
	{
		$this->container = $container;

		$container->get('log')->info(ucwords($this->data['name']) . ' element created');

		$this->setId($id);
		$this->setElements($elements);
		$this->setContent($content);
		$this->setAttributes($attributes);

		//Populate default accepted elements array
		$this->acceptedElements['elements'] = array(
			'Anchor',
			'Article',
			'Body',
			'Div',
			'Fieldset',
			'Footer',
			'Form',
			'Button',
			'Head',
			'Header',
			'Heading',
			'HorizontalRule',
			'Image',
			'Input',
			'Link',
			'ListItem',
			'Meta',
			'Option',
			'OrderedList',
			'PageBreak',
			'Paragraph',
			'Script',
			'Section',
			'Select',
			'Span',
			'Style',
			'Table',
			'TableBody',
			'TableCell',
			'TableHeader',
			'TableHeading',
			'TableRow',
			'Title',
			'UnorderedList'
		);
	}

	/**
	 * This function is called to get a textual representation of the compnent. Nothing
	 * is passed to the function and a string representing the element is returned.
	 *
	 * @return string
	 */
	public function toString()
	{
		$this->container->get('log')->info(sprintf('Converting %s object to string', $this->data['name']));

		$str = ucwords($this->data['name']) . ' element\n\r';

		if ($this->id !== NULL) {
			$str .= '    ID: ' . $this->id . '.\n\r';
		}

		if (count($this->elements) > 0) {
			$str .= '    Elements:\n\r';
			foreach ($this->elements as $element) {
				if (is_object($element)) {
					$str .= $element->toString();
				} else {
					$str .= $element;
				}
			}
			$str .= '\n\r';
		}

		if ($this->content !== NULL || (is_array($this->content) && count($this->content) > 0)) {
			$str .= '    Content: ';
			if (is_array($this->content)){
				foreach ($this->content as $item) {
					$str .= '\n\r';
					if (is_object($item)) {
						$str .= $item->toString();
					} else {
						$str .= $item;
					}

				}
			} elseif (is_object($this->content)) {
				$str .= $this->content->toString();
			} else {
				$str .= $this->content;
			}
			$str .= '\n\r';
		}

		if (count($this->attributes) > 0) {
			$str .= '    Attributes: ';
			foreach ($this->attributes as $key => $val) {
				$str .= $key . '=' . (is_array($val) ? implode(', ', $val) : $val) . ', ';
			}
			$str = trim($str, ', ');
		}

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
		$this->container->get('log')->info(sprintf('Converting %s object to HTML', $this->data['name']));

		$html = '<' . $this->data['tag'];

		//Check for an ID
		if ($this->id !== null && trim($this->id) !== '') {
			$html .= ' id="' . str_replace(' ', '_', $this->id) . '"';
		}

		//Check the element for reqired attributes
		$this->validateAttributes();
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
			} elseif (is_array($this->content)) {
				foreach ($this->content as $element) {
					//Check if the content is just a sub class
					if (is_object($element)) {
						$html .= $element->toHtml();
					} else {
						$html .= $element;
					}
				}
			} else {
				$html .= $this->content;
			}
		}

		return $html . '</' . $this->data['tag'] . '>';
	}

	/**
	 * This function is used to check that any required parameters were set in the
	 * element.
	 *
	 * @return bool Indicates whether there was an error or not
	 */
	protected function validateAttributes()
	{
		//Missing attributes array
		$missing = array();

		//Check to see if an ID is required
		if (in_array('id', $this->reqAtts) && (!isset($this->id) || trim($this->id) === '')) {
			array_push($missing, 'ID');
		}

		//Check each reqired element
		foreach ($this->reqAtts as $val) {
			if ($val !== 'id' && !array_key_exists($val, $this->attributes)) {
				array_push($missing, ucwords($val));
			}
		}

		//Check for error
		try {
			if (count($missing) > 0) {
				throw new ComponentMissingRequiredAttributesException(sprintf('%s element missing the following attributes: %s', ucwords(get_class($this)), implode(', ', $missing)));
			}
			return true;
		} catch (ComponentMissingRequiredAttributesException $e) {
			return false;
		}
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
		if ($this->id !== $id && !in_array(trim($id), array('', null))) {
			$this->container->get('log')->info(sprintf('Setting ID to %s', $id));
			$this->id = $id;
		}
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
		if ($this->elements = $elements) {
			$this->container->get('log')->info(sprintf('Setting the elements list for %s%s', $this->data['name'], $this->id !== '' && $this->id !== null ? ' (' . $this->id . ')' : ''));

			//Check each element
			foreach ($elements as $key => $e) {
				if (!$this->checkElement($e)) {
					//Add new element
					unset($elements[$key]);
				}
			}
			$this->elements = $elements;
		}

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
		if ($this->content !== $content) {
			$this->container->get('log')->info(sprintf('Setting the content of %s%s', $this->data['name'], $this->id !== '' && $this->id !== null ? ' (' . $this->id . ')' : ''));
			$this->content = $content;
		}
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
		if ($this->attributes !== $attributes) {
			$this->container->get('log')->info(
				sprintf(
					'Setting the attributes of %s%s',
					$this->data['name'],
					$this->id !== '' && $this->id !== null ? ' (' . $this->id . ')' : ''
				)
			);
			$this->attributes = $attributes;
		}
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
		$this->container->get('log')->info(sprintf('Adding element: %s', is_object($element) ? $element->toString() : $element));

		if (!$this->checkElement($element)) {
			$this->container->get('log')->notice('The element cannot be added');
			return;
		}

		//Check for content
		if ($this->getContent() !== NULL) {
			if (is_array($this->getContent())) {
				$this->elements = array_merge($this->elements, $this->getContent());
			} else {
				array_push($this->elements, $this->getContent());
			}
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
		$this->container->get('log')->info(sprintf('Adding attribute: %s = %s', key($attribute), implode(', ', $attribute)));

		try {
			//Check for a key as well as a value
			if (is_numeric(key($attribute))) {
				throw new AttributeInvalidException(sprintf('Attribute has no key%s', isset($attribute) ? ' (' . implode(', ', $attribute) . ')' : ''));
			}

			$this->attributes = array_merge($this->attributes, $attribute);
		} catch (AttributeInvalidException $e) {}

		return $this->attributes;
	}

	/**
	 * This function is used to check that an element is valid when attempting to add it to a HTML
	 * element. The functions takes a component class and will return a boolean value indicating
	 * whether the component can be added or not.
	 *
	 * @param  object $element The class object for the component
	 * @return boolean
	 */
	protected function checkElement($element)
	{
		$this->container->get('log')->info('Checking element');

		//Check the class type
		if (get_parent_class($element) === 'N8G\Grass\Components\Html\HtmlAbstract') {
			//Check the type
			if (is_string($element) && !in_array($this->acceptedElements['types'], 'string')) {
				throw new ElementInvalidException('Strings may not be added to this element');
			} elseif (is_int($element) && !in_array($this->acceptedElements['types'], 'int')) {
				throw new ElementInvalidException('Integer values cannot be added to this element');
			} elseif (!in_array(str_replace('N8G\Grass\Components\Html\\', '', get_class($element)), $this->acceptedElements['elements'])) {
				throw new ElementInvalidException(sprintf('This element could not be added because there is a restriction. %s cannot contatin a %s.', ucwords($this->data['name']), str_replace('N8G\Grass\Components\Html\\', '', get_class($element))));
			}
		}

		return true;
	}
}