<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Formatter;

interface Formatter
{
    public function format(\DateTimeImmutable $date, string $strategy = null): string;
}