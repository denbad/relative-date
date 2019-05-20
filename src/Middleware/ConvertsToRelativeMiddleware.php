<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

use Denbad\RelativeDate\Format\Format;

final class ConvertsToRelativeMiddleware implements Middleware
{
    /** @var Format[] */
    private $formats = [];

    public function __construct(iterable $formats = [])
    {
        foreach ($formats as $format) {
            $this->formats[(string) $format] = $format;
        }
    }

    public function format(string $date, string $format, callable $next): string
    {
        $result = '';

        if (array_key_exists($format, $this->formats)) {
            $result = call_user_func($this->formats[$format], $date);
        }

        return $next($result, $format);
    }
}
