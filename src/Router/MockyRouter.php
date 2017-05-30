<?php

namespace Karriere\Mocky\Router;

use Karriere\Mocky\Models\State;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class MockyRouter extends Router
{
    public function setup(App $app, State $state)
    {
        $mockyRouter = $this;

        $app->get('/setup/{name}', function (Request $request, Response $response) use ($state, $mockyRouter) {
            $testName = $request->getAttribute('name', null);

            $state->setActiveTest($testName);

            $body = json_encode([
                'status' => 200,
                'message' => sprintf('tests scope switched to %s', $testName),
            ]);

            return $mockyRouter->respond($response, 200, 'application/json', $body);
        });
    }
}
