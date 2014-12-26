<?php
namespace Website;

use Components\HTML\Paragraph,
	Utils\Log;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

Log::fatal('Fatal log check');
Log::warn('Warn log check');
Log::notice('Notice log check');
Log::info('Info log check');
Log::debug('Debug log check');
Log::error('Error log check');

	$p = new Paragraph('test p', 'Hello World!');
	echo $p->toHtml();
?>