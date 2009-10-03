<?php
namespace com\example\ioc;

require_once 'BaseTest.php';

class DefaultContainerTest extends BaseTest
{
    public function testFoo()
    {
        $this->fail('...');
        new DefaultContainer();
    }
}