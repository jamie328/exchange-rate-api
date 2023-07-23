<?php

namespace Tests\Unit\Currency;

use App\Services\CurrencyService;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CurrencyService::class);
    }

    /**
     * wrong_exchange_payload
     *
     * @return array
     */
    public function wrong_exchange_payload()
    {
        return [
            ['CNY', 'USD', 1238.33], // invalid source
            ['USD', 'CNY', 1238.33], // invalid target
        ];
    }

    /**
     * test wrong_exchange_payload
     * @dataProvider wrong_exchange_payload
     * @group validateBenefitEntityUseInCart
     * @return void
     */
    public function test_exchange_service_when_wrong_exchange_payload_returns_status_false(string $source, string $target, float $amount)
    {
        // arrange
        // act
        $validateRes = $this->service->exchange($source, $target, $amount);
        // assert
        $this->assertFalse($validateRes['status']);
    }

    /**
     * wrong_exchange_payload
     *
     * @return array
     */
    public function true_exchange_payload()
    {
        return [
            ['TWD', 'USD', 1238.33],
            ['USD', 'JPY', 33],
        ];
    }

    /**
     * test true_exchange_payload
     * @dataProvider true_exchange_payload
     * @group validateBenefitEntityUseInCart
     * @return void
     */
    public function test_exchange_service_when_true_exchange_payload_returns_status_true(string $source, string $target, float $amount)
    {
        // arrange
        // act
        $validateRes = $this->service->exchange($source, $target, $amount);
        // assert
        $this->assertTrue($validateRes['status']);
    }
}
