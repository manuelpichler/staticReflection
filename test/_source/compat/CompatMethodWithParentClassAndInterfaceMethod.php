<?php
class CompatMethodWithParentClassAndInterfaceMethod
       extends CompatMethodInClassWithoutParent
    implements CompatMethodInInterfaceWithoutParent
{
    public function foo() {}
}