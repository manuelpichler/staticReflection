#!/usr/bin/env php
<?php

$project = $argv[1] . '.phar';
$output  = realpath( $argv[2] ) . '/' . $project;
$input   = realpath( $argv[3] );

$phar = new Phar( $output, 0, $project );
$phar->buildFromDirectory( $input );