<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use App\UseCase\Currency\Exchange\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\UseCase\Currency\Exchange\Request as ExchangeRequest;
use App\UseCase\Currency\Exchange\UseCase as ExchangeUseCase;
use App\UseCase\Currency\Exchange\Response as ExchangeResponse;
use App\Exceptions\CurrencyErrorException;

class CurrencyController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function exchange(Request $request): JsonResponse
    {
        $response = new Response();
        try {
            $input = new ExchangeRequest();
            $requestData = $input->requestParser($request);
            $validateResult = $input->validate($requestData);
            if (!$validateResult['status']) {
                Log::error('Validate Error: ', [
                    'validator' => $validateResult
                ]);
                $response->setMsg('failure');
                return response()->json($response->getData(), 422);
            }
            // 1-2. 轉成 object
            $input->toDTO($requestData);
            // 2. 新增 useCase 實例 interactor
            $interactor = new ExchangeUseCase(new CurrencyService());
            // 3. useCase 執行商業邏輯
            $response = $interactor->execute($input);
            return response()->json($response->getData(), $response->getStatusCode());
        } catch (\Throwable $e) {
            Log::error(sprintf('%s::%s Error: %s', __CLASS__, __FUNCTION__, $e->getMessage()));
            return response()->json([
                'msg' => 'failure',
                'amount' => 0
            ], 400);
        }
    }
}
