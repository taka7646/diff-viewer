<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
 //   $git = new App\IO\GitCommand('$HOME/project/go/src/github.com/spf13/pflag');
    $git = new App\IO\GitCommand('$HOME/project/php/diff-viewer');
    $logs = $git->log('int.go');
    $logs = $git->ls();
    var_dump($logs);
    return $router->app->version();
});
