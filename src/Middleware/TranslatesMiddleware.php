<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

final class TranslatesMiddleware implements Middleware
{
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function format(string $date, string $format, callable $next): string
    {
        $glue = ' ';
        $parts = explode($glue, $date);

        $parts = array_map(function (string $token): string {
            return $this->translator->trans($token);
        }, $parts);

        return $next(implode($glue, $parts), $format);
    }
}