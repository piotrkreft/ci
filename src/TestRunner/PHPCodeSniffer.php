<?php

declare(strict_types=1);

namespace PK\CI\TestRunner;

use PHP_CodeSniffer\Runner;
use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Locator\Locator;

class PHPCodeSniffer implements FixableRunnerInterface
{
    public function __invoke(): int
    {
        $runner = new Runner();

        return $runner->{$_SERVER['code_sniffer_method']}();
    }

    /**
     * {@inheritdoc}
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories, ?bool $fix = false): array
    {
        include_once "{$directories->getProject()}/vendor/squizlabs/php_codesniffer/autoload.php";

        $config = $locator->locateConfigurationFile('ruleset.xml');
        $_SERVER['code_sniffer_method'] = $fix ? 'runPHPCBF' : 'runPHPCS';

        return array_values(array_filter(
            [
                $directories->getSrc(),
                $directories->getTests(),
                $directories->getBin(),
                "--standard=$config",
                '--extensions=php',
                '-p',
                $directories->getCache() ? "--cache={$directories->getCache()}/.php-code-sniffer.cache" : '--no-cache',
            ]
        ));
    }
}
