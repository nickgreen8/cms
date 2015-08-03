<?php
namespace N8G\Grass\Display\Pages;

use N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

/**
 * This class is used to build a post page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Post extends PageAbstract
{
	/**
	 * The ID of the parent blog
	 * @var int
	 */
	private $parent;

	/**
	 * Default constructor
	 *
	 * @param string     $id   Page ID
	 * @param array|null $args Page arguments
	 */
	public function __construct($id, $args)
	{
		Log::notice('Post page created');

		//Set the template name
		$this->template = 'post';

		//Call the parent constructor
		parent::__construct($id, $args);
	}

// Public functions

// Protected functions

	/**
	 * This function builds up the content for the page. Data is built into the page
	 * data array for display when the page is rendered.
	 *
	 * @return void
	 */
	protected function build()
	{
		//Get page elements
		$this->addPageComponent('monthFilter', $this->getMonthFilter());
		$this->addPageComponent('comments', $this->getComments());

		//Get post data
		$post = $this->getPostData($this->id);
		$this->addPageComponent('id', $post['id']);
		$this->addPageComponent('title', $post['title']);
		$this->addPageComponent('author', $post['author']);
		$this->addPageComponent('date', $post['date']);
		$this->addPageComponent('rating', $post['rating']);
		$this->addPageComponent('editor', $post['editor']);
		$this->addPageComponent('editTime', $post['editTime']);
		$this->addPageComponent('content', $this->parseContent($post['content']));
	}

// Private functions

	/**
	 * This function gets the content for the month filter. Returned is an array of dates.
	 *
	 * @return array The array of dates for the filter
	 */
	private function getMonthFilter()
	{
		return Database::execProcedure('GetMonthFilter', array('page' => $this->parent));
	}

	/**
	 * This function gets the data on comments on the post. Returned is an array of comments and data.
	 *
	 * @return array The array of comments
	 */
	private function getComments()
	{
		//Get the data
		$data = Database::execProcedure('GetPostComments', array('post' => $this->id));

		//Format the data
		foreach ($data as $key => $value) {
			$data[$key]['timestamp'] = date('d\/m\/Y \@ g:ia', strtotime($value['timestamp']));
		}

		return $data;
	}

	/**
	 * This function gets the data related to the post ready for it to be output. The ID of
	 * the post is passed and the data is returned as an array.
	 *
	 * @param  string|integer $id The ID of the post to be retrived
	 * @return array              The post data to be output
	 */
	private function getPostData($id)
	{
		return array(
			'id' => 1,
			'title' => 'Test',
			'author' => 'Nick Green',
			'date' => 'Sun 28th July 2015 @ 22:29',
			'rating' => '2.5',
			'editor' => 'Nick Green',
			'editTime' => 'Sun 28th July 2015 @ 22:30',
			'post' => '<p>This is a test</p>'
		);

		//Get the data
		$data = Database::execProcedure('GetPostData', array('post' => $this->id));

		//Format the data
		$data[0]['post'] = $this->parseContent($value['content']);
		$data[0]['date'] = date('d\/m\/Y \@ g:ia', strtotime($value['timestamp']));
		$data[0]['editTime'] = date('d\/m\/Y \@ g:ia', strtotime($value['editTime']));

		return $data[0];
	}
}