<?php

use PHPUnit\Framework\TestCase;
use App\Services\OperationService;
use App\Entities\WalletEntity;

class OperationServiceTest extends TestCase
{
    public function test_deposit_updates_balance_and_records_transaction()
    {
        $walletId = 1;
        $initial = WalletEntity::fromArray(['id' => $walletId, 'balance' => 100.0]);
        $amount = 50.0;

        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);
        $transationService = $this->createMock(\App\Services\TransationService::class);

        $walletRepo->expects($this->once())->method('getWalletById')->with($walletId)->willReturn($initial);
        $walletRepo->expects($this->once())->method('updateWallet')->with($walletId, $this->callback(function ($w) use ($amount) {
            return abs($w->balance - 150.0) < 0.001;
        }))->willReturn(WalletEntity::fromArray(['id' => $walletId, 'balance' => 150.0]));

        $transationService->expects($this->once())->method('createTransation');

        $service = new OperationService($walletRepo, $transationService);
        $result = $service->deposit($walletId, $amount);

        $this->assertInstanceOf(WalletEntity::class, $result);
        $this->assertEquals(150.0, $result->balance);
    }

    public function test_withdraw_throws_when_insufficient_funds()
    {
        $walletId = 2;
        $initial = WalletEntity::fromArray(['id' => $walletId, 'balance' => 30.0]);

        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);
        $transationService = $this->createMock(\App\Services\TransationService::class);

        $walletRepo->expects($this->once())->method('getWalletById')->with($walletId)->willReturn($initial);

        $service = new OperationService($walletRepo, $transationService);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');

        $service->withdraw($walletId, 50.0);
    }

    public function test_transfer_calls_repository_when_enough_funds()
    {
        $fromId = 3;
        $toId = 4;
        $amount = 25.0;

        $from = WalletEntity::fromArray(['id' => $fromId, 'balance' => 100.0]);
        $to = WalletEntity::fromArray(['id' => $toId, 'balance' => 10.0]);

        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);
        $transationService = $this->createMock(\App\Services\TransationService::class);

        $walletRepo->expects($this->exactly(2))->method('getWalletById')->willReturnOnConsecutiveCalls($from, $to);
        $walletRepo->expects($this->once())->method('tranfer')->with($fromId, $toId, $amount)->willReturn(true);

        $service = new OperationService($walletRepo, $transationService);
        $result = $service->transfer($fromId, $toId, $amount);

        $this->assertTrue($result);
    }
}
