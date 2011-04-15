<?php
namespace pdepend {
    class MethodWithInvalidParentClassParameter {
        public function foo(parent $parent) {}
    }
}