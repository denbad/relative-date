<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Strategy;

use Denbad\RelativeDate\Strategy\Strategy;
use Denbad\RelativeDate\Strategy\RelativeDateStrategy;

final class RelativeDateStrategyTest extends \PHPUnit\Framework\TestCase
{
    public function testToString(): void
    {
        $this->assertEquals(Strategy::STRATEGY_RELATIVE_DATE, (string) new RelativeDateStrategy());
    }

    public function testInvoke(): void
    {
        $strategy = new RelativeDateStrategy();

        $a = new \DateTimeImmutable('tomorrow midnight');
        $this->assertEquals($a->format(Strategy::FALLBACK_FORMAT), $strategy($a->format('c')));

        $a = (new \DateTimeImmutable('today midnight'))->modify('+1 minute');
        $b = new \DateTimeImmutable('today midnight');
        $this->assertEquals('today', $strategy($a->format('c')));
        $this->assertEquals('today', $strategy($b->format('c')));

        $a = (new \DateTimeImmutable('-1 day midnight'))->modify('+1 minute');
        $b = new \DateTimeImmutable('-1 day midnight');
        $this->assertEquals('yesterday', $strategy($a->format('c')));
        $this->assertEquals('yesterday', $strategy($b->format('c')));

        $a = (new \DateTimeImmutable('-2 days midnight'))->modify('+1 minute');
        $b = new \DateTimeImmutable('-2 days midnight');
        $this->assertEquals('2_days_ago', $strategy($a->format('c')));
        $this->assertEquals('2_days_ago', $strategy($b->format('c')));

        $a = (new \DateTimeImmutable('-3 days midnight'))->modify('+1 minute');
        $b = new \DateTimeImmutable('-3 days midnight');
        $this->assertEquals('3_days_ago', $strategy($a->format('c')));
        $this->assertEquals('3_days_ago', $strategy($b->format('c')));

        $a = new \DateTimeImmutable('-4 days midnight');
        $this->assertEquals($a->format(Strategy::FALLBACK_FORMAT), $strategy($a->format('c')));
    }
}