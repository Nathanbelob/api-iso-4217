<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyCollection extends ResourceCollection
{
    /**
     * Convert the collection of currencies to an array.
     * @param $request
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($currency) {
            return new CurrencyResource($currency);
        });
    }
}
