<?php
require_once __DIR__ . '/source/StaticReflectionParser.php';

use \de\buzz2ee\lang\StaticReflectionParser;

$fileName = __DIR__ . '/source/RuntimeReflectionClass.php';

$parser = new StaticReflectionParser( $fileName );
$parser->parse();