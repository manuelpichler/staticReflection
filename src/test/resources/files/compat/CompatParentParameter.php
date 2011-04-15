<?php
namespace {
    require_once __DIR__ . '/CompatParameterMagicConstantFunction.php';
    
    class CompatParentParameter extends \compat\CompatParameterMagicConstantFunction {
        function barBaz(parent $parent) {}
    }
}