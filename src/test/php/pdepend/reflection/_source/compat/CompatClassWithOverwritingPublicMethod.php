<?php
include_once 'CompatClassWithOverwrittenProtectedMethod.php';

class CompatClassWithOverwritingPublicMethod extends CompatClassWithOverwrittenProtectedMethod
{
    public function fooBar()
    {

    }
}