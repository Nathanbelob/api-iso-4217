<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * Get currency by code or number
     * @param string $code code or number
     * @return Currency|null
     */
    public function getByCodeOrNumber(string $code): ?Currency
    {
        return Currency::with('countries')->where("code", $code)->orWhere("number", $code)->first();
    }

    /**
     * Insert currency and relation between country and currency
     * @param array $currency currency data
     * @param array $countries countries ids
     * @return Currency
     */
    public function create(array $currency, array $countries): Currency
    {
        $currency = Currency::updateOrCreate(['name' => $currency['name']], $currency);
        $currency->countries()->attach($countries);

        return $currency;
    }
}