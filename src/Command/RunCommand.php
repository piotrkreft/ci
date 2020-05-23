<?php

declare(strict_types=1);

namespace PK\CI\Command;

use PK\CI\Configuration\Directories;
use PK\CI\Configuration\DirectoriesInterface;
use PK\CI\Exception\InvalidArgumentException;
use PK\CI\Executor\Executor;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\RunnerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var string
     */
    protected $projectDir;

    /**
     * @var RunnerInterface[]
     */
    protected $runners;

    /**
     * @var Executor
     */
    protected $executor;

    /**
     * @var Locator
     */
    protected $locator;

    /**
     * @param RunnerInterface[] $runners
     */
    public function __construct(
        string $name,
        string $projectDir,
        array $runners,
        Executor $executor,
        Locator $locator
    ) {
        $this->projectDir = $projectDir;
        $this->runners = $runners;
        $this->executor = $executor;
        $this->locator = $locator;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        foreach (array_keys($this->runners) as $name) {
            $this->addOption($name, null, InputOption::VALUE_OPTIONAL, 'Toggles runner with yes|no flag', 'yes');
        }
        Directories::addInputDefinition($this->getDefinition(), $this->projectDir);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directories = Directories::fromInput($input, $this->projectDir);

        foreach ($this->runners as $name => $runner) {
            if (!in_array($input->getOption($name), ['yes', 'no'])) {
                throw new InvalidArgumentException("Only yes|no allowed for $name option.");
            }
            if ('no' === $input->getOption($name)) {
                continue;
            }
            $argv = $this->getArgv($runner, $directories);
            $output->writeln(\PHP_EOL . "Run $name " . implode(' ', $argv) . \PHP_EOL);
            if ($code = $this->executor->run($runner, ...$argv)) {
                return $code;
            }
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgv(RunnerInterface $runner, DirectoriesInterface $directories): array
    {
        return $runner->getArgv($this->locator, $directories);
    }
}
