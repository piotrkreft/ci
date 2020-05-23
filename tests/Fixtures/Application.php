<?php

declare(strict_types=1);

namespace PK\Tests\CI\Fixtures;

use PK\CI\Application as BaseApplication;
use PK\Tests\CI\Fixtures\TestRunner\FixableRunner;
use PK\Tests\CI\Fixtures\TestRunner\Runner;

class Application extends BaseApplication
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
        return [
            'runner' => Runner::class,
            'fixable-runner' => FixableRunner::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRunners(): array
    {
        return parent::runners();
    }
}
