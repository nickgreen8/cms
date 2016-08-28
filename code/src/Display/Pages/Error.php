<?php
namespace N8G\Grass\Display\Pages;

/**
 * This class is used to build an error page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Error extends PageAbstract
{
    /**
     * The ID of the page
     * @var string|integer
     */
    protected $id = 'Error';
    /**
     * The name of the template to import
     * @var string
     */
    protected $template = 'error';

// Public functions

    /**
     * This function builds up the content for the page. Data is built into the page data array for
     * display when the page is rendered. This is the parent function and can be overriden within
     * the child page classes.
     *
     * @return void
     */
    public function build()
    {
        $this->container->get('logger')->info('Building error page');

        //Check for exception
        if (isset($this->args['exception'])) {
            //Get the exception
            $exception = $this->args['exception'];

            //Create page data
            $data['code']    = $exception->getCode();
            $data['message'] = $exception->getMessage();

            //Set the code argument
            $this->args['code'] = $exception->getCode();

            //Check if the application is in debug mode
            if (isset($this->container->get('config')->debug) && $this->container->get('config')->debug === true) {
                $this->addPageComponent('debug', true);
                $data['file']       = $exception->getFile();
                $data['line']       = $exception->getLine();
                $data['stacktrace'] = array();

                //Build the stacktrace
                foreach ($exception->getTrace() as $call) {
                    array_push(
                        $data['stacktrace'],
                        sprintf(
                            '%s (%d) %s%s%s(%s)',
                            isset($call['file']) ? str_replace('/Users/NickGreen/workspace/code/cms/code/src/', '', $call['file']) : 'unknown',
                            isset($call['line']) ? $call['line'] : 'unknown',
                            isset($call['class']) ? str_replace('N8G\Grass\\', '', $call['class']) : 'unknown',
                            isset($call['type']) ? $call['type'] : 'unknown',
                            $call['function'],
                            $this->processArgsOutput($call['args'])
                        )
                    );
                }
            }
        //Check for error code
        } elseif (isset($this->args['code'])) {
            //Create page data
            $data['code'] = $this->args['code'];
        }

        //Add error to the data
        $this->addPageComponent('error', $data);
    }

    /**
     * This function builds the page title. This includes all parent pages as well as the site tag
     * line if it is the home page. The page data array is passed and the title as a string is
     * returned.
     *
     * @param  array  $data The page data pulled from the database
     * @return string       The fully formed page title
     */
    public function buildTitle()
    {
        $this->container->get('logger')->debug('Building error page title');

        return implode(
            sprintf(' %s ', $this->container->get('config')->options->titleSeperator),
            array(
                $this->container->get('config')->options->title,
                'Error',
                $this->args['code']
            )
        );
    }

// Private functions

    private function processArgsOutput($args)
    {
        //Check for args
        if (!isset($args)) {
            return '';
        }

        //Check that the args are an array
        if (!is_array($args)) {
            return $args;
        }

        //Format args array output
        foreach ($args as $key => $value) {
            if (is_array($value)) {
                $args[$key] = 'array';
            } elseif (is_object($value)) {
                $args[$key] = 'object';
            }
        }
        return implode(', ', $args);
    }
}
