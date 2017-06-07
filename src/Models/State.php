<?php

namespace Karriere\Mocky\Models;

use Karriere\Mocky\Path;

class State
{
    const STATE_PATH = __DIR__ . '/../../state/';

    private $statePath;

    private $activeTest;

    private $testRoutes = [];
    private $testScope;

    public function __construct($statePath, $scope)
    {
        $this->statePath = $statePath;
        $this->testScope = $scope;
    }

    public function getActiveTest()
    {
        return $this->activeTest;
    }

    public function setActiveTest($testName, $testScope)
    {
        $this->activeTest = $testName;
        $this->testScope = $testScope;
        $this->testRoutes = [];
        $this->store();
    }

    public function getIteration($route)
    {
        $iteration = 0;

        if (array_key_exists($route, $this->testRoutes)) {
            $iteration = $this->testRoutes[$route];
        }

        $this->testRoutes[$route] = $iteration + 1;

        $this->store();

        return $iteration;
    }

    private function store()
    {
        $file = Path::join($this->statePath, $this->testScope . '.state');
        file_put_contents($file, serialize($this));
    }
}
