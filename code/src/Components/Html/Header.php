<?php
namespace Components\Html;

use Components\Html\HtmlAbstract,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl header tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Header extends HtmlAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array();

	/**
	 * Default constructor for the class.
	 *
	 * @param mixed $content    The content of the class. Can be string or an array.
	 * @param string $id        The ID of the element.
	 * @param array $elements   Array of other elements making up the overall element
	 * @param array $attributes Array of element attributes
	 */
	public function __construct($content = NULL, $id = NULL, $elements = array(), $attributes = array())
	{
		Log::info('Initilising header element');
		$this->data = array(
			'name'	=>	'header',
			'tag'	=>	'header'
		);
		parent::__construct($content, $id, $elements, $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array();
		$this->acceptedElements['elements'] = array(
			'Anchor',
			'Article',
			'Div',
			'Form',
			'Button',
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