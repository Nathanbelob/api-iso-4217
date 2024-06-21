<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\CrawlingServiceInterface;
use Symfony\Component\HttpClient\HttpClient;

class CrawlingService implements CrawlingServiceInterface
{
    private $client;
    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * Fetch Currency Codes on Wikipedia
     * @param string $code code to search
     * @return Collection|null
     * @throws Exception
     */
    public function fetchCurrencyCodes(string $code): ?Collection
    {
        try{
            $response = $this->client->request('GET', 'https://pt.wikipedia.org/wiki/ISO_4217');
            $content = $response->getContent();
    
            $crawler = new Crawler($content);
            $table = $crawler->filter('table.wikitable')->eq(0);
            $tableData = [];
    
            $table->filter('tr')->each(function ($row) use (&$tableData) {
                $rowData = [];
                $row->filter('th, td')->each(function ($cell) use (&$rowData) {
                    $rowData[] = $cell->text(); // Conteúdo da célula sem o <img>
                });
                $tableData[] = $rowData;
            });
            unset($tableData[0]); //excluindo cabeçalho da tabela
            $tableData = $this->traitTable($tableData);
        
            return $tableData->filter(function ($item) use ($code) {
                return $item['code'] == $code || $item['number'] == $code;
            })->first();

        } catch (Exception $e) {
            // Lida com outros tipos de erros
            throw new \Exception($e->getMessage());
        }
    }

    private function traitTable(array $tableData)
    {
        $keyNames = [
            0 => 'code',
            1 => 'number',
            2 => 'decimal',
            3 => 'name',
            4 => 'countries',
            5 => 'icon'
        ];

        return collect($tableData)->map(function ($item) use ($keyNames) { //nomeando as keys do array para facilitar a busca
            return collect($item)->mapWithKeys(function ($value, $key) use ($keyNames) {
                return [$keyNames[$key] => $value];
            });
        });
    }
}

