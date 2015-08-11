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
		parent::build();

		//Get post data
		$post = $this->getPostData($this->id);

		//Add page data
		$this->addPageComponent('name', $post['title']);
		$this->addPageComponent('type', 'post');

		$this->addPageComponent('heading', $post['title']);
		$this->addPageComponent('author', $post['author']);
		$this->addPageComponent('date', $post['date']);
		$this->addPageComponent('rating', $post['rating']);
		$this->addPageComponent('editor', $post['editor']);
		$this->addPageComponent('editTime', $post['editTime']);
		$this->addPageComponent('useful', $post['useful']);
		$this->addPageComponent('notUseful', $post['notUseful']);
		$this->addPageComponent('content', $this->parseContent($post['content']));

		//Get page elements
		$this->addPageComponent('monthFilter', $this->getMonthFilter($post['page']));
		$this->addPageComponent('comments', $this->getComments($post['id']));
		$this->addPageComponent('title', $this->buildTitle(array('type' => 'post', 'parent' => $post['page'], 'name' => $post['title'])));
	}

// Private functions

	/**
	 * This function gets the content for the month filter. Returned is an array of dates.
	 *
	 * @return array The array of dates for the filter
	 */
	private function getMonthFilter($id)
	{
		return Database::execProcedure('GetMonthFilter', array('page' => $id));
	}

	/**
	 * This function gets the data on comments on the post. Returned is an array of comments and data.
	 *
	 * @return array The array of comments
	 */
	private function getComments($id)
	{
		//Get the data
		$data = Database::execProcedure('GetPostComments', array('post' => $id));

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
		//Get the data
		$data = Database::execProcedure('GetPostData', array('post' => $id));

		//Format the data
		$data[0]['content'] = $this->parseContent($data[0]['content']);
		$data[0]['date'] = date('d\/m\/Y \@ g:ia', strtotime($data[0]['timestamp']));
		$data[0]['editTime'] = date('d\/m\/Y \@ g:ia', strtotime($data[0]['editTime']));
		$data[0]['rating'] = $data[0]['rating'] === null ? '' : $data[0]['rating'];

		return $data[0];
	}
}