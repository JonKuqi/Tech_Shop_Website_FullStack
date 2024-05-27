<?php

class GabimSasie extends Exception{

    private const   FINAL_MESSAGE = "Only this number of products are in depo: ";
   
    public function __construct($message = "", $code=0, Throwable $previous = null){
    
        $message =self::FINAL_MESSAGE.$message;

        parent::__construct($message, $code, $previous);
    }
    public function shfaq(){
        return __CLASS__ . "[{$this->code}]: {$this->message}\n";
    }
}


?>