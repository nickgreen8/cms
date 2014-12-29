<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	Utils\Log;

/**
 * This class is has been created for the simple HTMl p (paragraph) tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Paragraph extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array();

	/**
	 * Default constructor for the class.
	 *
	 * @param string $id        The ID of the element.
	 * @param mixed $content    The content of the class. Can be string or an array.
	 * @param array $elements   Array of other elements making up the overall element
	 * @param array $attributes Array of element attributes
	 */
	public function __construct($id = NULL, $content = NULL, $elements = array(), $attributes = array())
	{
		Log::info('Initilising paragraph element');
		$this->data = array(
			'name'	=>	'paragraph',
			'tag'	=>	'p'
		);
		parent::__construct($id, $content, $elements, $attributes);
	}
}