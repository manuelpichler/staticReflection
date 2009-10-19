<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\parser;

require_once 'BaseTest.php';

/**
 * Test cases for the tokenizer class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class TokenizerTest extends \de\buzz2ee\reflection\BaseTest
{
    /**
     * @return void
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testTokenizerScopeParsingWithNonPhpCurlyBrace()
    {
        $source = '<?php class c { function x() { ?>}<?php } }';
        $scope  = 0;

        $tokenizer = new Tokenizer( $source );
        while ( ( $token = $tokenizer->next() ) !== Tokenizer::EOF )
        {
            if ( $token->type === ParserTokens::T_SCOPE_CLOSE )
            {
                --$scope;
            }
            else if ( $token->type === ParserTokens::T_SCOPE_OPEN )
            {
                ++$scope;
            }
        }
        $this->assertSame( 0, $scope );
    }
}