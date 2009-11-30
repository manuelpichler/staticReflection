#!/usr/bin/env php
<?php

$project = $argv[1] . '.phar';
$output  = realpath( $argv[3] ) . '/' . $argv[2] . '.phar';
$input   = realpath( $argv[4] );

$phar = new Phar( $output, 0, $project );
$phar->buildFromDirectory( $input );