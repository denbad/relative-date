<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

final class NoopTranslator implements Translator
{
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return sprintf('[%s]', $id);
    }
}