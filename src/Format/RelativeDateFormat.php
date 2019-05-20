<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Format;

final class RelativeDateFormat implements Format
{
    public function __toString(): string
    {
        return 'relative-date';
    }

    public function __invoke(string $date): string
    {
        $date = new \DateTimeImmutable($date);

        $default = sprintf('%s %s %s', $date->format('d'), $date->format('F'), $date->format('Y'));

        if ($date > new \DateTimeImmutable('today 23:59:59')) {
            return $default;
        }
        if ($date >= new \DateTimeImmutable('today midnight')) {
            return 'today';
        }
        if ($date >= new \DateTimeImmutable('-1 day midnight')) {
            return 'yesterday';
        }
        if ($date >= new \DateTimeImmutable('-2 days midnight')) {
            return '2_days_ago';
        }
        if ($date >= new \DateTimeImmutable('-3 days midnight')) {
            return '3_days_ago';
        }

        return $default;
    }
}
