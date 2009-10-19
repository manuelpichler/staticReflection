<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\parser;

/**
 * Simple token object.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class Token
{
    /**
     * @var integer
     */
    public $offset = 0;

    /**
     * @var integer
     */
    public $type = 0;

    /**
     * @var string
     */
    public $image = null;

    /**
     * The start line number.
     *
     * @var integer
     */
    public $startLine = 0;

    /**
     * The end line number.
     *
     * @var integer
     */
    public $endLine = 0;

    /**
     * @param integer $offset
     * @param integer $type
     * @param string  $image
     * @param integer $startLine
     * @param integer $endLine
     */
    public function __construct( $offset, $type, $image, $startLine, $endLine )
    {
        $this->offset    = $offset;
        $this->type      = $type;
        $this->image     = $image;
        $this->startLine = $startLine;
        $this->endLine   = $endLine;
    }

    // @codeCoverageIgnoreStart

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s[offset=%d;type=%d;image="%s"]',
            __CLASS__,
            $this->offset,
            $this->type,
            $this->image
        );
    }

    // @codeCoverageIgnoreEnd
}