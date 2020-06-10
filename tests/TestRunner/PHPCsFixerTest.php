<?php

declare(strict_types=1);

namespace PK\Tests\CI\TestRunner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PK\CI\Configuration\Directories;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\PHPCsFixer;

class PHPCsFixerTest extends TestCase
{
    /**
     * @var Locator|MockObject
     */
    private $mockLocator;

    /**
     * @var PHPCsFixer
     */
    private $runner;

    protected function setUp(): void
    {
        $this->mockLocator = $this->createMock(Locator::class);
        $this->runner = new PHPCsFixer();

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
    public function testShouldGetArgv(bool $fix, Directories $directories, array $expected): void
    {
        // when
        $argv = $this->runner->getArgv($this->mockLocator, $directories, $fix);

        // then
        $this->assertEquals($expected, $argv);
    }

    /**
     * @return mixed[][]
     */
    public function argvProvider(): array
    {
        $directories = new Directories('.', '/src', '/tests', '/bin', null);
        $directoriesWithCache = new Directories('.', '/src', null, null, '/cache');

        return [
            [
                false,
                $directories,
                ['fix', '/src', '/tests', '/bin', '--dry-run', '--using-cache=no', '-v', '--config=.php_cs'],
            ],
            [
                true,
                $directoriesWithCache,
                ['fix', '/src', '--cache-file=/cache/.php_cs.cache', '-v', '--config=.php_cs'],
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
        $this->assertEquals(['fix', '/src', '--dry-run', '--using-cache=no', '-v', '--config=.php_cs'], $argv);
    }
}
