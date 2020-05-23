<?php

declare(strict_types=1);

namespace PK\Tests\CI\Fixtures\TestRunner;

use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\RunnerInterface;

class Runner implements RunnerInterface
{
    /**
     * @var bool
     */
    private static $run = false;

    public function __invoke(): int
    {
        self::$run = true;

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories): array
    {
        return [];
    }

    public static function isRun(): bool
    {
        return self::$run;
    }

    public static function reset(): void
    {
        self::$run = false;
    }
}
