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
class Author
{
    const TYPE = __CLASS__;

    /**
     * @var string
     */
    private $_firstName = null,
            $_lastName  = null;

    public function __construct( $firstName = '', $lastName = '' )
    {
        $this->_firstName = $firstName;
        $this->_lastName  = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s[firstName=%s; lastName=%s]',
            self::TYPE,
            $this->_firstName,
            $this->_lastName
        );
    }
}