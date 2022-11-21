<?php

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class ApplicationTest
{
    public function test_application_on_depends_on_application_and_domain_layers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Application'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Infrastructure'),
                Selector::namespace('App\Query')
            );
    }
}