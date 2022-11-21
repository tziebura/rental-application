<?php

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class QueryTest
{
    public function test_query_should_not_interact_with_any_other_layer(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Query'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Domain'),
                Selector::namespace('App\Application'),
                Selector::namespace('App\Infrastructure'),
            );
    }
}