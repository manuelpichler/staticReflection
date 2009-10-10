<?php

namespace de\buzz2ee\ioc\sample;

class SienceFictionMovie extends Movie
{
    const TYPE = __CLASS__;

    public function __construct() { var_dump(func_get_args()); }
}
