<?php

    session_start();

    require '../vendor/autoload.php';

    $app =  new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ]);

    $container = $app->getContainer();

    $container['csrf'] = function($c){
        $guard = new \Slim\Csrf\Guard();
        $guard->setFailureCallable(function ($request, $response, $next) {
            
            die("CSRF Failure, Do Something Here");

            return $next($request, $response);
        });
        return $guard;
    };

    $container['view'] = function ($container) {
        $view = new \Slim\Views\Twig(__DIR__.'/../views',[
            'cache' => false
        ]);

        $basePath = rtrim(str_ireplace('index.php','',$container['request']->getUri()->getBasePath()),'/');
        $view->addExtension(new Slim\Views\TwigExtension($container['router'],$basePath));
        $view->addExtension(new App\Middleware\CsrfExtension($container['csrf']));

        return $view;
    };

    $container['db'] = function(){
        // return new PDO('mysql:host=localhost;dbname=esportsetiopia','root','');
        return new PDO('mysql:host=localhost;dbname=esportse_esports','esportse_esports','Peter@619');
    };

    $container['flash'] = function(){
        return new \Slim\Flash\Messages;
    };

    //$app->add($container->get('csrf'));

    require '../routes/router.php';

?>