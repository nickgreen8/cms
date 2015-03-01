<?php
namespace N8G\Cms\Display;

use N8G\Cms\Components\Html\Body,
	N8G\Cms\Components\Html\Link,
	N8G\Cms\Components\Html\ListItem,
	N8G\Cms\Components\Html\Meta,
	N8G\Cms\Components\Html\Script,
	N8G\Cms\Components\Html\Style,
	N8G\Cms\Components\Html\Title,
	N8G\Cms\Components\Html\UnorderedList,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

class Page
{
	private static $page;
	private $id;
	private $head = array();
	private $twig;
	private $data;

	private function __construct($id)
	{
		//Set page ID
		$this->id = $id;

		//Get twig instance
		$this->twig = Twig::init();

		//Get the page data from the DB
		$this->data = array(
			'meta'		=>	array('keywords', 'description', 'subject', 'revised', 'summary', 'copyright', 'url', 'author', 'designer'),
			'style'		=>	array(),
			'script'	=>	array(),
			'content'	=>	array()
		);
		$this->getData();

		//Build page sections
		$this->buildBody();
		$this->buildHead();
	}

	public static function init($id = 1)
	{
		//Check for instance of class
		if (!isset(self::$page)) {
			self::$page = new Page($id);
		}
		//Return this class
		return self::$page;
	}

	public function render()
	{
		$this->twig->render($data);
	}

	private function getData()
	{
		//Get the data from the database
		$page = Database::getArray(Database::perform('page', array('name', 'title'), 'select', $this->id));

		//Assign data to page array
		$this->data['name'] = $page['name'];
		$this->data['title'] = $page['title'];
	}

	private function buildHead()
	{
		//Add mandatory data
		$this->data['favicon'] = Config::getItem('favicon');
		$this->data['language'] = Config::inConfig('language') ? Config::getItem('language') : 'en';

		//Add meta data
		foreach ($this->data['meta'] as $name) {
			if (Config::inConfig('meta-' . $name)) {
				array_push($this->head, new Meta(array('name' => $name, 'content' => Config::getItem('meta-' . $name))));
			}
		}
		//Add script
		foreach ($this->data['script'] as $file) {
			array_push($this->head, new Script(null, array('type' => 'text/javascript', 'src' => $file)));
		}
		//Add style
		foreach ($this->data['style'] as $file) {
			array_push($this->data['style'], new Link(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $file)));
		}
	}

	private function buildBody()
	{
		$this->data['header'] = '<h1>Header</h1>';
		$this->data['content'] = '<p>This is a test</p>';
		$this->data['footer'] = '<h2>Footer</h2>';
	}
}