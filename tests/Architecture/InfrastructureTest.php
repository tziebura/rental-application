<?php

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class InfrastructureTest
{
    public function test_infrastructure_should_not_interact_with_domain(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Infrastructure'))
            ->excluding(Selector::namespace('App\Infrastructure\Persistence'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Domain'),
            );
    }
}