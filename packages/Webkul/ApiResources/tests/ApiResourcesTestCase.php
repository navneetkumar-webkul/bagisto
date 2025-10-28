<?php

namespace Webkul\ApiResources\Tests;

use Tests\TestCase;
use Webkul\ApiResources\Tests\Concerns\ApiResourcesTestBench;
use Webkul\Core\Tests\Concerns\CoreAssertions;

class ApiResourcesTestCase extends TestCase
{
    use ApiResourcesTestBench, CoreAssertions;
}
