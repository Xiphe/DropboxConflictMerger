<?php
spl_autoload_register(
    function ($class) {
        if (strpos($class, 'Diff') === 0) {
            $path = dirname(__FILE__).DIRECTORY_SEPARATOR;
            $path .= str_replace('_', DIRECTORY_SEPARATOR, $class).'.php';

            include $path;
        }
    }
);