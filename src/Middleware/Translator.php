<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

interface Translator
{
    public function trans(string $id, array $parameters = [], string $locale = null): string;
}