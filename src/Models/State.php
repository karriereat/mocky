<?php

namespace Karriere\Mocky\Models;

class State
{
    const STATE_FILE = __DIR__ . '/../../state/default.state';

    private $activeTest;

    private $testRoutes = [];

    public static function load()
    {
        $state = self::create('');

        if(file_exists(self::STATE_FILE)) {
            $state = unserialize(file_get_contents(self::STATE_FILE));
        }

        return $state;
    }

    public static function store($state)
    {
        file_put_contents(self::STATE_FILE, serialize($state));
    }

    private static function create($testName)
    {
        $instance = new self();
        $instance->activeTest = $testName;

        return $instance;
    }

    public function getActiveTest()
    {
        return $this->activeTest;
    }

    public function setActiveTest($test)
    {
        $this->activeTest = $test;
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
