<?php

declare(strict_types=1);

namespace PK\Tests\CI\TestRunner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PK\CI\Configuration\Directories;
use PK\CI\Exception\NoConfigurationFileFound;
use PK\CI\Exception\RunException;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\PHPUnit;

class PHPUnitTest extends TestCase
{
    /**
     * @var Locator|MockObject
     */
    private $mockLocator;

    /**
     * @var PHPUnit
     */
    private $runner;

    protected function setUp(): void
    {
        $this->mockLocator = $this->createMock(Locator::class);
        $this->runner = new PHPUnit();
    }

    /**
     * @dataProvider argvProvider
     *
     * @param string[] $expected
     */
    public function testShouldGetArgv(Directories $directories, array $expected): void
    {
        // given
        $this->mockLocator->method('locateConfigurationFile')
            ->willReturnCallback(function (string $file): string {
                return $file;
            });

        // when
        $argv = $this->runner->getArgv($this->mockLocator, $directories);

        // then
        $this->assertEquals($expected, $argv);
    }

    /**
     * @return mixed[][]
     */
    public function argvProvider(): array
    {
        $directories = new Directories('.', '/src', '/tests', '/bin', null);
        $directoriesWithCache = new Directories('.', '/src', '/tests', '/bin', '/cache');

        return [
            [
                $directories,
                ['--configuration=phpunit.xml', '--do-not-cache-result', '/tests'],
            ],
            [
                $directoriesWithCache,
                ['--configuration=phpunit.xml', '--cache-result-file=/cache/.phpunit.result.cache', '/tests'],
            ],
        ];
    }

    public function testShouldGetArgvWhenNoConfigurationFile(): void
    {
        // given
        $this->mockLocator->method('locateConfigurationFile')
            ->willThrowException(new NoConfigurationFileFound());

        // when
        $argv = $this->runner->getArgv(
            $this->mockLocator,
            new Directories('.', '/src', '/tests', null, null)
        );

        // then
        $this->assertEquals(
            ['--do-not-cache-result', '/tests'],
            $argv
        );
    }

    public function testShouldThrowExceptionWhenNoTestsDirectory(): void
    {
        // given
        $directories = new Directories('.', '/src', null, null, null);
        $this->expectException(RunException::class);

        // when
        $this->runner->getArgv($this->mockLocator, $directories);
    }
}
