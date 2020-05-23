<?php

declare(strict_types=1);

namespace PK\CI\TestRunner;

use PHPMD\TextUI\Command;
use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Locator\Locator;

class PHPMD implements RunnerInterface
{
    public function __invoke(): int
    {
        return Command::main($_SERVER['argv']);
    }

    /**
     * {@inheritdoc}
     */
    public function getArgv(Locator $locator, DirectoriesInterface $directories): array
    {
        $dirs = implode(
            ',',
            array_filter([
                $directories->getSrc(),
                $directories->getTests(),
                $directories->getBin(),
            ])
        );

        return [
            $dirs,
            'text',
            'codesize',
        ];
    }
}
