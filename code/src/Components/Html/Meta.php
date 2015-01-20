<?php
namespace N8G\Cms\Components\Html;

use N8G\Cms\Components\Html\HtmlAbstract,
	N8G\Utils\Log;

/**
 * This class is has been created for the simple HTMl meta tag.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class Meta extends HtmlAbstract
{
	/**
	 * A list of the required attributes for this element.
	 * @var array
	 */
	protected $reqAtts = array('name', 'content');

	/**
	 * Default constructor for the class.
	 *
	 * @param mixed  $content    The content of the class. Can be string or an array.
	 * @param string $id        The ID of the element.
	 * @param array  $elements   Array of other elements making up the overall element
	 * @param array  $attributes Array of element attributes
	 */
	public function __construct($attributes = array())
	{
		Log::info('Initilising meta element');
		$this->data = array(
			'name'	=>	'meta',
			'tag'	=>	'meta'
		);
		parent::__construct(NUll, NULL, array(), $attributes);

		//Populate default accepted elements array
		$this->acceptedElements['types'] = array();
		$this->acceptedElements['elements'] = array();
	}
}