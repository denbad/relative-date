<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Format;

final class RelativeDateTimeFormat implements Format
{
    private $fallbackFormat;

    public function __construct(string $fallbackFormat = Format::FALLBACK_FORMAT)
    {
        $this->fallbackFormat = $fallbackFormat;
    }

    public function __toString(): string
    {
        return Format::FORMAT_RELATIVE_DATETIME;
    }

    public function __invoke(string $date): string
    {
        $date = new \DateTimeImmutable($date);

        if ($date > new \DateTimeImmutable('today 23:59:59')) {
            return $date->format($this->fallbackFormat);
        }
        if ($date >= new \DateTimeImmutable('today midnight')) {
            return sprintf('%s %s', 'today_at', $date->format('H:i'));
        }
        if ($date >= new \DateTimeImmutable('-1 day midnight')) {
            return sprintf('%s %s', 'yesterday_at', $date->format('H:i'));
        }
        if ($date >= new \DateTimeImmutable('-2 days midnight')) {
            return sprintf('%s %s', '2_days_ago_at', $date->format('H:i'));
        }
        if ($date >= new \DateTimeImmutable('-3 days midnight')) {
            return sprintf('%s %s', '3_days_ago_at', $date->format('H:i'));
        }

        return $date->format($this->fallbackFormat);
    }
}
