<?php

namespace Karriere\Mocky;

use Karriere\Mocky\Router\MockyRouter;
use Karriere\Mocky\Router\TestRouter;
use Slim\App;

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
        $this->app = new App();

        $this->config = $config;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $request = $this->app->getContainer()->get('request');
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
}
