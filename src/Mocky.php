<?php

namespace Karriere\Mocky;

use Karriere\Mocky\Models\State;
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
     * @var string
     */
    private $configDirectory;

    public function __construct($configDirectory)
    {
        $this->app = new App();

        $this->configDirectory = $configDirectory;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $state = State::load();

        (new TestRouter($this->configDirectory))->setup($this->app, $state);
        (new MockyRouter($this->configDirectory))->setup($this->app, $state);
    }

    public function run()
    {
        $this->app->run();
    }
}
