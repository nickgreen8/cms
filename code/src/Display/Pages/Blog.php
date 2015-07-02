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
	}

// Public functions

// Protected fucntions

	/**
	 * This function builds up the content for the page. Data is built into the page
	 * data array for display when the page is rendered.
	 *
	 * @return void
	 */
	protected function build()
	{
		$this->addPageComponent('posts', $this->getPosts());
		$this->addPageComponent('monthFilter', $this->getMonthFilter());
	}

// Private functions

	private function getPosts()
	{
		//Get the data
		$data = Database::execProcedure('GetPosts', array('page' => Config::getPageId()));

		//Format the data
		foreach ($data as $key => $value) {
			$data[$key]['post'] = $this->parseContent($value['content']);
			$data[$key]['preview'] = $this->parseContent($this->createPreview($value['content']));
			$data[$key]['rating'] = round($value['rating'], 1);
			$data[$key]['date'] = date('d\/m\/Y \@ g:ia', strtotime($value['timestamp']));
			$data[$key]['editTime'] = date('d\/m\/Y \@ g:ia', strtotime($value['editTime']));
		}

		return $data;
	}

	private function getMonthFilter()
	{
		return Database::execProcedure('GetMonthFilter', array('page' => Config::getPageId()));
	}

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