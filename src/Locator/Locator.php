<?php

declare(strict_types=1);

namespace PK\CI\Locator;

use PK\CI\Exception\NoConfigurationFileFound;

class Locator
{
    /**
     * @var string
     */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @throws NoConfigurationFileFound
     */
    public function locateConfigurationFile(string $file): string
    {
        if (file_exists($config = "$this->projectDir/$file")) {
            return $config;
        }
        if (file_exists($config = "$this->projectDir/$file.dist")) {
            return $config;
        }
        if (file_exists($config = "$this->projectDir/vendor/piotrkreft/ci/$file.dist")) {
            return $config;
        }

        throw new NoConfigurationFileFound("There's no file to match requested $file");
    }
}
