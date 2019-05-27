<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

use Denbad\RelativeDate\Strategy\Strategy;

final class ConvertsToRelativeMiddleware implements Middleware
{
    /** @var Strategy[] */
    private $strategies = [];

    public function __construct(iterable $strategies = [])
    {
        foreach ($strategies as $strategy) {
            $this->strategies[(string) $strategy] = $strategy;
        }
    }

    public function format(string $date, string $strategy, callable $next): string
    {
        $result = '';

        if (array_key_exists($strategy, $this->strategies)) {
            $result = call_user_func($this->strategies[$strategy], $date);
        }

        return $next($result, $strategy);
    }
}
