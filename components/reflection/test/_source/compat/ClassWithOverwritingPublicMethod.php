<?php
include_once 'ClassWithOverwrittenProtectedMethod.php';

class ClassWithOverwritingPublicMethod extends ClassWithOverwrittenProtectedMethod
{
    public function fooBar()
    {

    }
}