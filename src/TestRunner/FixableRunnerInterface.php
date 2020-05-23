<?php

declare(strict_types=1);

namespace PK\CI\TestRunner;

use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Locator\Locator;

interface FixableRunnerInterface extends RunnerInterface
{
    /**
     * @return string[]
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories, ?bool $fix = false): array;
}
