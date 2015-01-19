<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl h1-h7 (heading) tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Heading extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array();

	/**
	 * Default constructor for the class.
	 *
	 * @param int    $level      The level of the heading (eg. 1-7)
	 * @param mixed  $content    The content of the class. Can be string or an array.
	 * @param string $id         The ID of the element.
	 * @param array  $elements   Array of other elements making up the overall element
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($level, $content = NULL, $id = NULL, $elements = array(), $attributes = array())
	{
		Log::info(sprintf('Initilising heading (h%n) element', $level));
		$this->data = array(
			'name'	=>	'heading',
			'tag'	=>	'h' . $level
		);
		parent::__construct($content, $id, $elements, $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array('string', 'int');
		$this->acceptedElements['elements'] = array(
			'Anchor',
			'Image',
			'PageBreak',
			'Span'
		);
	}
}