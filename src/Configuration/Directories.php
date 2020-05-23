<?php

declare(strict_types=1);

namespace PK\CI\Configuration;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class Directories implements DirectoriesInterface
{
    private const SRC = 'src-dir';
    private const TESTS = 'tests-dir';
    private const BIN = 'bin-dir';
    private const CACHE = 'cache-dir';

    /**
     * @var string
     */
    private $project;

    /**
     * @var string
     */
    private $src;

    /**
     * @var string|null
     */
    private $tests;

    /**
     * @var string|null
     */
    private $bin;

    /**
     * @var string|null
     */
    private $cache;

    public function __construct(string $project, string $src, ?string $tests, ?string $bin, ?string $cache)
    {
        $this->project = $project;
        $this->src = $src;
        $this->tests = $tests;
        $this->bin = $bin;
        $this->cache = $cache;
    }

    public function getProject(): string
    {
        return $this->project;
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public function getTests(): ?string
    {
        return $this->tests;
    }

    public function getBin(): ?string
    {
        return $this->bin;
    }

    public function getCache(): ?string
    {
        return $this->cache;
    }

    public static function addInputDefinition(InputDefinition $inputDefinition, string $projectDir): void
    {
        $inputDefinition->addOptions([
            new InputOption(
                '--' . self::SRC,
                null,
                InputOption::VALUE_REQUIRED,
                'Source dir of a project.',
                "$projectDir/src"
            ),
            new InputOption(
                '--' . self::TESTS,
                null,
                InputOption::VALUE_REQUIRED,
                'Tests dir of a project.',
                is_dir($testDir = "$projectDir/tests") ? $testDir : null
            ),
            new InputOption(
                '--' . self::BIN,
                null,
                InputOption::VALUE_REQUIRED,
                'Binaries dir of a project.',
                is_dir($binDir = "$projectDir/bin") ? $binDir : null
            ),
            new InputOption(
                '--' . self::CACHE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Cache dir of a project.'
            ),
        ]);
    }

    public static function fromInput(InputInterface $input, string $projectDir): self
    {
        return new self(
            $projectDir,
            $input->getOption(self::SRC),
            $input->getOption(self::TESTS),
            $input->getOption(self::BIN),
            $input->getOption(self::CACHE)
        );
    }
}
