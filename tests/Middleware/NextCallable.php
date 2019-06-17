<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Middleware;

interface NextCallable
{
    public function __invoke(string $result, string $strategy): string;
}