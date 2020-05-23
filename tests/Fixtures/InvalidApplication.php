<?php

declare(strict_types=1);

namespace PK\Tests\CI\Fixtures;

use PK\CI\Application;

class InvalidApplication extends Application
{
    public function __construct()
    {
        parent::__construct('.');
    }

    /**
     * @return string[]
     */
    protected function runners(): array
    {
        return [\stdClass::class];
    }
}
