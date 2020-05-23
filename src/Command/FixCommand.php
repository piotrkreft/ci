<?php

declare(strict_types=1);

namespace PK\CI\Command;

use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\TestRunner\FixableRunnerInterface;
use PK\CI\TestRunner\RunnerInterface;

class FixCommand extends RunCommand
{
    /**
     * @param FixableRunnerInterface $runner
     *
     * {@inheritdoc}
     */
    protected function getArgv(RunnerInterface $runner, DirectoriesInterface $directories): array
    {
        return $runner->getArgv($this->locator, $directories, true);
    }
}
