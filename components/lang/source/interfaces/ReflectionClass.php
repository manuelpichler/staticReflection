<?php
namespace de\buzz2ee\lang\interfaces;

interface ReflectionClass
{
    function getName();

    function getMethods();

    function getAnnotations();
}