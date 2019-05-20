<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Format;

final class RelativeDateTimeFormat implements Format
{
    public function __toString(): string
    {
        return 'relative-datetime';
    }

    public function __invoke(string $date): string
    {
        $date = new \DateTimeImmutable($date);

        $default = sprintf('%s %s %s', $date->format('d'), $date->format('F'), $date->format('Y'));

        if ($date > new \DateTimeImmutable('today 23:59:59')) {
            return $default;
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

        return $default;
    }
}
