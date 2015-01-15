<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	Exceptions\Components\ComponentMissingRequiredAttributesException,
	Utils\Log;

/**
 * This class is has been created for the simple HTMl button tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Button extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array('type');

	/**
	 * A list of possible button types
	 * @var array
	 */
	private $types = array(
		'button',
		'submit',
		'reset'
	);

	/**
	 * Default constructor for the class.
	 *
	 * @param string $id         The ID of the element.
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($content = NULL, $id = NULL, $elements = array(), $attributes = array())
	{
		Log::info('Initilising button element');
		$this->data = array(
			'name'	=>	'button',
			'tag'	=>	'button'
		);
		parent::__construct($content, $id, $elements, $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array('string', 'int');
		$this->acceptedElements['elements'] = array(
			'Image',
			'Span'
		);
	}

	/**
	 * This function is used to check that any required parameters were set in the
	 * element. THIS IS OVERLOADED in this class to only allow certain values in
	 * certain attributes.
	 *
	 * @return bool Indicates whether there was an error or not
	 */
	protected function validateAttributes()
	{
		//Check for encryption type
		if (!isset($this->attributes['type']) || !in_array($this->attributes['type'], $this->types)) {
			//Check for error
			try {
				throw new ComponentMissingRequiredAttributesException(sprintf('Button type is not in the following: %s', implode(', ', $this->types)));
			} catch (ComponentMissingRequiredAttributesException $e) {}
		}
		parent::validateAttributes();
	}
}