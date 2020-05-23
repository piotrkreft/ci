<?php

declare(strict_types=1);

namespace PK\Tests\CI;

use PHPUnit\Framework\TestCase;
use PK\CI\Application as ActualApplication;
use PK\CI\Exception\InvalidArgumentException;
use PK\Tests\CI\Fixtures\Application;
use PK\Tests\CI\Fixtures\InvalidApplication;
use PK\Tests\CI\Fixtures\TestRunner\FixableRunner;
use PK\Tests\CI\Fixtures\TestRunner\Runner;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApplicationTest extends TestCase
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var ApplicationTester
     */
    private $tester;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->application->setAutoExit(false);

        $this->tester = new ApplicationTester($this->application);
    }

    protected function tearDown(): void
    {
        Runner::reset();
        FixableRunner::reset();
    }

    public function testShouldRun(): void
    {
        // when
        $this->tester->run(['run']);

        // then
        $this->assertTrue(Runner::isRun());
        $this->assertTrue(FixableRunner::isRun());
        $this->assertFalse(FixableRunner::isFix());
    }

    public function testShouldFix(): void
    {
        // when
        $this->tester->run(['fix']);

        // then
        $this->assertFalse(Runner::isRun());
        $this->assertFalse(FixableRunner::isRun());
        $this->assertTrue(FixableRunner::isFix());
    }

    public function testShouldThrowExceptionWhenInvalidRunnerClassPassed(): void
    {
        // given
        $this->expectException(InvalidArgumentException::class);

        // when
        new InvalidApplication();
    }

    public function testShouldNotRunRunners(): void
    {
        // given
        $application = new ActualApplication('.');
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);

        // when
        $tester->run([
            'run',
            '--php-cs-fixer' => 'no',
            '--php-cs' => 'no',
            '--phpmd' => 'no',
            '--phpunit' => 'no',
        ]);

        // then
        $this->assertEquals('', $tester->getDisplay());
    }
}
