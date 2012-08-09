<?php

class Response {
    protected $app;
    public $id;
    public $version = 1;
	public $error;
	public $response;
    public $count;
	
    public function __construct($id = null, $app = null) {
        if (isset($id)) {
            $this->id = $id;
        }
        
        if (isset($app)) {
            $this->app = $app;
        }
        
        $this->response = new stdClass();
    }
	
    public function setVersion($version) {
        $this->version = intval($version);
    }
    
    public function setResponse($response) {
        $this->count = count($response);
        $this->response = $response;
    }
    
    public function setError(Error $error) {
        $this->error = $error;
    }
    
    public function display() {
		$response = $this->app->response();
		$response['Content-Type'] = 'application/json';
		$response['X-Powered-By'] = 'Slim';
        echo json_encode($this);
        exit;
    }
}
