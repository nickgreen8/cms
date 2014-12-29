<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	Utils\Log;

/**
 * This class is has been created for the simple HTMl page break (br) tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class PageBreak extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array();

	/**
	 * Default constructor for the class.
	 *
	 * @param string $id         The ID of the element.
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($id = NULL, $attributes = array())
	{
		Log::info('Initilising page break element');
		$this->data = array(
			'name'	=>	'page break',
			'tag'	=>	'br'
		);
		parent::__construct(NULL, $id, array(), $attributes);
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
		Log::info('Converting page break object to HTML');

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
}