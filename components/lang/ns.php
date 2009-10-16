<?php
namespace foo\bar {
    class X {}
}

namespace foo {
    use \foo\bar\X;

    new X;
}