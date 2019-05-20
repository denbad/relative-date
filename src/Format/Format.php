<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Format;

interface Format
{
    public function __toString(): string;

    public function __invoke(string $date): string;
}
