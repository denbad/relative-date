<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Strategy;

final class RelativeDateStrategy implements Strategy
{
    private $fallbackFormat;

    public function __construct(string $fallbackFormat = Strategy::FALLBACK_FORMAT)
    {
        $this->fallbackFormat = $fallbackFormat;
    }

    public function __toString(): string
    {
        return Strategy::STRATEGY_RELATIVE_DATE;
    }

    public function __invoke(string $date): string
    {
        $date = new \DateTimeImmutable($date);

        if ($date > new \DateTimeImmutable('today 23:59:59')) {
            return $date->format($this->fallbackFormat);
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

        return $date->format($this->fallbackFormat);
    }
}
