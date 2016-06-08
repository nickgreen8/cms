<?php
namespace N8G\Grass;

use N8G\Utils\Log;
use N8G\Utils\Json;
use N8G\Utils\Config;
use N8G\Utils\ContainerAbstract;

use N8G\DatabaseConnector\Database;

use N8G\Grass\Display\Twig;
use N8G\Grass\Display\Theme;
use N8G\Grass\Display\Navigation;

use N8G\Grass\Display\Pages\Blog;
use N8G\Grass\Display\Pages\Form;
use N8G\Grass\Display\Pages\Page;
use N8G\Grass\Display\Pages\Post;
use N8G\Grass\Display\Pages\Error;
use N8G\Grass\Display\Pages\Index;
use N8G\Grass\Display\Pages\Login;

use Parsedown;

/**
 * Application container.
 * This class will be the container that will hold all the utilities that are required throughout the application.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
final class Container extends ContainerAbstract
{
    /**
     * Populates the application container with all the utilities that are required.
     *
     * @param  array $config Array of configuration data
     * @return void
     */
    public function populate($config = null)
    {
        //Utilities
        $this->add('json', new Json);

        if (isset($config->logging)) {
            //Create logger
            $logger = new Log;
            $logger->init(
                $config->logging->directory,
                $config->logging->file,
                $config->logging->level
            );
            $this->add('logger', $logger);
            $this->get('logger')->notice('Logger Initilised');

            //Remove from config as no longer required
            unset($config->logging);
        }

        if (isset($config->theme)) {
            $this->add('theme', $config->theme);
        }

        //Add remaining config
        $this->add('config', $config);

        //3rd Party classes
        $this->add('markdown', new Parsedown);

        //Application Classes
        $this->add('twig', new Twig($this));
        $this->add('theme', new Theme($this));
        $this->add('navigation', new Navigation($this));

        //Page types
        $this->add('page.blog', new Blog($this));
        $this->add('page.form', new Form($this));
        $this->add('page.page', new Page($this));
        $this->add('page.post', new Post($this));
        $this->add('page.error', new Error($this));
        $this->add('page.index', new Index($this));
        $this->add('page.login', new Login($this));

        if (isset($config->database)) {
            //Create database connection
            $database = new Database($this, 'mysql');
            $this->add('db', $database);
            $database->connect($config->database);
            $this->get('logger')->notice('Database Connection Initilised');

            //Remove from config as no longer required
            unset($config->database);
        }
    }
}
