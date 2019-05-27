<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Middleware;

use PHPUnit\Framework\MockObject\MockObject;

trait GetsNextCallable
{
    private function getNextCallable(): MockObject
    {
        return $this->getMockForAbstractClass(NextCallable::class);
    }
}

interface NextCallable
{
    public function __invoke(string $result, string $strategy): string;
}

