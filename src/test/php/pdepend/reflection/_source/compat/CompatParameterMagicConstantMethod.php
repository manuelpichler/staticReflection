<?php
namespace compat {
    class CompatParameterMagicConstantMethod {
        public function fooBar( $foo = __METHOD__ ) {}
    }
}