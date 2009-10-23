<?php
class InvalidUnclosedMethodScope
{
    public function foo()
    {
        if ( $this->foo )
        {
            
        }
    }

    public function bar()
    {