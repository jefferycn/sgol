<?php
class users extends Object {

    public $id = 'users';

    public function info() {
        $token = $this->params('token');
        $sql = "SELECT
            a.id as id, username, email, first_name, last_name
            FROM users a, tokens b
            WHERE b.token = ? and a.id = b.user_id";
        $options = array($token);
        $conn = $this->getDB();
        $conn->setQuery($sql);
        $conn->setOptions($options);
        $row = $conn->getRow();
        if($row) {
            $auth = $row;
        }else {
            throw new exception("Token expired or not valid");
        }
    	//out put resulte
    	$this->setResponse($auth);
    	$this->display();
    }

    public function isLoggedIn() {
        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] > 0) {
            return $_SESSION['user'];
        }else {
            return false;
        }
    }

    public function auth() {
        $username = $this->params('username');
        $password = $this->params('password');
        $token = $this->params('token');

        $passwd = md5($password);
        $sql = "SELECT id, username, email, first_name, last_name FROM users WHERE username = ? and password = ?";
        $options = array($username, $passwd);
        $conn = $this->getDB();
        $conn->setQuery($sql);
        $conn->setOptions($options);
        $row = $conn->getRow();
        if($row) {
            $id = $row['id'];
            $sql = "update tokens set user_id = ? where token = ?";
            $options = array($id, $token);
            $conn = $this->getDB();
            $conn->setQuery($sql);
            $conn->setOptions($options);
            if($conn->save()) {
                $auth = $row;
            }else {
                $auth = false;
            }
        }else {
            throw new exception("Username or Password wrong");
        }

        return $auth;
    }
}
