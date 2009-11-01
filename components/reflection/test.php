<?php
use \org\pdepend\reflection\parser\Parser;
use \org\pdepend\reflection\interfaces\SourceResolver;

function __autoload( $className )
{
    include __DIR__ . '/source/' . strtr( substr( $className, 22 ), '\\', '/' ) . '.php';
}

if ( isset( $argv[1] ) )
{
    $className = $argv[1];
}
else
{
    $className = 'org\pdepend\reflection\parser\Parser';
}

class DynamicSourceResolver implements SourceResolver
{
    /**
     * @var array(string=>string)
     */
    private $_classNameToFileMap = array();

    /**
     * Constructs a new source resolver.
     *
     * @param string $directory The root source directory.
     */
    public function __construct( $directory, $namespace = null )
    {
        $path  = realpath( $directory );
        $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) );

        foreach ( $files as $file )
        {
            if ( $file->isFile() && substr( $file->getFilename(), -4, 4 ) === '.php' )
            {
                
                $className = pathinfo( $file->getFilename(), PATHINFO_FILENAME );
                if ( $namespace !== null )
                {
                    $localPath = substr( $file->getPath(), strlen( $path ) );
                    $className = rtrim( $namespace, '\\' ) . strtr( $localPath, '/', '\\' ) . '\\' . $className;
                }

                $this->_classNameToFileMap[$className] = $file->getPathname();
            }
        }
    }

    /**
     * Returns the source of the file where the given class is defined.
     *
     * @param string $className
     *
     * @return string
     */
    public function getSourceForClass( $className )
    {
        if ( isset( $this->_classNameToFileMap[$className] ) )
        {
            return file_get_contents( $this->_classNameToFileMap[$className] );
        }
        throw new LogicException( 'Cannot locate source for class: ' . $className );
    }
}

$resolver = new DynamicSourceResolver( __DIR__ . '/test', 'org\pdepend\reflection' );

$parser = new Parser( $resolver );
var_dump( $parser->parseClass( $className ) );