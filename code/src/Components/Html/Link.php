<?php
namespace Components\HTML;

use Components\HTML\HTMLAbstract,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl link tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Link extends HTMLAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array('rel', 'type', 'href');

	/**
	 * Default constructor for the class.
	 *
	 * @param mixed  $content    The content of the class. Can be string or an array.
	 * @param string $id         The ID of the element.
	 * @param array  $elements   Array of other elements making up the overall element
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($attributes = array())
	{
		Log::info('Initilising link element');
		$this->data = array(
			'name'	=>	'link',
			'tag'	=>	'link'
		);
		parent::__construct(NUll, NULL, array(), $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array();
		$this->acceptedElements['elements'] = array();
	}
}