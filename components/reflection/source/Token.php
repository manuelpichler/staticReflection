<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

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
     * @param integer $offset
     * @param integer $type
     * @param string  $image
     */
    public function __construct( $offset, $type, $image )
    {
        $this->offset = $offset;
        $this->type   = $type;
        $this->image  = $image;
    }

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
}