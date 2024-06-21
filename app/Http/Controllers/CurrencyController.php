<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use App\Http\Requests\CurrencyRequest;
use App\Http\Resources\CurrencyCollection;

class CurrencyController extends Controller
{
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @OA\Post(
     *     path="/currency",
     *     summary="Obter informações sobre moedas",
     *     description="Retorna informações detalhadas sobre moedas a partir de seus códigos ou números.",
     *     operationId="getCurrencyInfo",
     *     tags={"Currency"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="code",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"MXV"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="code", type="string", example="MXV"),
     *                     @OA\Property(property="number", type="string", example="979"),
     *                     @OA\Property(property="decimal", type="integer", example=2),
     *                     @OA\Property(property="currency", type="string", example="Unidade Mexicana de Investimento"),
     *                     @OA\Property(
     *                         property="currency_locations",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="location", type="string", example="México"),
     *                             @OA\Property(property="icon", type="string", nullable=true, example=null)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getCurrencies(CurrencyRequest $request)
    {
        $data = $request->validated();
        $currencies = $this->currencyService->getCurrencies($data);

        if($currencies) {
            return new CurrencyCollection($currencies);
        }

        throw new \Exception("Failed to get currencies");
    }
}
