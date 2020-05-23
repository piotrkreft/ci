<?php

declare(strict_types=1);

namespace PK\CI\TestRunner;

use PhpCsFixer\Console\Application;
use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Locator\Locator;

class PHPCsFixer implements FixableRunnerInterface
{
    public function __invoke(): int
    {
        $application = new Application();
        $application->setAutoExit(false);

        return $application->run();
    }

    /**
     * {@inheritdoc}
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories, ?bool $fix = false): array
    {
        $config = $locator->locateConfigurationFile('.php_cs');

        return array_values(array_filter([
            'fix',
            $directories->getSrc(),
            $directories->getTests(),
            $directories->getBin(),
            $fix ?
                null :
                '--dry-run',
            $directories->getCache() ?
                "--cache-file={$directories->getCache()}/.php_cs.cache" :
                '--using-cache=no',
            '-v',
            "--config=$config",
        ]));
    }
}
