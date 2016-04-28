<?php
namespace N8G\Grass\Components\Html;

/**
 * This class is has been created for the simple HTMl ordered list (ol) tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class OrderedList extends HtmlAbstract
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
	public function __construct($container, $content = NULL, $id = NULL, $elements = array(), $attributes = array())
	{
		$container->get('log')->info('Initilising ol element');
		$this->data = array(
			'name'	=>	'ordered list',
			'tag'	=>	'ol'
		);
		parent::__construct($container, $content, $id, $elements, $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array();
		$this->acceptedElements['elements'] = array(
			'ListItem',
			'Span'
		);
	}
}