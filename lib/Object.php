<?php

class Object {
    protected $app;
	protected $response;
	protected $userId;
	
    public function __construct($app) {
        $this->app = $app;
        $response = $this->app->response();
		$response['Content-Type'] = 'text/html; charset=utf-8';
    }
    
    protected function getID() {
        return $this->id;
    }
    
    protected function initResponse() {
        if (!isset($this->response)) {
            $this->response = new Response($this->getID(), $this->app);
        }
    }
    
    public function setResponse($response) {
        $this->initResponse();
        $this->response->setResponse($response);
    }
  
    public function display() {
        $this->initResponse();
        $this->response->display();
    }
    
    protected function params($key = false, $options = array()) {
        if ($key === false) {
            $data = $this->app->request()->params();
        } else {
            $data = $this->app->request()->params($key);
        }
        if(isset($options['default']) && empty($data)) {
            return $options['default'];
        }
        if(empty($data)) {
            $data = $this->app->config($key);
        }
        if(empty($data)) {
            if(isset($options['noexception'])) {
                return false;
            }else {
                throw new exception("Request param $key not found");
            }
        }
        if(!is_array($data) && isset($options['validate']) && is_array($options['validate'])) {
            $table = $options['validate']['table'];
            $field = $options['validate']['field'];
            $conn = $this->getDB();
            $sql = "select count(*) from $table where $field = ?";
            $queryOption = array($data);
            if($conn->getOne($sql, $queryOption)) {
                return $data;
            }else {
                return false;
            }
        }
        return $data;
    }

    protected function getDB() {
        return DB::getInstance($this->app);
    }

    protected function validateToken($token) {
        $auth = new auth($this->app);
        if($this->userId = $auth->tokenAlive($token)) {
            return true;
        }else {
            return false;
        }
    }
}
