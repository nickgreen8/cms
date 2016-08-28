<?php
namespace N8G\Grass\Display;

use N8G\Grass\Exceptions\Display\ThemeException;

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
	 * Application container reference.
	 * @var object
	 */
	private $container;
	/**
	 * The theme to be utilised.
	 * @var string
	 */
	private $theme;

	/**
	 * Default constructor.
	 *
	 * @param object $container An instance of the container.
	 */
	public function __construct(&$container)
	{
		//Set the container
		$this->container = &$container;
	}

//Public functions

	/**
	 * This function is used to check that the theme set is valid. If not, the
	 * default theme is used.
	 *
	 * @return void
	 */
	public function checkForTheme()
	{
		//Check that there is a directory
		if (!is_dir($this->getDirectory())) {
			throw new ThemeException('The directory could not be found.');
		}
	}

// Getters

	/**
	 * This function is used to return the directory to the relevant theme.
	 *
	 * @return string The path to the theme
	 */
	public function getDirectory()
	{
		return sprintf(
			'%s%s/',
			$this->container->get('config')->theme->directory,
			$this->theme
		);
	}

	/**
	 * This function gets the settings for the pagetype related to the theme.
	 *
	 * @param  string|null $type null to just get the global theme settings of the page type for the page type settings
	 * @return array             The array of settings from the config file
	 */
	public function getPageSettings($type = null)
	{
		//Get the settings for the page type
		$settings = $this->getSettings($type);

		//Format settings
		foreach ($settings as $key => $value) {
			//Check for embeded options/settings
			if (is_array($value)) {
				//Look for the option to be turned on
				if (isset($value['on'])) {
					if (isset($value['options'])) {
						foreach ($value['options'] as $option) {
							if ($option['selected']) {
								$settings[$key] = $option['option'];
							}
						}
					} else {
						$settings[$key] = $value['on'];
					}
				}
			}
		}

		return $settings;
	}

	/**
	 * This function is used to get all the files with specific extentions out of the relevant themes directory. These
	 * files are then input into an array which is returned for ouput in HTML.
	 *
	 * @param  array  $extentions The file extentions to check for.
	 * @param  string $path       The path of the directory to interigate.
	 * @return Array              An array of HTML strings for each stylesheet.
	 */
	public function getFiles($extentions, $path = '')
	{
		//Create return array
		$files = array();

		//Go through all files in the theme directory
		foreach (array_diff(scandir(sprintf('%s%s', $this->getDirectory(), $path)), array('.', '..')) as $file) {
			//Check for file
			if (preg_match(sprintf("/.(?:%s)$/", implode('|', $extentions)), $file)) {
				array_push($files, sprintf('%s%s%s', $this->getDirectory(), $path, $file));
			//Check for directory
			} elseif (is_dir(sprintf('%s%s%s', $this->getDirectory(), $path, $file))) {
				$files = array_merge(
					$this->getFiles($extentions, sprintf('%s%s/', $path, $file)),
					$files
				);
			}
		}

		//Return the list of files
		return $files;
	}

    /**
     * Sets the the theme to be utilised.
     *
     * @param  string $theme the theme
     * @return self
     */
    public function setTheme($theme)
    {
    	$this->container->get('logger')->notice(sprintf('Setting theme to: %s', $theme));
        $this->theme = $theme;
        return $this;
    }

    /**
     * Gets the the theme to be utilised.
     *
     * @return string The theme to be utilised
     */
    public function getTheme()
    {
        return !isset($this->theme)
			? is_null($this->container->get('config')->theme->active)
				? $this->container->get('config')->theme->default
				: $this->container->get('config')->theme->active
			: $this->theme;
    }

// Private functions

	/**
	 * This function gets the settings for the page or the overall theme.
	 *
	 * @param  string|null $type null to just get the global theme settings of the page type for the page type settings
	 * @return array             The array of settings from the config file
	 */
	private function getSettings($type = null)
	{
		$path = sprintf('%sconfig.json', $this->getDirectory());

		//Check that the theme config exists
		if ($this->container->get('json')->fileExists($path)) {
			//Get the data
			$config = $this->container->get('json')->readFile($path, true);
			$settings = $config['settings'];

			//Check the settings to be returned
			if ($type === null) {
				return $settings;
			} else {
				return (isset($settings[$type]) && $settings[$type] !== null) ? $settings[$type] : array();
			}
		}

		//Return an empty settings array by default
		return array();
	}
}