<?php

declare(strict_types=1);

namespace PK\Tests\CI\TestRunner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PK\CI\Configuration\Directories;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\PHPCodeSniffer;

class PHPCodeSnifferTest extends TestCase
{
    /**
     * @var Locator|MockObject
     */
    private $mockLocator;

    /**
     * @var PHPCodeSniffer
     */
    private $runner;

    protected function setUp(): void
    {
        $this->mockLocator = $this->createMock(Locator::class);
        $this->runner = new PHPCodeSniffer();

        $this->mockLocator->method('locateConfigurationFile')
            ->willReturnCallback(function (string $file): string {
                return $file;
            });
    }

    /**
     * @dataProvider argvProvider
     *
     * @param string[] $expected
     */
    public function testShouldGetArgv(bool $fix, Directories $directories, string $method, array $expected): void
    {
        // when
        $argv = $this->runner->getArgv($this->mockLocator, $directories, $fix);

        // then
        $this->assertEquals($expected, $argv);
        $this->assertEquals($method, $_SERVER['code_sniffer_method']);
    }

    /**
     * @return mixed[][]
     */
    public function argvProvider(): array
    {
        $directories = new Directories($projectDir = __DIR__ . '/../..', '/src', '/tests', '/bin', null);
        $directoriesWithCache = new Directories($projectDir, '/src', null, null, '/cache');

        return [
            [
                false,
                $directories,
                'runPHPCS',
                ['/src', '/tests', '/bin', '--standard=ruleset.xml', '--extensions=php', '-p', '--no-cache'],
            ],
            [
                true,
                $directories,
                'runPHPCBF',
                ['/src', '/tests', '/bin', '--standard=ruleset.xml', '--extensions=php', '-p', '--no-cache'],
            ],
            [
                true,
                $directoriesWithCache,
                'runPHPCBF',
                ['/src', '--standard=ruleset.xml', '--extensions=php', '-p', '--cache=/cache/.php-code-sniffer.cache'],
            ],
        ];
    }

    public function testShouldGetArgvWithDefault(): void
    {
        // given
        $directories = new Directories($projectDir = __DIR__ . '/../..', '/src', null, null, null);

        // when
        $argv = $this->runner->getArgv($this->mockLocator, $directories);

        // then
        $this->assertEquals(['/src', '--standard=ruleset.xml', '--extensions=php', '-p', '--no-cache'], $argv);
        $this->assertEquals('runPHPCS', $_SERVER['code_sniffer_method']);
    }
}
