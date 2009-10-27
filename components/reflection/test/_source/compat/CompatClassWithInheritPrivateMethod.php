<?php
include_once 'CompatClassWithPrivateMethod.php';

class CompatClassWithInheritPrivateMethod extends CompatClassWithPrivateMethod
{
    public function fooBar()
    {

    }

    private function _foo()
    {

    }
}