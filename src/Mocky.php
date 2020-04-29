<?php

namespace Karriere\Mocky;

use Karriere\Mocky\Router\MockyRouter;
use Karriere\Mocky\Router\TestRouter;
use Slim\App;
use Slim\Error\Renderers\JsonErrorRenderer;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

class Mocky
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var Configuration
     */
    private $config;

    public function __construct(Configuration $config)
    {
        $this->app = AppFactory::create();
        $this->setErrorHandler();

        $this->config = $config;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();
        $scope = $request->getHeader('Mocky-Scope');

        if (empty($scope)) {
            $scope = 'default';
        } else {
            $scope = reset($scope);
        }

        $state = (new StateManager($config))->load($scope);

        (new TestRouter($this->config->directory))->setup($this->app, $state);
        (new MockyRouter())->setup($this->app, $state);
    }

    public function run()
    {
        $this->app->run();
    }

    private function setErrorHandler()
    {
        $errorMiddleware = $this->app->addErrorMiddleware(false, false, false);
        $errorHandler = $errorMiddleware->getDefaultErrorHandler();
        $errorHandler->registerErrorRenderer('application/vnd.api+json', JsonErrorRenderer::class);
    }
}
