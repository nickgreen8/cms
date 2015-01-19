<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl divisional (div) tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Div extends HTMLAbstract
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
	 * @param string $id        The ID of the element.
	 * @param array  $elements   Array of other elements making up the overall element
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($content = NULL, $id = NULL, $elements = array(), $attributes = array())
	{
		Log::info('Initilising div element');
		$this->data = array(
			'name'	=>	'divisional tag',
			'tag'	=>	'div'
		);
		parent::__construct($content, $id, $elements, $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array();
		$this->acceptedElements['elements'] = array(
			'Anchor',
			'Article',
			'Div',
			'Footer',
			'Form',
			'Button',
			'Header',
			'Heading',
			'HorizontalRule',
			'Image',
			'OrderedList',
			'Paragraph',
			'Section',
			'Span',
			'Table',
			'UnorderedList'
		);
	}
}