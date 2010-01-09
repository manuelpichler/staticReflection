<?php
include_once 'CompatClassWithImplementedInterface.php';
include_once 'CompatInterfaceSimple.php';

class CompatClassWithSameImplementedInterface
       extends CompatClassWithImplementedInterface
    implements CompatInterfaceSimple
{
    
}