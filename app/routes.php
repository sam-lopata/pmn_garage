<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use PmnGarage\Application\Actions\Garage\ListAction;
use PmnGarage\Application\Actions\Garage\ViewAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use PmnGarage\Application\Actions\Garage\FindByCountryAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello!');
        return $response;
    });

    $app->group('/garages', function (Group $group) {
        $group->get('', ListAction::class);
        $group->get('/country/{country}', FindByCountryAction::class);
        $group->get('/{id}', ViewAction::class);
    });
};
