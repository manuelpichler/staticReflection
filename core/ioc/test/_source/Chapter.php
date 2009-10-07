<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

/**
 * Simple test class
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 *
 * @property string $subtitle
 */
class Chapter
{
    const TYPE = __CLASS__;

    /**
     * @var string
     */
    private $_title = 'Example chapter';

    /**
     * @var integer
     */
    private $_pages = 0;

    /**
     * @var array(string=>mixed)
     */
    private $_properties = array(
        'subtitle'  =>  null
    );

    /**
     * @param string $title
     */
    public function __construct( $title )
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @return integer
     */
    public function getPages()
    {
        return $this->_pages;
    }

    /**
     * @param integer $pages
     *
     * @return void
     */
    public function setPages( $pages )
    {
        $this->_pages = $pages;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws OutOfRangeException
     */
    public function __get( $name )
    {
        if ( $this->__isset( $name ) )
        {
            return $this->_properties[$name];
        }
        throw new \OutOfRangeException( 'Unknown property ' . $name );
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function __set( $name, $value )
    {
        if ( $this->__isset( $name ) )
        {
            $this->_properties[$name] = $value;
        }
        else
        {
            throw new \OutOfRangeException( 'Unknown property ' . $name );
        }
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function __isset( $name )
    {
        return array_key_exists( $name, $this->_properties );
    }
}