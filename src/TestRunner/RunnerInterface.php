<?php

declare(strict_types=1);

namespace PK\CI\TestRunner;

use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Exception\RunException;
use PK\CI\Locator\Locator;

interface RunnerInterface
{
    /**
     * @throws RunException
     */
    public function __invoke(): int;

    /**
     * @return string[]
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories): array;
}
