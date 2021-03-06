<?php
namespace N8G\Grass\Display\Pages;

use N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config,
	N8G\Grass\Components\Html\Div,
	N8G\Grass\Components\Html\Span,
	N8G\Grass\Components\Html\Image,
	N8G\Grass\Components\Html\Paragraph;

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
		$this->addPageComponent('rating', $this->formatRating($post['rating'], $post['ratingCount']));
		$this->addPageComponent('edited', $post['edited']);
		$this->addPageComponent('editor', $post['editor']);
		$this->addPageComponent('editTime', $post['editTime']);
		$this->addPageComponent('useful', $post['useful']);
		$this->addPageComponent('notUseful', $post['notUseful']);
		$this->addPageComponent('content', $this->parseContent($post['content']));
		$this->addPageComponent('parent', $post['page']);

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

	/**
	 * This function is used to convert the rating of the post to HTML. This is checked against
	 * the theme configuration and displayed as selected.
	 *
	 * @param  float   $rating The rating of the post
	 * @param  integer $count  The count of ratings that have been made against the post
	 * @return string          The HTML for the rating to be output as
	 */
	private function formatRating($rating, $count)
	{
		//Get the rating details
		$setting = $this->theme->getPageSetting('post', 'rating');
		//Look for the selected format
		$format = $this->getSelectedOption($setting['options']);

		//Check on format
		if ($format === 'stars') {
			//Format to stars
			return $this->formatStars($rating, $count);
		} else {
			$p = new Paragraph(array(new Span('Rating: ', null, array(), array('class' => 'bold')), round($rating, 2), new Span(sprintf('(%d)', $count), 'ratingCount', array(), array('class' => 'half'))), null, array(), array('class' => 'rating'));
			return $p->toHtml();
		}
	}

	/**
	 * This function converts the rating into stars. The rating is passed and the star rating is
	 * returned as a div populated with images ready to be output.
	 *
	 * @param  float  $rating The post rating
	 * @return string         The rating converted into HTML.
	 */
	private function formatStars($rating, $count)
	{
		//Get stars variables
		$full = (int) floor($rating);
		$half = round($rating - $full, 1) >= 0.5 ? true : false;

		//Create HTML element
		$div = new Div();
		$div->addAttribute(array('class' => 'rating'));

		//Build rating
		for ($i = 0; $i < 5; $i++) {
			//Checking for full
			if ($i < $full) {
				//Add full star
				$div->addElement(new Image(null, array('src' => sprintf('%simages/fullStar.png', $this->theme->getDirectory()), 'alt' => 'fullStar')));
			//Checking for half
			} elseif ($half && $i === $full) {
				//Add full half star
				$div->addElement(new Image(null, array('src' => sprintf('%simages/halfStar.png', $this->theme->getDirectory()), 'alt' => 'halfStar')));
				//Add empty half star
				$div->addElement(new Image(null, array('src' => sprintf('%simages/emptyHalfStar.png', $this->theme->getDirectory()), 'alt' => 'halfEmptyStar')));
			//Checking for empty
			} elseif ($i >= $full) {
				//Add empty star
				$div->addElement(new Image(null, array('src' => sprintf('%simages/emptyFullStar.png', $this->theme->getDirectory()), 'alt' => 'emptyStar')));
			}
		}

		//Add the count
		$div->addElement(new Paragraph(sprintf('(%d)', $count), 'ratingCount', array(), array('class' => 'half')));
		//Return HTML
		return $div->toHtml();
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