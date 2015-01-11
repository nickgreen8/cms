<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	Utils\Log;

/**
 * This class is has been created for the simple HTMl script tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Script extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array();

	/**
	 * Default constructor for the class.
	 *
	 * @param mixed  $content    The content of the class. Can be string or an array.
	 * @param string $id         The ID of the element.
	 * @param array  $elements   Array of other elements making up the overall element
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($content = '', $attributes = array())
	{
		Log::info('Initilising script element');
		$this->data = array(
			'name'	=>	'script',
			'tag'	=>	'script'
		);
		parent::__construct($content, NULL, array(), $attributes);
	}
}