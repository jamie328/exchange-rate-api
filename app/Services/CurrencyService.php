<?php

namespace App\Services;

class CurrencyService
{
    public function __construct()
    {
        $this->exchange_rate = config('currency_rate');
    }

    /**
     * 把字串形式的輸入值轉成浮點數 ex: $221.33 -> 221.33
     * @param string $amount
     * @return float
     */
    public function transformAmountToFloat(string $amount): float
    {
        if (preg_match('/^\$/', $amount)) {
            $amount = str_replace('$', '', $amount);
        }

        return (float) str_replace(',', '', $amount);
    }

    /**
     * 把浮點數轉成字串形式 ex: 221.33 -> $221.33
     * @param float $amount
     * @return string
     */
    public function transformFloatToString(float $amount): string
    {
        return '$' . number_format($amount, 2);
    }

    /**
     * 匯率轉換
     * @param string $source
     * @param string $target
     * @param float $amount
     * @return array
     */
    public function exchange(string $source, string $target, float $amount): array
    {
        if (!in_array($source, array_keys($this->exchange_rate))) {
            return [
                'status' => false,
                'amount' => 0
            ];
        }
        if (!in_array($target, array_keys($this->exchange_rate[$source]))) {
            return [
                'status' => false,
                'amount' => 0
            ];
        }

        $rate = $this->exchange_rate[$source][$target];
        return [
            'status' => true,
            'amount' => (float) $amount * $rate
        ];
    }
}
