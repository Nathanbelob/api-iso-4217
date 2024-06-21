<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code' => $this->resource['code'],
            'number' => $this->resource['number'],
            'decimal' => $this->resource['decimal'],
            'currency' => $this->resource['name'],
            'currency_locations' => $this->formatCurrencyLocations($this->resource['countries'], $this->resource['icon'] ?? null)
        ];
    }

    /**
     * Format currencyLocations
     * @param array $countries array of countries
     * @param array|null $icon flag country icon
     * @return array 
     */
    private function formatCurrencyLocations(array $countries, string $icon = null): array
    {
        $locations = [];

        foreach ($countries as $country) {
            $locations[] = [
                'location' => $country,
                'icon' => $icon
            ];
        }

        return $locations;
    }
}
