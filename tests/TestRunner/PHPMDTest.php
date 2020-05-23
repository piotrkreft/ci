<?php

declare(strict_types=1);

namespace PK\Tests\CI\TestRunner;

use PHPUnit\Framework\TestCase;
use PK\CI\Configuration\Directories;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\PHPMD;

class PHPMDTest extends TestCase
{
    /**
     * @var Locator
     */
    private $mockLocator;

    /**
     * @var PHPMD
     */
    private $runner;

    protected function setUp(): void
    {
        $this->mockLocator = $this->createMock(Locator::class);
        $this->runner = new PHPMD();

        $this->mockLocator->method('locateConfigurationFile')
            ->willReturnCallback(function (string $file): string {
                return $file;
            });
    }

    /**
     * @return mixed[][]
     */
    public function argvProvider(): array
    {
        $directories = new Directories('.', '/src', '/tests', '/bin', null);
        $onlySrc = new Directories('.', '/src', null, null, null);

        return [
            [
                $directories,
                ['/src,/tests,/bin', 'text', 'codesize'],
            ],
            [
                $onlySrc,
                ['/src', 'text', 'codesize'],
            ],
        ];
    }

    /**
     * @dataProvider argvProvider
     *
     * @param string[] $expected
     */
    public function testShouldGetArgv(Directories $directories, array $expected): void
    {
        // when
        $argv = $this->runner->getArgv($this->mockLocator, $directories);

        // then
        $this->assertEquals($expected, $argv);
    }
}
