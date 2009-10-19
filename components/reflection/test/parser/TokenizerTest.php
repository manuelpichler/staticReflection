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
     * Test source code
     *
     * @var string
     */
    private $_source = '<?php
        class c
        {
            function x()
            {
                ?>}<?php
                $x = "

                ";
            }
        }';

    /**
     * The test tokenizer.
     *
     * @var \de\buzz2ee\reflection\parser\Tokenizer
     */
    private $_fixture = null;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_fixture = new Tokenizer( $this->_source );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\parser\Tokenizer
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testTokenizerScopeParsingWithNonPhpCurlyBrace()
    {
        $scope = 0;
        while ( ( $token = $this->_fixture->next() ) !== Tokenizer::EOF )
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

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\parser\Tokenizer
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testTokenizerSetsCorrectStartLineNumbers()
    {
        $expected = array( 2, 2, 3, 4, 4, 5, 7, 9, 10, 11 );
        $actual   = array();

        while ( ( $token = $this->_fixture->next() ) !== Tokenizer::EOF )
        {
            $actual[] = $token->startLine;
        }

        $this->assertSame( $expected, $actual );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\parser\Tokenizer
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testTokenizerSetsCorrectEndLineNumbers()
    {
        $expected = array( 2, 2, 3, 4, 4, 5, 7, 9, 10, 11 );
        $actual   = array();

        while ( ( $token = $this->_fixture->next() ) !== Tokenizer::EOF )
        {
            $actual[] = $token->endLine;
        }

        $this->assertSame( $expected, $actual );
    }
}