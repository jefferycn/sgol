<?php

class Error {
    public $code;
    public $title;
    public $message;
	
	public function __construct($code, $title, $message) {
        $this->setCode($code);
        $this->setTitle($title);
        $this->setMessage($message);
    }
	
 	public function setCode($code) {
		$this->code = intval($code);
	}
    
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
    public static function errorFromException(Exception $exception) {
        $error = new Error($exception->getCode(), get_class($exception), $exception->getMessage());
        return $error;
    }
    
}
