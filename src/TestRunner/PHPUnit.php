<?php

declare(strict_types=1);

namespace PK\CI\TestRunner;

use PHPUnit\TextUI\Command;
use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Exception\NoConfigurationFileFound;
use PK\CI\Exception\RunException;
use PK\CI\Locator\Locator;

class PHPUnit implements RunnerInterface
{
    public function __invoke(): int
    {
        return Command::main(false);
    }

    /**
     * {@inheritdoc}
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories): array
    {
        if (!$directories->getTests()) {
            throw new RunException('PHPUnit requires tests directory to run.');
        }
        try {
            $config = "--configuration={$locator->locateConfigurationFile('phpunit.xml')}";
        } catch (NoConfigurationFileFound $exception) {
            $config = null;
        }

        return array_values(array_filter([
            $config,
            $directories->getCache() ?
                "--cache-result-file={$directories->getCache()}/.phpunit.result.cache" :
                '--do-not-cache-result',
            $directories->getTests(),
        ]));
    }
}
