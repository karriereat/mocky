<?php

namespace Karriere\Mocky\Router;

use Karriere\Mocky\Models\State;
use Slim\App;
use Slim\Http\Response;

abstract class Router
{
    /**
     * register the router routes on the slim app instance.
     *
     * @param App   $app
     * @param State $state
     *
     * @return void
     */
    abstract public function setup(App $app, State $state);

    /**
     * @param Response $response
     * @param int      $statusCode
     * @param string   $contentType
     * @param string   $body
     *
     * @return Response
     */
    protected function respond(Response $response, $statusCode, $contentType, $body)
    {
        $response->getBody()->write($body);

        return $response->withStatus($statusCode)->withHeader('Content-Type', $contentType);
    }
}
