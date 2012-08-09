<?php

class SlimException {
	public static function exceptionHandler($exception, $app) {
	    $error = Error::errorFromException($exception);
	    $response = new Response(null, $app);
	    $response->setVersion(0);
	    $response->setError($error);
	    $response->display();
	    exit();
	}
}
