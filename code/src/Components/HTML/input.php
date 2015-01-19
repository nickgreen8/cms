<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	Exceptions\Components\ComponentMissingRequiredAttributesException,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl input tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Input extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array('type', 'name');

	/**
	 * A list of possible input types
	 * @var array
	 */
	private $types = array(
		'button',
		'checkbox',
		'color',
		'date',
		'datetime',
		'datetime-local',
		'email',
		'file',
		'hidden',
		'image',
		'month',
		'number',
		'password',
		'radio',
		'range',
		'reset',
		'search',
		'submit',
		'tel',
		'text',
		'time',
		'url',
		'week'
	);

	/**
	 * Default constructor for the class.
	 *
	 * @param string $id         The ID of the element.
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($id = NULL, $attributes = array())
	{
		Log::info('Initilising input element');
		$this->data = array(
			'name'	=>	'input',
			'tag'	=>	'input'
		);
		parent::__construct(null, $id, array(), $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array();
		$this->acceptedElements['elements'] = array();
	}

	/**
	 * This function is called to convert the element to HTML so that it can be outout
	 * on a web page. Nothing is passed to this function as it uses the elements array
	 * to render the HTML. This HTML is then returned. THIS IS OVERLOADED in this
	 * class due to the unique lack of any content.
	 *
	 * @return string
	 */
	public function toHtml()
	{
		Log::info('Converting input object to HTML');

		$html = '<' . $this->data['tag'];

		//Check for an ID
		if ($this->id !== null) {
			$html .= ' id="' . str_replace(' ', '_', $this->id) . '"';
		}

		//Check the element for reqired attributes
		$this->validateAttributes();
		//Add any attributes
		foreach ($this->attributes as $key => $val) {
			$html .= ' ' . $key . '="' . $val . '"';
		}

		return $html . ' />';
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
				throw new ComponentMissingRequiredAttributesException(sprintf('Input type is not in the following: %s', implode(', ', $this->types)));
			} catch (ComponentMissingRequiredAttributesException $e) {}
		}
		parent::validateAttributes();
	}
}