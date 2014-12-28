<?php
namespace Website;

use Components\HTML\Paragraph,
	Utils\Log,
	Exceptions\Components\ComponentNotFoundException,
	Exceptions\Components\ClassNotFoundException,
	Exceptions\Components\UnableToOpenFileException;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/library/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

	$p = new Paragraph(null, 'Hello World!');
	$p->setId('test p');

	echo $p->toHtml();

try {
	throw new ComponentMissingRequiredAttributesException('Error', Log::ERROR);
} catch (ComponentNotFoundException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} catch (ClassNotFoundException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} catch (UnableToOpenFileException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} catch (ComponentMissingRequiredAttributesException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>