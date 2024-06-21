<?php

namespace App\Services;

use App\Services\CountryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\CurrencyRepository;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\CurrencyServiceInterface;
use Symfony\Component\HttpClient\HttpClient;

class CurrencyService implements CurrencyServiceInterface
{
    private $crawlingService;
    private $countryService;
    private $currencyRepository;
    public function __construct(CurrencyRepository $currencyRepository, CountryService $countryService, CrawlingService $crawlingService)
    {
        $this->currencyRepository = $currencyRepository;
        $this->countryService = $countryService;
        $this->crawlingService = $crawlingService;
    }

    /**
     * Get Currencies by code
     * @param array $data array with codes
     * @return array
     */
    public function getCurrencies(array $data): array
    {
        $result = [];

        foreach($data['code'] as $code) {
            $currency = Cache::get($code);
            if($currency == null) { //verifica se está em cache
                $currency = $this->currencyRepository->getByCodeOrNumber($code); //verifica se está no banco de dados
                if($currency) {
                    $currency = $currency->toArray();
                    $this->formatCurrency($currency);
                } else {
                    $currency = $this->crawlingService->fetchCurrencyCodes($code); //busca via crawling
                    if($currency){
                        $currency = $currency->toArray();
                        $this->saveCurrency($currency);
                    } else {
                        throw new \Exception("Failed to fetch currency data for code $code");
                    }
                }
            }
            $result[] = $this->formatCountries($currency);
        }
       return $result;
    }

    /**
     * Save currencies data on DB and Cache
     * @param array $currency currency data
     * @return void
     */
    private function saveCurrency(array $currency): void
    {
        $countries = explode(',', $currency['countries']);
        DB::transaction(function () use ($countries, $currency){
            $countries = $this->countryService->create($countries);
            $this->currencyRepository->create($currency, $countries);//salva no banco de dados
        });

        Cache::put($currency['code'], $currency, 60); // salva em cache por 1 hora
    }

    /**
     * Format currency of model
     * @param array $currency currency data
     * @return void
     */
    private function formatCurrency(&$currency): void
    {
        $currency['countries'] = collect($currency['countries'])->pluck('name')->toArray();
    }

    /**
     * Format currency of array
     * @param array $currency currency data
     * @return array
     */
    private function formatCountries($currency): array
    {
        if(!is_array($currency['countries'])) {
            $currency['countries'] = explode(',', $currency['countries']);
        }

        return $currency;

    }
}

