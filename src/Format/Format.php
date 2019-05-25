<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Format;

interface Format
{
    public const FALLBACK_FORMAT = 'd F Y';
    public const FORMAT_RELATIVE_DATE = 'relative-date';
    public const FORMAT_RELATIVE_DATETIME = 'relative-datetime';

    public function __toString(): string;

    public function __invoke(string $date): string;
}
