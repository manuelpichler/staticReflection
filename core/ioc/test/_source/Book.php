<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

/**
 * Simple test class
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class Book
{
    const TYPE = __CLASS__;

    /**
     * @var Author
     */
    private $_author = null;

    public function __construct( Author $author = null )
    {
        $this->_author = $author;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->_author;
    }
}