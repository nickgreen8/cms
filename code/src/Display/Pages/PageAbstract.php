<?php
namespace N8G\Grass\Display\Pages;

use N8G\Grass\Components\Html\HtmlBuilder,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

/**
 * This class is used to build an index page. This is a singleton class that is used
 * to load the relevant index page from the relevant theme. All relevant data will
 * also be parsed ready to be inserted into the page template. This will then be
 * requested from the page and output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
abstract class PageAbstract implements PageInterface
{
//Public functions

	/**
	 * This function is used to parce the content of the page. The content is parsed as a string
	 * before being converted into HTML and returned as a HTML string.
	 *
	 * @param  string $content The content that is pulled from the database.
	 * @return string          The page content as a HTML string.
	 */
	public function parseContent($content)
	{
		Log::debug('Converting content to HTML.');
		Log::info(sprintf('Converting string: %s', $content));

		$html = '';
		$content = explode('\n', $content);

		//Go through each line
		foreach ($content as $line) {
			//Extract data
			preg_match("/([a-z]{0,6})(\d{1,2})?#(?:(\w+)#)?(?:{(.*)})?(.*)/u", trim($line), $data);

			//Convert to HTML
			$html .= HtmlBuilder::getObject($data[1], $data[5], $data[3], HtmlBuilder::convertStrToAttributes($data[4]), $data[2])->toHtml();
		}

		//Set the content and return
		$this->data['content'] = $html;
		return $html;
	}

//Private functions
}