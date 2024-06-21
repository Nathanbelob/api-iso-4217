<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface CrawlingServiceInterface
{
    /**
     * Fetch Currency Codes on Wikipedia
     * @param string $code code to search
     * @return Collection|null
     */
    public function fetchCurrencyCodes(string $code): ?Collection;
}

