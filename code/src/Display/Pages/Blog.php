<?php
namespace N8G\Grass\Display\Pages;

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
	 * Create a constant array of filter types for a blog;
	 * @var array
	 */
	private $filters = array('month');
	/**
	 * The name of the template to import
	 * @var string
	 */
	protected $template = 'blog';

// Public functions

	/**
	 * This function builds up the content for the page. Data is built into the page
	 * data array for display when the page is rendered.
	 *
	 * @return void
	 */
	public function build()
	{
		parent::build();
		$this->addPageComponent('posts', $this->getPosts());
		$this->addPageComponent('monthFilter', $this->getMonthFilter());
	}

// Protected functions

// Private functions

	/**
	 * This function gets the posts to display. The database is queried with the data
	 * formatted and then returned in array format.
	 *
	 * @return array The blog posts to be displayed
	 */
	private function getPosts()
	{
		$this->container->get('logger')->notice('Getting page posts');

		//Get the page arguments
		$args = $this->getArgs();

		//Set procedure params
		$params = array(
			'page'		=> $this->container->get('page-id'),
			'filter'	=> 'NULL'
		);

		if (count($args) > 0 && in_array($args[1], $this->filters)) {
			unset($args[1]);
			$params['filter'] = sprintf('%s', implode('-', $args));
		}

		$this->container->get('logger')->debug(sprintf('Arguments being passed to get posts: %s', json_encode($params)));

		//Get the data
		$data = $this->container->get('db')->execProcedure('GetPosts', $params);

		//Format the data
		foreach ($data as $key => $value) {
			$data[$key]['post'] = $this->parseContent($value['content']);
			$data[$key]['preview'] = $this->parseContent($this->createPreview($value['content']));
			$data[$key]['rating'] = round($value['rating'], 1);
			$data[$key]['date'] = date('d\/m\/Y \@ g:ia', strtotime($value['timestamp']));
			$data[$key]['editTime'] = date('d\/m\/Y \@ g:ia', strtotime($value['editTime']));
			$data[$key]['key'] = strtolower(str_replace(' ', '-', preg_replace("/\'/", '', $value['title'])));
		}

		return $data;
	}

	/**
	 * This function gets the content for the month filter. Returned is an array of dates.
	 *
	 * @return array The array of dates for the filter
	 */
	private function getMonthFilter()
	{
		return $this->container->get('db')->execProcedure('GetMonthFilter', array('page' => $this->container->get('page-id')));
	}

	/**
	 * This function creates the preview for the blog posts. The string is passed and
	 * the parsed content is returned.
	 *
	 * @param  string $string The string to be converted for preview
	 * @return string         The preview content
	 */
	private function createPreview($string)
	{
		//Split string
		$words = explode(" ", $string);
		$content = array();

		if (count($words) > 50) {
			for ($i = 0; $i < 50; $i++) {
				array_push($content, $words[$i]);
			}
			array_push($content, '...');
		} else {
			for ($i = 0; $i < count($words); $i++) {
				array_push($content, $words[$i]);
			}
		}

		return implode(" ", $content);
	}
}