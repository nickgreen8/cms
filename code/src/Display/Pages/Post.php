<?php
namespace N8G\Grass\Display\Pages;

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
	 * The name of the template to import
	 * @var string
	 */
	protected $template = 'post';

// Public functions

	/**
	 * This function builds up the content for the page. Data is built into the page
	 * data array for display when the page is rendered.
	 *
	 * @return void
	 */
	public function build()
	{
		$this->container->get('logger')->info('Building page data');
		//Get post data
		$post = $this->getPostData($this->id);

		//Add page data
		$this->addPageComponent('name', $post['title']);
		$this->addPageComponent('type', 'post');

		$this->addPageComponent('heading', $post['title']);
		$this->addPageComponent('author', $post['author']);
		$this->addPageComponent('date', $post['date']);
		$this->addPageComponent('rating', array('value' => $post['rating'], 'count' => $post['ratingCount']));
		$this->addPageComponent('rating', array('value' => 3.25, 'count' => 16));
		$this->addPageComponent('edited', $post['edited']);
		$this->addPageComponent('editor', $post['editor']);
		$this->addPageComponent('editTime', $post['editTime']);
		$this->addPageComponent('useful', $post['useful']);
		$this->addPageComponent('notUseful', $post['notUseful']);
		$this->addPageComponent('content', $this->parseContent($post['content']));
		$this->addPageComponent('parent', $post['page']);

		//Set page identifiers in Config
		$this->container->add('page-name', $post['title']);
		$this->container->add('page-type', 'post');
		$this->addPageComponent('theme', $this->container->get('config')->theme->active);

		//Get page elements
		$this->addPageComponent('monthFilter', $this->getMonthFilter($post['page']));
		$this->addPageComponent('comments', $this->getComments($post['id']));
		$this->addPageComponent('settings', $this->container->get('theme')->getPageSettings('post'));
	}

	/**
	 * This function builds the page title. This includes all parent pages as well as the site tag
	 * line if it is the home page. The page data array is passed and the title as a string is
	 * returned.
	 *
	 * @param  array  $data The page data pulled from the database
	 * @return string       The fully formed page title
	 */
	public function buildTitle()
	{
		$this->container->get('logger')->info('Building post header');

		//Get post data
		$post = $this->getPostData($this->id);
		//Get parent data
		$data = $this->getPageData($post['page']);
		//Create title array
		$title = array($post['title']);

		//Get parent
		while ($data['parent'] !== null && (is_numeric($data['parent']) && $data['parent'] > 0)) {
			//Add page name
			array_unshift($title, $data['name']);
			//Get parent data
			$data = $this->getPageData($data['parent']);
		}
		//Add final parent
		array_unshift($title, $data['name']);

		//Prepend the site title
		array_unshift($title, $this->container->get('config')->options->title);

		//Return built title
		return implode(sprintf(' %s ', $this->container->get('config')->options->titleSeperator), $title);
	}

// Protected functions

// Private functions

	/**
	 * This function gets the content for the month filter. Returned is an array of dates.
	 *
	 * @return array The array of dates for the filter
	 */
	private function getMonthFilter($id)
	{
		return $this->container->get('db')->execProcedure('GetMonthFilter', array('page' => $id));
	}

	/**
	 * This function gets the data on comments on the post. Returned is an array of comments and data.
	 *
	 * @return array The array of comments
	 */
	private function getComments($id)
	{
		//Get the data
		$data = $this->container->get('db')->execProcedure('GetPostComments', array('post' => $id));

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
		$data = $this->container->get('db')->execProcedure('GetPostData', array('post' => $id));

		//Format the data
		$data[0]['content'] = $this->parseContent($data[0]['content']);
		$data[0]['date'] = date('d\/m\/Y \@ g:ia', strtotime($data[0]['timestamp']));
		$data[0]['editTime'] = date('d\/m\/Y \@ g:ia', strtotime($data[0]['editTime']));
		$data[0]['rating'] = $data[0]['rating'] === null ? '' : $data[0]['rating'];

		return $data[0];
	}

	/**
	 * This function takes a list of options and returns the selected one. If there are no
	 * selected options, null is returned.
	 *
	 * @param  array       $options The array of options to be checked
	 * @return string|null          The selected option or null
	 */
	private function getSelectedOption($options)
	{
		//Look for the selected format
		foreach ($options as $option) {
			//Check if selected
			if ($option['selected'] === true) {
				//return selected
				return $option['option'];
			}
		}

		//Return default
		return null;
	}
}