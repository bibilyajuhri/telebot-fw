<?php 
// define your autoloader
function the_autoloader($classname) {
    // $classname  will 'X\X' in the example
    $filename = str_replace('\\', '/', $classname) . '.php';
    require $filename;
}

// register the autoloader
spl_autoload_register('the_autoloader');