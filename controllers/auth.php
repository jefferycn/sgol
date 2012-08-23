<?php
class auth extends Object {

    public $id = 'auth';

    public function requestToken() {
        $appId = $this->params('app_id', array(
            'validate' => array(
                'table' => 'apps',
                'field' => 'type',
            )
        ));
        $token = array();
        $token['token'] = uniqid();
        $token['app_id'] = $appId;
        $token['user_id'] = 0;
        $token['created'] = time();
        $token['updated'] = time();
        $conn = $this->getDB();
        
        if($id = $conn->insert("tokens", $token)) {
            $response = $token['token'];
        }else {
            $response = false;
        }

        return $response;
    }

    public function createToken($params) {
        $posts = array();
        $postedSignature = $params['sig'];
        $posts['app_id'] = $params['app_id'];
        $posts['time'] = $params['time'];
        $this->sigAlive($postedSignature, $posts);

        $authorization = array();
        $authorization['Authorization']['id'] = 0;
        $authorization['Authorization']['guid'] = String::uuid();
        $authorization['Authorization']['app_id'] = $posts['app_id'];
        $authorization['Authorization']['user_id'] = 0;
        $authorization['Authorization']['last_activity'] = time();
        if(!env('REMOTE_ADDR')) {
            throw new exception(__('can not get your ip addr', true), 3);
        }else {
            $authorization['Authorization']['ip'] = ip2long(env('REMOTE_ADDR'));
        }
        $authorization['Authorization']['status'] = 0;
        $authorization['Authorization']['created'] = time();
        if(!$this->Authorization->save($authorization)) {
            throw new exception(__('can not create a new session', true), 4);
        }
        return $authorization['Authorization']['guid'];
    }

    public function tokenAlive($token) {
        $conn = $this->getDB();
        // todo: add expire checking
        $options = array($token);
        $userId = $conn->getOne("auth_token_alive", $options);
        if(empty($userId)) {
            throw new exception('your token have been expired');
        }
        return $userId;
    }

    private function destoryToken($token) {
    }

    private function sigAlive($postedSignature, $posts) {
        $sig = $this->createSignature($posts);
        if($sig == $postedSignature) {
            return true;
        }else {
            throw new exception('bad request, signature is not equal');
        }
    }

    public function createSignature($posts = array()) {
        $error = array();
        if(!isset($posts['app_id']) or empty($posts['app_id'])) {
            throw new exception('bad request, application id not specified');
        }
        $app_id = $posts['app_id'];
        $conn = $this->getDB();
        $sql = "select type, secret from apps";
        $apps = $conn->getIndexedRows('type', $sql);
        if(!isset($apps[$app_id])) {
            throw new exception('bad request, application id not valid');
        }
        $posts['secret_key'] = $apps[$app_id];
        ksort($posts);
        return md5(http_build_query($posts));
    }
}
