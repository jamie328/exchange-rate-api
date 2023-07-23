<?php

namespace App\UseCase\Currency\Exchange;

use App\Services\CurrencyService;
class Response
{
    /**
     *  定義 response 回傳格式
     */
    private $msg = 'success';
    private $amount = 0;

    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = app(CurrencyService::class)->transformFloatToString($amount);
    }
    public function getData(): array
    {
        return [
            'msg' => $this->msg,
            'amount' => $this->amount
        ];
    }

    public function getStatusCode(): int
    {
        if ($this->msg === 'success') {
            return 200;
        }
        return 400;
    }
}
