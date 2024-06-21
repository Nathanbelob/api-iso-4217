<?php

namespace Tests\Unit\App\Services;

use Mockery;
use Tests\TestCase;
use App\Services\CrawlingService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlingServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testFetchCurrencyCodes()
    {
        // Exemplo de conteúdo HTML que seria retornado pela requisição HTTP
        $htmlContent = '
        <table class="wikitable">
            <tr>
                <th>Code</th>
                <th>Number</th>
                <th>Decimal</th>
                <th>Name</th>
                <th>Countries</th>
                <th>Icon</th>
            </tr>
            <tr>
                <td>USD</td>
                <td>840</td>
                <td>2</td>
                <td>US Dollar</td>
                <td>United States</td>
                <td>http://wiki.usa</td>
            </tr>
        </table>';

        // Mock do HttpClient e da Response
        $httpClientMock = Mockery::mock(HttpClientInterface::class);
        $responseMock = Mockery::mock(ResponseInterface::class);

        // Definindo o comportamento do mock para a requisição e resposta HTTP
        $httpClientMock->shouldReceive('request')
            ->with('GET', 'https://pt.wikipedia.org/wiki/ISO_4217')
            ->andReturn($responseMock);

        $responseMock->shouldReceive('getContent')
            ->andReturn($htmlContent);

        // Criando instância real do HttpClient e injetando o mock
        $realHttpClient = HttpClient::create();
        $crawlerService = new CrawlingService();

        // Usando reflexão para injetar o mock do HttpClient na propriedade privada $client
        $reflection = new \ReflectionClass($crawlerService);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($crawlerService, $httpClientMock);

        // Chamando o método que estamos testando
        $result = $crawlerService->fetchCurrencyCodes('USD');

        // Verificando se o resultado está correto
        $this->assertNotNull($result);
        $this->assertEquals('USD', $result['code']);
        $this->assertEquals('840', $result['number']);
        $this->assertEquals('2', $result['decimal']);
        $this->assertEquals('US Dollar', $result['name']);
        $this->assertEquals('United States', $result['countries']);
        $this->assertEquals('http://wiki.usa', $result['icon']);
    }
}