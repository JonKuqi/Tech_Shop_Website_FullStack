<?php


function error_handler($errno, $errstring, $errfile, $errline, $errcontext = null){

    //Mesazhi qe dergohet
    $mesazhi = "Error [$errno]: $errstring in $errfile on line $errline \n";
    error_log($mesazhi, 3, "Website-Php-functions/errorLogs.log");
    
    
   // echo $mesazhi;

    //True i bjen qe ka shku me sukses
    return true;
}

error_reporting(E_ALL);


set_error_handler("error_handler");



?>