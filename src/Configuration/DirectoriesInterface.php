<?php

declare(strict_types=1);

namespace PK\CI\Configuration;

interface DirectoriesInterface
{
    public function getProject(): string;

    public function getSrc(): string;

    public function getTests(): ?string;

    public function getBin(): ?string;

    public function getCache(): ?string;
}
