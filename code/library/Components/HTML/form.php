<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	Exceptions\Components\ComponentMissingRequiredAttributesException,
	Utils\Log;

/**
 * This class is has been created for the simple HTMl form tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Form extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array('method', 'action', 'enctype');

	/**
	 * A list of possible form types
	 * @var array
	 */
	private $types = array(
		'multipart/form-data',
		'application/x-www-form-urlencoded',
		'text/plain'
	);

	/**
	 * Default constructor for the class.
	 *
	 * @param mixed  $content    The content of the class. Can be string or an array.
	 * @param string $id         The ID of the element.
	 * @param array  $elements   Array of other elements making up the overall element
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($content = NULL, $id = NULL, $elements = array(), $attributes = array())
	{
		Log::info('Initilising form element');
		$this->data = array(
			'name'	=>	'form',
			'tag'	=>	'form'
		);
		parent::__construct($content, $id, $elements, $attributes);
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
		if (!isset($this->attributes['enctype']) || !in_array($this->attributes['enctype'], $this->types)) {
			//Check for error
			try {
				throw new ComponentMissingRequiredAttributesException(sprintf('Form encryption type is not in: %s', implode(', ', $this->types)));
			} catch (ComponentMissingRequiredAttributesException $e) {}
		}
		parent::validateAttributes();
	}
}