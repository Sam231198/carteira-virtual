<?php

use PHPUnit\Framework\TestCase;
use App\Services\TransationService;
use App\Entities\TransationEntity;

class TransationServiceTest extends TestCase
{
    public function test_create_transation_validates_and_calls_repository()
    {
        $transationData = TransationEntity::fromArray(['wallet_id' => 1, 'amount' => 10.0, 'type' => 'deposit']);

        $transRepo = $this->createMock(\App\Repositories\TransationRepository::class);
        $transRepo->expects($this->once())->method('createWallet')->with($this->isInstanceOf(TransationEntity::class))->willReturn($transationData);

        $service = new TransationService($transRepo);
        $result = $service->createTransation($transationData);

        $this->assertInstanceOf(TransationEntity::class, $result);
        $this->assertEquals(10.0, $result->amount);
    }

    public function test_create_transation_throws_on_invalid_data()
    {
        $transationData = TransationEntity::fromArray(['wallet_id' => null, 'amount' => 0, 'type' => '']);

        $transRepo = $this->createMock(\App\Repositories\TransationRepository::class);
        $service = new TransationService($transRepo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid transation data');

        $service->createTransation($transationData);
    }

    public function test_extract_transation_returns_repository_result()
    {
        $walletId = 5;
        $transRepo = $this->createMock(\App\Repositories\TransationRepository::class);
        $transRepo->expects($this->once())->method('getWalletByWalletId')->with($walletId)->willReturn(\App\Entities\TransationEntity::fromArray(['id' => 1, 'wallet_id' => $walletId, 'amount' => 5.0, 'type' => 'deposit']));

        $service = new TransationService($transRepo);
        $result = $service->extractTransation($walletId);

        $this->assertInstanceOf(TransationEntity::class, $result);
        $this->assertEquals($walletId, $result->wallet_id);
    }
}
