<?php

namespace Karriere\Mocky\Models;

class State
{
    const STATE_PATH = __DIR__ . '/../../state/';

    private $activeTest;

    private $testRoutes = [];
    private $testScope;

    public static function load($scope)
    {
        $state = self::create('', $scope);

        if(file_exists(self::STATE_PATH . $scope . '.state')) {
            $state = unserialize(file_get_contents(self::STATE_PATH . $scope . '.state'));
        }

        return $state;
    }

    public static function store($state)
    {
        file_put_contents(self::STATE_PATH . $state->testScope . '.state', serialize($state));
    }

    private static function create($testName, $testScope)
    {
        $instance = new self();
        $instance->activeTest = $testName;
        $instance->testScope = $testScope;

        return $instance;
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
        self::store($this);
    }

    public function getIteration($route)
    {
        $iteration = 0;

        if (array_key_exists($route, $this->testRoutes)) {
            $iteration = $this->testRoutes[$route];
        }

        $this->testRoutes[$route] = $iteration + 1;

        self::store($this);

        return $iteration;
    }
}
