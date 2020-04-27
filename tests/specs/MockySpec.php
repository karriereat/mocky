<?php

namespace tests\specs\Karriere\Mocky;

use Karriere\Mocky\Configuration;
use Karriere\Mocky\Mocky;
use PhpSpec\ObjectBehavior;

class MockySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Configuration(__DIR__ . '/../config/mocky.php'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Mocky::class);
    }
}
