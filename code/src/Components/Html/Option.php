<?php
namespace N8G\Grass\Components\Html;

use N8G\Grass\Components\Html\HtmlAbstract,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl select option tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Option extends HtmlAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array('value');

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
		Log::info('Initilising option element');
		$this->data = array(
			'name'	=>	'combobox option',
			'tag'	=>	'option'
		);
		parent::__construct($content, $id, $elements, $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array('string', 'int');
		$this->acceptedElements['elements'] = array();
	}
}