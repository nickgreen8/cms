<?php
namespace N8G\Grass\Display;

use N8G\Grass\Components\Html\Script,
	N8G\Grass\Components\Html\Style,
	N8G\Grass\Components\Html\Link,
	N8G\Grass\Exceptions\Display\ThemeException,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config,
	N8G\Utils\Json;

/**
 * This class is used to contain theme data. This will include the retreval
 * of the relevant script and style sheets. The path to the theme is also
 * heald for easy use.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Theme
{
	/**
	 * The data retrieved from the theme.
	 * @var array
	 */
	private $data;
	/**
	 * Path to the relevant theme folder.
	 * @var string
	 */
	private $path;
	/**
	 * Array of style sheets related to the theme.
	 * @var array
	 */
	private $style = array();
	/**
	 * Array of script files related to the theme.
	 * @var array
	 */
	private $script = array();
	/**
	 * Array of theme specific savings
	 * @var array
	 */
	private $settings = array();

	/**
	 * This is the default constructor. One optional parameter is passed. This
	 * is the name of the theme. If nothing is passed then the theme that is
	 * set in the database is used.
	 *
	 * @param string $name The name of the theme
	 */
	public function __construct($name = null)
	{
		$this->path = ($name === null) ? Config::getItem('theme') : $name;

		//Check the theme is valid
		try {
			$this->checkForTheme();
		} catch (\Exception $e) {
			echo "Theme - GO TO ERROR PAGE";
		}

		$this->data['style'] = $this->getStyles();
		$this->data['script'] = $this->getScript();
	}

	/**
	 * This function is used to check that the theme set is valid. If not, the
	 * default theme is used.
	 *
	 * @return void
	 */
	private function checkForTheme()
	{
		//Check that there is a directory
		if (!is_dir(ASSETS_DIR . THEMES_DIR . $this->path)) {
			throw new ThemeException('The directory could not be found.');
		}

		//Set path value
		$this->path = THEMES_DIR . $this->path . '/';
	}

// Getters

	/**
	 * This function is used to return the path to the relevant theme.
	 *
	 * @return string The path to the theme
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * This function is used to return the data collected on instantiation.
	 *
	 * @return The theme data
	 */
	public function getHeadData()
	{
		return array(
			'style'		=> $this->getStyles(),
			'script'	=> $this->getScript()
		);
	}

	public function getSettings($type = null)
	{
		if (count($this->settings) > 0) {
			return array_merge($this->getGlobalSettings(), ($type === null) ? array() : (isset($this->settings[$type]) && $this->settings[$type] !== null) ? $this->settings[$type] : array());
		}

		$path = ASSETS_DIR . $this->path . 'config.json';

		//Check that the theme config exists
		if (Json::fileExists($path)) {
			//Get the data
			$config = Json::readFile($path, true);
			$this->settings = $config['settings'];

			return array_merge($this->getGlobalSettings(), ($type === null) ? array() : (isset($this->settings[$type]) && $this->settings[$type] !== null) ? $this->settings[$type] : array());
		}

		//Return an empty settings array by default
		return array();
	}

// Private functions

	/**
	 * This function is used to get all the stylesheets out of the relevant themes
	 * directory. These files are then input into HTML and returns each of them in
	 * an array.
	 *
	 * @param  string $path The path of the directory to interigate
	 * @return Array        An array of HTML strings for each stylesheet
	 */
	private function getStyles($path = '')
	{
		//Create return array
		$styles = array();

		//Go through all styles in the theme directory
		foreach (array_diff(scandir(ASSETS_DIR . $this->path . $path), array('.', '..')) as $file) {
			//Check for stylesheet
			if (substr($file, -3) === 'css') {
				array_push($styles, new Link(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => str_replace(ROOT_DIR, './', ASSETS_DIR) . $this->path . $path . $file)));
			} elseif (is_dir(ASSETS_DIR . $this->path . $path . $file)) {
				$styles = array_merge($this->getStyles($path . $file . '/'), $styles);
			}
		}

		//Return the list of stylesheets
		return $styles;
	}

	/**
	 * This function is used to get all the javascript files out of the relevant
	 * themes directory. These files are then input into HTML and returns each of
	 * them in an array.
	 *
	 * @param  string $path The path of the directory to interigate
	 * @return Array An array of HTML strings for each javascript files
	 */
	private function getScript($path = '')
	{
		//Create return array
		$script = array();

		//Go through all styles in the theme directory
		foreach (array_diff(scandir(ASSETS_DIR . $this->path . $path), array('.', '..')) as $file) {
			//Check for stylesheet
			if (substr($file, -2) === 'js') {
				array_push($script, new Script(array('async' => '', 'type' => 'text/javascript', 'href' => str_replace(ROOT_DIR, './', ASSETS_DIR) . $this->path . $path . $file)));
			} elseif (is_dir(ASSETS_DIR . $this->path . $path . $file)) {
				$script = array_merge($this->getScript($path . $file . '/'), $script);
			}
		}

		//Return the list of stylesheets
		return $script;
	}

	private function getGlobalSettings()
	{
		//Create return array
		$settings = array();

		//Loop though settings
		foreach ($this->settings as $key => $value) {
			//Check for page type
			if (!is_array($value)) {
				//Add to array
				$settings[$key] = $value;
			}
		}

		//Return global settings
		return $settings;
	}
}