<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

class NoopTranslator implements Translator
{
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return sprintf('[%s]', $id);
    }
}