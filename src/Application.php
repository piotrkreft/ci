<?php

declare(strict_types=1);

namespace PK\CI;

use PK\CI\Command\FixCommand;
use PK\CI\Command\RunCommand;
use PK\CI\Exception\InvalidArgumentException;
use PK\CI\Executor\Executor;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\FixableRunnerInterface;
use PK\CI\TestRunner\PHPCodeSniffer;
use PK\CI\TestRunner\PHPCsFixer;
use PK\CI\TestRunner\PHPMD;
use PK\CI\TestRunner\PHPUnit;
use PK\CI\TestRunner\RunnerInterface;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    private const VERSION = '0.1.0';

    public function __construct(string $projectDir)
    {
        parent::__construct('CI', self::VERSION);

        $runners = $fixables = [];
        foreach ($this->runners() as $name => $runner) {
            $runners[$name] = new $runner();
            if (!$runners[$name] instanceof RunnerInterface) {
                throw new InvalidArgumentException('Each runner has to be of type ' . RunnerInterface::class);
            }
            if (!$runners[$name] instanceof FixableRunnerInterface) {
                continue;
            }

            $fixables[$name] = $runners[$name];
        }
        $executor = new Executor();
        $locator = new Locator($projectDir);

        $this->add(new RunCommand('run', $projectDir, $runners, $executor, $locator));
        $this->add(new FixCommand('fix', $projectDir, $fixables, $executor, $locator));
    }

    /**
     * @return string[]
     */
    protected function runners(): array
    {
        return [
            'php-cs-fixer' => PHPCsFixer::class,
            'php-cs' => PHPCodeSniffer::class,
            'phpmd' => PHPMD::class,
            'phpunit' => PHPUnit::class,
        ];
    }
}
