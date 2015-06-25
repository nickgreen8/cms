<?php
namespace N8G\Grass\Display\Pages;

use N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

/**
 * This class is used to build a normal page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Blog extends PageAbstract
{
	/**
	 * Default constructor
	 *
	 * @param string $content Page content
	 */
	public function __construct($content)
	{
		Log::notice('Blog page created');

		//Set the template name
		$this->template = 'blog';

		//Call the parent constructor
		parent::__construct($content);

		//Build page content
		$this->build();
	}

// Public functions

// Private functions

	/**
	 * This function builds up the content for the page. Data is built into the page
	 * data array for display when the page is rendered.
	 *
	 * @return void
	 */
	private function build()
	{
		$posts = array(
			array(
				'id'		=>	1,
				'title'		=>	'Test',
				'rating'	=>	'90%',
				'date'		=>	'Thursday 25th June 2015, 07:17am',
				'author'	=>	'Nick Green',
				'post'		=>	$this->parseContent('This is a test')
			)
		);

		$this->addPageComponent('posts', $posts);
		$this->addPageComponent('monthFilter', array('Jun 2015', 'Jul 2015'));
	}
}