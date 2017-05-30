<?php

namespace Karriere\Mocky\Router;

use Karriere\Mocky\Models\State;
use Karriere\Mocky\Path;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class TestRouter extends Router
{
    /**
     * @var string
     */
    private $configDirectory;

    public function __construct($configDirectory)
    {
        $this->configDirectory = $configDirectory;
    }

    public function setup(App $app, State $state)
    {
        $testRouter = $this;
        $test = $state->getActiveTest();

        if (!empty($test)) {
            $testConfig = Path::join($this->configDirectory, 'tests', $test . '.json');

            if (file_exists($testConfig)) {
                $methods = json_decode(file_get_contents($testConfig), true);

                foreach ($methods as $method => $routes) {
                    foreach ($routes as $route => $params) {
                        $app->map([$method], $route,
                            function (Request $request, Response $response) use ($testRouter, $params, $route, $state) {
                                return $testRouter->mock(
                                    $response,
                                    $state,
                                    $route,
                                    $params['content-type'],
                                    $params['mock']
                                );
                            }
                        );
                    }
                }
            }
        }
    }

    private function mock(Response $response, State $state, $route, $contentType, $mockFile)
    {
        $mockFilePath = Path::join($this->configDirectory, 'mocks', $mockFile);

        $mock = json_decode(file_get_contents($mockFilePath));

        if (is_array($mock)) {
            $iteration = $state->getIteration($route);

            if($iteration >= count($mock)) {
                $iteration = count($mock) - 1;
            }

            $mock = $mock[$iteration];
        }

        $statusCode = $mock->status;
        $body = json_encode($mock->response);

        return $this->respond($response, $statusCode, $contentType, $body);
    }
}
