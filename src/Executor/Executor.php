<?php

declare(strict_types=1);

namespace PK\CI\Executor;

class Executor
{
    public function run(callable $runner, string ...$argv): int
    {
        $currentArgv = $_SERVER['argv'];
        $_SERVER['argv'] = array_merge(['executable'], $argv);

        try {
            $exitCode = $runner();
        } catch (\Throwable $throwable) {
            $_SERVER['argv'] = $currentArgv;

            throw $throwable;
        }

        $_SERVER['argv'] = $currentArgv;

        return $exitCode;
    }
}
