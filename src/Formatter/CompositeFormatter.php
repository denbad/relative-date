<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Formatter;

use Denbad\RelativeDate\Strategy\Strategy;
use Denbad\RelativeDate\Middleware\Middleware;

final class CompositeFormatter implements Formatter
{
    /** @var Middleware[] */
    private $middlewares = [];
    private $defaultStrategy;

    public function __construct(
        iterable $middlewares = [],
        string $defaultStrategy = Strategy::STRATEGY_RELATIVE_DATETIME
    ) {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = $middleware;
        }

        $this->defaultStrategy = $defaultStrategy;
    }

    public function format(\DateTimeImmutable $date, string $strategy = null): string
    {
        return call_user_func(
            $this->callableForNextMiddleware(0),
            (clone $date)->format('c'),
            $strategy ?? $this->defaultStrategy
        );
    }

    private function callableForNextMiddleware(int $index): callable
    {
        if (!isset($this->middlewares[$index])) {
            return static function (string $date): string {
                return $date;
            };
        }

        $middleware = $this->middlewares[$index];

        return function (string $date, string $strategy) use ($middleware, $index): string {
            return $middleware->format($date, $strategy, $this->callableForNextMiddleware($index + 1));
        };
    }
}