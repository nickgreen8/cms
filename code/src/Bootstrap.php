<?php
namespace N8G\Grass;

/**
 * This class is use to initilise the processes and connections that will be needed on
 * an initial page load. Processes will also be stoped at the end of a page load using
 * the functions in this class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Bootstrap
{
    /**
     * Application container reference.
     * @var object
     */
    private $container;

    /**
     * Default constructor.
     *
     * @param object $container Application container.
     */
    public function __construct(&$container)
    {
        date_default_timezone_set('Europe/London');
        $this->container = &$container;
    }

    /**
     * This function is the initial call that is will begin any processes and will
     * create connections that will be required for a page to be created. Nothing
     * is passed to this function and nothing is returned.
     *
     * @return void
     */
    public function run()
    {
        //Initilise error handler
        set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
            $this->errorHandler($errno, $errstr, $errfile, $errline, $errcontext);
        });
    }

    /**
     * This function is used to close connections and stop any processes running in
     * the background. Nothing is passed to this function and nothing is returned.
     *
     * @return float The time since the initial request was made.
     */
    public function tearDown()
    {
        //Calculate and output the load time
        $this->container->get('logger')->success(
            sprintf('Page load time: %fs', microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5)
        );
    }

    /**
     * This function takes any errors, processes them and will cause the application
     * to react as required.
     *
     * @param  integer $code    The error code.
     * @param  string  $message The error message.
     * @param  string  $file    The filename where the error is thrown.
     * @param  integer $line    The line number where the error is.
     * @param  mixed   $context
     * @return boolean          True.
     */
    public function errorHandler($code, $message, $file, $line, $context)
    {
        //Get the relevant method for the log
        $method = $this->getMethod($code);

        //Build the log entry
        $log = sprintf(
            '[code: %d] [type: %s] [file: %s] [line: %d] %s',
            $code,
            $this->getType($code),
            $file,
            $line,
            $message
        );

        //Log the error
        $this->container->get('logger')->$method($log);

        //Check whether the error requires a specific exception to be thrown
        $this->errorMap($code, $message);

        //Don't execute PHP's own error handler
        return true;
    }

    /**
     * This function is used to get the relevent function to log the error with. The
     * error code is passed and before the log method to be used is passed back as a
     * string.
     *
     * @param  integer $code The PHP error code for the error that has been thrown
     * @return string        The function to log the error with
     */
    private function getMethod($code)
    {
        switch ($code) {
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
                return 'fatal';
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_STRICT:
                return 'warn';
                break;
            default:
                return 'error';
                break;
        }
    }

    /**
     * This function gets the error type as a string so that it can be displayed in the
     * logs. The error code is passed and before the type is passed back as a string.
     *
     * @param  integer $code The PHP error code for the error that has been thrown
     * @return string        The error type as a string
     */
    private function getType($code)
    {
        //Use the code to determine the error
        switch ($code) {
            case E_ERROR:              // 1
                return 'ERROR';
                break;
            case E_WARNING:            // 2
                return 'WARNING';
                break;
            case E_PARSE:              // 4
                return 'PARSE';
                break;
            case E_NOTICE:             // 8
                return 'NOTICE';
                break;
            case E_CORE_ERROR:         // 16
                return 'CORE ERROR';
                break;
            case E_CORE_WARNING:       // 32
                return 'CORE WARNING';
                break;
            case E_CORE_ERROR:         // 64
                return 'CORE ERROR';
                break;
            case E_CORE_WARNING:       // 128
                return 'CORE WARNING';
                break;
            case E_USER_ERROR:         // 256
                return 'USER ERROR';
                break;
            case E_USER_WARNING:       // 512
                return 'USER WARNING';
                break;
            case E_USER_NOTICE:        // 1024
                return 'USER NOTICE';
                break;
            case E_STRICT:             // 2048
                return 'STRICT';
                break;
            case E_RECOVERABLE_ERROR:  // 4096
                return 'RECOVERABLE';
                break;
            case E_DEPRECATED:         // 8192
                return 'DEPRECATED';
                break;
            case E_USER_DEPRECATED:    // 16384
                return 'USER DEPRECATED';
                break;
            default:                   // Unknown
                return sprintf('UNKNOWN (%d)', $code);
                break;
        }
    }

    /**
     * Maps an error to a specific exception that might have to be thrown. This is triggered by the custom error
     * handler within this class. The error code and the error message is then used to determine any error that needs
     * to be thrown.
     *
     * @param  integer $code    The error code
     * @param  string  $message The error message
     * @return void
     */
    private function errorMap($code, $message)
    {
        //Check the message
        switch ($message) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_USER_ERROR:
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                throw new \Exception($message, 500);
                break;

            default:
                $this->container->get('logger')->info('No error message map to be thrown.');
        }

        //Check the code
        switch ($code) {
            case 'mysqli::mysqli(): (HY000/2002): No such file or directory':
                throw new \Exception('No database could be found', 500);
                break;

            default:
                $this->container->get('logger')->info('No error code map to be thrown.');
        }
    }
}
