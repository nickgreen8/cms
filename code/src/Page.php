<?php
namespace N8G\Cms;

use N8G\Cms\Components\Html\Anchor,
	N8G\Cms\Components\Html\Article,
	N8G\Cms\Components\Html\Body,
	N8G\Cms\Components\Html\Button,
	N8G\Cms\Components\Html\Div,
	N8G\Cms\Components\Html\Fieldset,
	N8G\Cms\Components\Html\Footer,
	N8G\Cms\Components\Html\Form,
	N8G\Cms\Components\Html\Head,
	N8G\Cms\Components\Html\Header,
	N8G\Cms\Components\Html\Heading,
	N8G\Cms\Components\Html\HorizontalRule,
	N8G\Cms\Components\Html\Image,
	N8G\Cms\Components\Html\Input,
	N8G\Cms\Components\Html\Link,
	N8G\Cms\Components\Html\ListItem,
	N8G\Cms\Components\Html\Meta,
	N8G\Cms\Components\Html\Option,
	N8G\Cms\Components\Html\OrderedList,
	N8G\Cms\Components\Html\PageBreak,
	N8G\Cms\Components\Html\Paragraph,
	N8G\Cms\Components\Html\Script,
	N8G\Cms\Components\Html\Section,
	N8G\Cms\Components\Html\Select,
	N8G\Cms\Components\Html\Span,
	N8G\Cms\Components\Html\Style,
	N8G\Cms\Components\Html\Table,
	N8G\Cms\Components\Html\TableBody,
	N8G\Cms\Components\Html\TableCell,
	N8G\Cms\Components\Html\TableHeader,
	N8G\Cms\Components\Html\TableHeading,
	N8G\Cms\Components\Html\TableRow,
	N8G\Cms\Components\Html\Title,
	N8G\Cms\Components\Html\UnorderedList,
	N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Config;

class Page
{
	private static $page;
	private $id;
	private $head;
	private $body;
	private $data;

	private function __construct($id)
	{
		//Set page ID
		$this->id = $id;

		//Create head and body
		$this->head = new Head();
		$this->body = new Body();

		//Get the page data from the DB
		$this->data = array(
			'meta'		=>	array('keywords', 'description', 'subject', 'revised', 'summary', 'copyright', 'url', 'author', 'designer'),
			'style'		=>	array(),
			'script'	=>	array(),
			'content'	=>	array()
		);
		$this->getData();

		//Build page sections
		$this->buildHead();
		$this->buildBody();
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
		return $this->head->toHtml() . $this->body->toHtml();
	}

	private function getData()
	{
		//Get the data from the database
		$page = Database::getArray(Database::perform('page', array('name', 'title'), 'select', $this->id));

		//Assign data to page array
		$this->data['name'] = $page['name'];
		$this->data['title'] = $page['title'];
	}

	private function processData()
	{
		//Build page sections
		$this->buildHead();
		$this->buildBody();
	}

	private function buildHead()
	{
		//Add mandatory data
		$this->head->addElement(new Meta(array('name' => 'content', 'content' => 'text/html;charset=UTF-8', 'http-equiv' => 'Content-type')));
		$this->head->addElement(new Title($this->data['title']));
		$this->head->addElement(new Link(array('rel' => 'shortcut icon', 'type' => '', 'href' => Config::getItem('favicon'))));

		//Add meta data
		foreach ($this->data['meta'] as $name) {
			if (Config::inConfig('meta-' . $name)) {
				$this->head->addElement(new Meta(array('name' => $name, 'content' => Config::getItem('meta-' . $name))));
			}
		}
		//Add script
		foreach ($this->data['script'] as $file) {
			$this->head->addElement(new Script(null, array('type' => 'text/javascript', 'src' => $file)));
		}
		//Add style
		foreach ($this->data['style'] as $file) {
			$this->head->addElement(new Link(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $file)));
		}
	}

	private function buildBody()
	{
	}
}