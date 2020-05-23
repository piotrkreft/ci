<?php

declare(strict_types=1);

namespace PK\Tests\CI\Fixtures\TestRunner;

use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\FixableRunnerInterface;

class FixableRunner implements FixableRunnerInterface
{
    /**
     * @var bool
     */
    private static $fix = false;

    /**
     * @var bool
     */
    private static $run = false;

    /**
     * @var bool
     */
    private $shouldFix = false;

    public function __invoke(): int
    {
        if ($this->shouldFix) {
            self::$fix = true;

            return 0;
        }

        self::$run = true;

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories, ?bool $fix = false): array
    {
        $this->shouldFix = $fix;

        return [];
    }

    public static function isRun(): bool
    {
        return self::$run;
    }

    public static function isFix(): bool
    {
        return self::$fix;
    }

    public static function reset(): void
    {
        self::$run = false;
        self::$fix = false;
    }
}
