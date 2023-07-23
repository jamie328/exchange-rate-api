<?php

namespace App\UseCase\Currency\Exchange;

use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CurrencyErrorException;
use App\Services\CurrencyService;

class Request
{
    public function requestParser(BaseRequest $request): array
    {
        return [
            'source' => !empty($request->query('source')) ? (string) $request->query('source') : '',
            'target' => !empty($request->query('target')) ? (string) $request->query('target') : '',
            'amount' => !empty($request->query('amount')) ? (string) $request->query('amount') : '',
        ];

    }

    /**
     * 檢查 input 參數
     *
     * @param array $input
     * @return array
     * @throws CurrencyErrorException
     */
    public function validate(array $input): array
    {
        try {
            $isStartDollarSign = false;
            if (preg_match('/^\$/', $input['amount'])) {
                $isStartDollarSign = true;
                $input['amount'] = str_replace('$', '', $input['amount']);
            }

            $rule = [
                'source' => "required|string|in:USD,JPY,TWD,CNY",
                'target' => "required|string|in:USD,JPY,TWD,CNY",
                'amount' => "required|string|regex:/^[1-9]\d{0,2}(,\d{3})*(\.\d{1,2})?$/"
            ];
            $message = [
                'required' => ':attribute 不得為空',
                'string' => ':attribute 須為 string',
                'regex' => ':attribute regex 格式錯誤，須以千分位撰寫數字格式，如為小數最多兩位',
                'in' => ':attribute 須為 USD,JPY,TWD'
            ];
            $validator = Validator::make($input, $rule, $message);
            if ($validator->fails() || !$isStartDollarSign) {
                $errors = $validator->errors()->toArray();
                if (!isset($errors['amount']) && !$isStartDollarSign) {
                    $errors['amount'][] = 'amount 須以 $ 作為開頭';
                } elseif (isset($errors['amount'])) {
                    $errors['amount'][0] = $errors['amount'][0] . '，且 amount 須以 $ 作為開頭';
                }
                return [
                    'status' => false,
                    'message' => $errors,
                ];
            }
            return [
                'status' => true,
                'message' => [],
            ];
        } catch (\Throwable $e) {
            Log::error(sprintf('%s::%s Error: %s', __CLASS__, __FUNCTION__, $e->getMessage()));
            throw new CurrencyErrorException($e->getMessage());
        }
    }

    /**
     * 轉換成 object
     *
     * @param  array $input
     * @return void
     */
    public function toDTO(array $input): void
    {
        foreach ($input as $key => $val) {
            $this->$key = $val;
            if ($key === 'amount') {
                $this->amount = app(CurrencyService::class)->transformAmountToFloat($val);
            }
        }
    }
}
