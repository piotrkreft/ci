<?php

declare(strict_types=1);

namespace PK\Tests\CI\Locator;

use PHPUnit\Framework\TestCase;
use PK\CI\Exception\NoConfigurationFileFound;
use PK\CI\Locator\Locator;

class LocatorTest extends TestCase
{
    /**
     * @var Locator
     */
    private $locator;

    protected function setUp(): void
    {
        $this->locator = new Locator(__DIR__ . '/Fixtures');
    }

    /**
     * @dataProvider filesProvider
     */
    public function testShouldLocateConfigurationFile(string $file, string $exptected): void
    {
        // when
        $found = $this->locator->locateConfigurationFile($file);

        // then
        $this->assertEquals($exptected, $found);
    }

    /**
     * @return string[][]
     */
    public function filesProvider(): array
    {
        return [
            ['conf', __DIR__ . '/Fixtures/conf'],
            ['dist_conf', __DIR__ . '/Fixtures/dist_conf.dist'],
            ['default', __DIR__ . '/Fixtures/vendor/piotrkreft/ci/default.dist'],
        ];
    }

    public function testShouldThrowExceptionWhenNoConfigurationFileFound(): void
    {
        // given
        $this->expectException(NoConfigurationFileFound::class);

        // when
        $this->locator->locateConfigurationFile('not_existsing');
    }
}
