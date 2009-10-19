<?php
use \de\buzz2ee\reflection\StaticReflectionParser;

function __autoload( $className )
{
    include __DIR__ . '/source/' . strtr( substr( $className, 22 ), '\\', '/' ) . '.php';
}

if ( isset( $argv[1] ) )
{
    $fileName = $argv[1];
}
else
{
    $fileName = __DIR__ . '/source/RuntimeReflectionClass.php';
}

$parser = new StaticReflectionParser( $fileName );
$parser->parse();