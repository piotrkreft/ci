<?php

declare(strict_types=1);

namespace PK\Tests\CI\Command;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PK\CI\Command\FixCommand;
use PK\CI\Configuration\Directories;
use PK\CI\Exception\InvalidArgumentException;
use PK\CI\Executor\Executor;
use PK\CI\Locator\Locator;
use PK\CI\TestRunner\FixableRunnerInterface;
use Symfony\Component\Console\Tester\CommandTester;

class FixCommandTest extends TestCase
{
    /**
     * @var FixableRunnerInterface|MockObject
     */
    private $mockRunnerOne;

    /**
     * @var FixableRunnerInterface|MockObject
     */
    private $mockRunnerTwo;

    /**
     * @var Executor|MockObject
     */
    private $mockExecutor;

    /**
     * @var Locator|MockObject
     */
    private $mockLocator;

    /**
     * @var CommandTester
     */
    private $tester;

    protected function setUp(): void
    {
        $this->mockRunnerOne = $this->createMock(FixableRunnerInterface::class);
        $this->mockRunnerTwo = $this->createMock(FixableRunnerInterface::class);
        $this->mockExecutor = $this->createMock(Executor::class);
        $this->mockLocator = $this->createMock(Locator::class);

        $this->tester = new CommandTester(
            new FixCommand(
                'name',
                '.',
                ['one' => $this->mockRunnerOne, 'two' => $this->mockRunnerTwo],
                $this->mockExecutor,
                $this->mockLocator
            )
        );
    }

    public function testShouldExecute(): void
    {
        // given
        $this->mockRunnerOne
            ->method('getArgv')
            ->with($this->mockLocator, $this->isInstanceOf(Directories::class), true)
            ->willReturn(['one']);
        $this->mockRunnerTwo
            ->method('getArgv')
            ->with($this->mockLocator, $this->isInstanceOf(Directories::class), true)
            ->willReturn(['two']);
        $this->mockExecutor
            ->method('run')
            ->withConsecutive([$this->mockRunnerOne, 'one'], [$this->mockRunnerTwo, 'two']);

        // when
        $exitCode = $this->tester->execute([]);

        // then
        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Run one', $this->tester->getDisplay());
        $this->assertStringContainsString('Run two', $this->tester->getDisplay());
    }

    public function testShouldExecuteOnlyEnableRunner(): void
    {
        // given
        $this->mockRunnerTwo
            ->method('getArgv')
            ->with($this->mockLocator, $this->isInstanceOf(Directories::class), true)
            ->willReturn(['two']);
        $this->mockExecutor
            ->expects($this->once())
            ->method('run')
            ->with($this->mockRunnerTwo, 'two');

        // when
        $exitCode = $this->tester->execute(['--one' => 'no']);

        // then
        $this->assertEquals(0, $exitCode);
        $this->assertStringNotContainsString('Run one', $this->tester->getDisplay());
        $this->assertStringContainsString('Run two', $this->tester->getDisplay());
    }

    public function testShouldStopExecutionAndReturnExitCode(): void
    {
        // given
        $this->mockRunnerOne
            ->method('getArgv')
            ->with($this->mockLocator, $this->isInstanceOf(Directories::class), true)
            ->willReturn(['one']);
        $this->mockExecutor
            ->expects($this->once())
            ->method('run')
            ->with($this->mockRunnerOne, 'one')
            ->willReturn(12);

        // when
        $exitCode = $this->tester->execute([]);

        // then
        $this->assertEquals(12, $exitCode);
    }

    public function testShouldThrowExceptionForInvalidArgumentValue(): void
    {
        // given
        $this->mockExecutor
            ->expects($this->never())
            ->method('run');
        $this->expectException(InvalidArgumentException::class);

        // when
        $exitCode = $this->tester->execute(['--one' => 'maybe']);

        $this->assertEquals(0, $exitCode);
    }
}
