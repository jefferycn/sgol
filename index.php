<?php
require 'lib/Slim-framework/Slim/Slim.php';
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

//$app->error(function ( Exception $e ) use ($app) {
	//SlimException::exceptionHandler($e, $app);
//});

$app->notFound(function () use ($app) {
    $error = new Error(1, 'Page Not Found', 'The page you are looking for could not be found');
    $response = new Response(null, $app);
    $response->setVersion(0);
    $response->setError($error);
    $response->display();
});

// special routes
$app->get('/reload', function () use ($app) {
    $conn = DB::getInstance($app);
    $re = $conn->save("init");
    var_dump($re);
    var_dump(time());
});

// generic route
// this kind of route will only contain uri like: /foo/bar

$app->get('/[a-z]+(/[a-zA-Z]+)', function () use ($app) {
    $uri = $app->request()->getResourceUri();
    $items = explode("/", substr($uri, 1));
    switch(count($items)) {
        case 1:
            $object = array_shift($items);
            $method = "index";
            break;
        case 2:
            $object = array_shift($items);
            $method = array_shift($items);
            break;
    }
    if(class_exists($object)) {
        $controller = new $object($app);
        if(method_exists($controller, $method)) {
            $response = $controller->$method();
            $controller->setResponse($response);
            $controller->display();
        }else {
            $app->pass();
        }
    }else {
        $app->pass();
    }
});

$app->run();
