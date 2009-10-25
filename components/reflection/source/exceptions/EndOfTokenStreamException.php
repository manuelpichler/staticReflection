<?php
namespace org\pdepend\reflection\exceptions;

class EndOfTokenStreamException extends ParserException
{
    public function __construct( $fileName )
    {
        parent::__construct( 'Unexpected end of token stream in file ' . $fileName );
    }
}
