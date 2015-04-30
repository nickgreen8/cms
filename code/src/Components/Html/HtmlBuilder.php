<?php
namespace N8G\Grass\Components\Html;

use N8G\Utils\Config,
	N8G\Utils\Log;

/**
 * This is an abstract class that should be extended by all HTML object elements. All methods
 * that should be required have been specified in the component interface and are implemented
 * within this class. If there is anything that is missing, or a method does not fit into the
 * pattern, it can be overloaded within its class.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
class HtmlBuilder
{
	/**
	 * This function converts the parsed content into the relevant HTML objects so that they can
	 * be converted into the HTML string to be output onto the page. The type that is determined,
	 * the content and then any additional information that is required is passed. The relevant
	 * object is then returned for conversion.
	 *
	 * @param  string $type       The type of object to be created.
	 * @param  string $content    The content of the object.
	 * @param  string $id         The ID of the HTML element
	 * @param  array  $attributes An array of any attributes to be added to the HTML.
	 * @param  mixed  $additional Any additional information related to the object.
	 * @return object             The relevant HTML object.
	 */
	public static function getObject($type = 'p', $content = '', $id = '', $attributes = array(), $additional = null)
	{
		Log::info('Creating new HTML object');
		Log::debug(sprintf('Object type: %s', $type));
		Log::debug(sprintf('Object content: %s', $content));

		//Determine the correct class
		switch ($type) {
			case 'a' :
				//Create a new anchor
				return new Anchor($content, $id, array(), $attributes);
				break;

			case 'div' :
				//Create new div
				return new Div($content, $id, array(), $attributes);
				break;

			case 'fieldset' :
				//Create new fieldset
				return new Fieldset($content, $id, array(), $attributes);
				break;

			case 'form' :
				//Create new form
				return new Form($content, $id, array(), $attributes);
				break;

			case 'h' :
				//Create new heading
				return new Heading($additional, $content, $id, array(), $attributes);
				break;

			case 'input' :
				//Create new input
				return new Input($content, $id, array(), $attributes);
				break;

			case 'p' :
			default :
				//Create new paragraph
				return new Paragraph($content, $id, array(), $attributes);
				break;
		}
	}

	/**
	 * This function converts the extracted string from parsing the content into an array of
	 * attributes. This can then be passed into the HTML object and utilised.
	 *
	 * @param  string $string The extracted string.
	 * @return array          The array of attributes.
	 */
	public static function convertStrToAttributes($string = '')
	{
		Log::debug('Converting string to attributes array');

		//Create the return array
		$attributes = array();

		//Split the attributes
		$atts = explode('|', $string);

		//Loop through to convert
		foreach ($atts as $attribute) {
			//Extract the data
			preg_match(sprintf("/(?:\s*)?(\w+)(?:\s*)?=(?:\s*)?([%s]*)(?:\s*)?/u", Config::getItem('accepted-chars')), trim($attribute), $convertion);

			//Check for data
			if (count($convertion) === 3 && trim($convertion[1]) !== '') {
				//Add to the attributes array
				$attributes[trim($convertion[1])] = (trim($convertion[2]) === '') ? $convertion[1] : $convertion[2];
			}
		}

		//Return the list of attributes
		return $attributes;
	}
}