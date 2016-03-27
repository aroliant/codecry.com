<?php


#CONFIGURATION STARTS
define('DISPLAY_ERROR', true);          #DISPLAY ERRORS



#CONFIGURATION ENDS
ini_set('display_errors',DISPLAY_ERROR);

require 'vendor/autoload.php';


function include_dir($path) {
    if(is_dir($path)) {
        foreach (glob($path.'*') as $filename) {
            if(is_file($filename) && pathinfo($filename, PATHINFO_EXTENSION) == 'php') {
                require_once $filename;
            } elseif(is_dir($filename)) {
                include_dir($filename.'/');
            }
        }
    }
}

date_default_timezone_set("Etc/Greenwich");
?>
