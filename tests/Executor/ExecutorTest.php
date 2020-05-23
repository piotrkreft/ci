<?php

declare(strict_types=1);

namespace PK\Tests\CI\Command;

use PHPUnit\Framework\TestCase;
use PK\CI\Executor\Executor;

class ExecutorTest extends TestCase
{
    /**
     * @var Executor
     */
    private $executor;

    protected function setUp(): void
    {
        $this->executor = new Executor();
    }

    public function testShouldExecute(): void
    {
        // given
        $argv = $_SERVER['argv'];
        $callable = function (): int {
            $this->assertEquals(['executable', 'WEIRDO_VAR'], $_SERVER['argv']);

            return 15;
        };

        // when
        $code = $this->executor->run($callable, 'WEIRDO_VAR');

        // then
        $this->assertEquals(15, $code);
        $this->assertEquals($argv, $_SERVER['argv']);
    }

    public function testShouldRestoreArgvAndReThrowException(): void
    {
        // given
        $argv = $_SERVER['argv'];
        $callable = function (): void {
            throw new \Exception('Failed to execute.');
        };
        $this->expectException(\Exception::class);

        // when
        $this->executor->run($callable, 'WEIRDO_VAR');

        // then
        $this->assertEquals($argv, $_SERVER['argv']);
    }
}
