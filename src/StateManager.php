<?php

namespace Karriere\Mocky;

use Karriere\Mocky\Models\State;

class StateManager
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        $this->initializeStateDirectory();
    }

    public function load($scope)
    {
        $statePath = $this->configuration->statePath;
        $stateFile = Path::join($statePath, $scope . '.state');

        if (file_exists($stateFile)) {
            $state = unserialize(file_get_contents($stateFile));
        } else {
            $state = new State($statePath, $scope);
        }

        return $state;
    }

    private function initializeStateDirectory()
    {
        if (!file_exists($this->configuration->statePath)) {
            mkdir($this->configuration->statePath);
        }
    }
}
