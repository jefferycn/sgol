<?php
require 'Slim/Slim.php';
$app = new Slim();

$app->add(new Slim_Middleware_SessionCookie(array(
    'expires' => '7 days 20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'sgolabc123',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

if(file_exists('config/config.php')) {
    require_once 'config/config.php';
}else {
    require_once 'config/config.sample.php';
}

$app->error(function ( Exception $e ) use ($app) {
	SlimException::exceptionHandler($e, $app);
});

$app->notFound(function () use ($app) {
    $error = new Error(1, 'Page Not Found', 'The page you are looking for could not be found');
    $response = new Response(null, $app);
    $response->setVersion(0);
    $response->setError($error);
    $response->display();
});

$app->get('/', function () use ($app) {
    $users = new users($app);
    if($user = $users->isLoggedIn()) {
        $view = $app->view();
        $token = $_SESSION['token'];
        $app->config('token', $token);
        $games = new games($app);
        $view->setData('gameTypes', $games->getTypes());
        $view->setData('games', $games->getAvailableGames());
        $view->setData('user', $user);
        $view->setData('token', $token);
        $app->render("index.tpl");
    }else {
        $app->response()->redirect('/login');
    }
});

$app->get('/login', function () use ($app) {
    $app->render("login.tpl");
});


$app->get('/auth(/:api)', function ($api = false) use ($app) {
    if($api) {
        $users = new users($app);
        $auth = $users->auth();
        $users->setResponse($auth);
        $users->display();
        exit;
    }else {
        $app->config('app_id', 'web');
        $auth = new auth($app);
        $token = $auth->requestToken();
        $_SESSION['token'] = $token;
        $app->config('token', $token);
        $users = new users($app);
        $auth = $users->auth();
        if($auth) {
            $_SESSION['user'] = $auth;
            $app->response()->redirect('/');
        }else {
            $app->response()->redirect('/login');
        }
    }
});

$app->get('/users/info(/:api)', function ($api = false) use ($app) {
    $users = new users($app);
    $users->info();
});

$app->get('/games/:action(/:api)', function ($action, $api = false) use ($app) {
    if($api) {
        $games = new games($app);
        $data = $games->$action();
        $games->setResponse($data);
        $games->display();
        exit;
    }else {
        $app->config('token', $_SESSION['token']);
        $games = new games($app);
        $data = $games->$action();
        switch($action) {
            case 'close':
            case 'finish':
            case 'oneonone':
                $app->response()->redirect('/');
                break;
            case 'join':
            case 'create':
                $app->response()->redirect('/games/myrole?id=' . $data);
                break;
            case 'myrole':
                $view = $app->view();
                $view->setData('role', $data);
                $app->render("myrole.tpl");
                break;
            case 'ranking':
                $view = $app->view();
                $view->setData('items', $data);
                $app->render("ranking.tpl");
                break;
            case 'getTypes':
                $view = $app->view();
                $view->setData('types', $data);
                $app->render("new.tpl");
                break;
            case 'observe':
                $view = $app->view();
                $view->setData($data);
                $app->render("observe.tpl");
                break;
            case 'kill':
                $view = $app->view();
                $view->setData($data);
                $app->render("kill.tpl");
                break;
            case 'killed':
            case 'open':
                $app->response()->redirect('/games/myrole?id=' . $data);
                break;
            default:
        }
    }
});

$app->get('/requestToken/api', function () use ($app) {
    $auth = new auth($app);
    $token = $auth->requestToken();
    $auth->setResponse($token);
    $auth->display();
    exit;
});

$app->get('/reload', function () use ($app) {
    $reload = new reload($app);
    $reload->run();
});

$app->run();
