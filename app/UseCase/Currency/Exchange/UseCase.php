<?php

namespace App\UseCase\Currency\Exchange;

use App\Exceptions\CurrencyErrorException;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Log;

class UseCase
{
    private $currencyService;
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * 主程式
     *
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request): Response
    {
        $response = new Response();
        try {
            // 1. 處理進來資料
            $data = collect($request)->toArray();
            // 2. 執行其商業邏輯
            $exchangeResult = $this->currencyService->exchange($data['source'], $data['target'], $data['amount']);
            if (!$exchangeResult['status']) {
                $response->setMsg('failure');
                Log::error(sprintf('%s::%s Error: %s', __CLASS__, __FUNCTION__, 'Exchange Service Business Error'));
                return $response;
            }

            $response->setAmount($exchangeResult['amount']);
            return $response;
        } catch (\Throwable $e) {
            $response->setMsg('failure');
            Log::error(sprintf('%s::%s Error: %s', __CLASS__, __FUNCTION__, $e->getMessage()));
            return $response;
        }
    }
}
