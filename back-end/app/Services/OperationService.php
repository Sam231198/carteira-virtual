<?php

namespace App\Services;

use App\Entities\TransationEntity;
use App\Entities\WalletEntity;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OperationService
{
    public function __construct(
        private WalletRepository $walletRepository,
        private TransationService $transationService
    ) {
        // Initialization code if needed
    }

    public function deposit(int $walletId, float $amount): WalletEntity
    {
        try {
            return DB::transaction(function () use ($walletId, $amount) {
                $wallet = $this->walletRepository->getWalletById($walletId);

                if (!$wallet) {
                    throw new \Exception("Wallet not found");
                }

                $wallet->balance = $wallet->balance + $amount;
                $updatedWallet = $this->walletRepository->updateWallet($walletId, $wallet);

                $this->recordTransation($walletId, $amount, 'deposit');

                return $updatedWallet;
            });
        } catch (\Exception $e) {
            Log::error('Deposit failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function withdraw(int $walletId, float $amount): WalletEntity
    {
        try {
            return DB::transaction(function () use ($walletId, $amount) {
                $wallet = $this->walletRepository->getWalletById($walletId);

                if (!$wallet) {
                    throw new \Exception("Wallet not found");
                }

                if ($wallet->balance < $amount) {
                    throw new \Exception("Insufficient funds");
                }

                $wallet->balance = $wallet->balance - $amount;
                return $this->walletRepository->updateWallet($walletId, $wallet);
            });
        } catch (\Exception $e) {
            Log::error('Withdraw failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function transfer(int $fromWalletId, int $toWalletId, float $amount): bool
    {
        try {
            return DB::transaction(function () use ($fromWalletId, $toWalletId, $amount) {
                $fromWallet = $this->walletRepository->getWalletById($fromWalletId);
                $toWallet = $this->walletRepository->getWalletById($toWalletId);

                if (!$fromWallet || !$toWallet) {
                    throw new \Exception("One or both wallets not found");
                }

                if ($fromWallet->balance < $amount) {
                    throw new \Exception("Insufficient funds in the source wallet");
                }

                return $this->walletRepository->tranfer($fromWalletId, $toWalletId, $amount);
            });
        } catch (\Exception $e) {
            Log::error('Transfer failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function recordTransation(int $walletId, float $amount, string $type, int $walletIdTransfer = 0): void
    {
        $transition = TransationEntity::fromArray([
            'wallet_id' => $walletId,
            'amount' => $amount,
            'type' => $type,
            'wallet_transfer_id' => $walletIdTransfer
        ]);

        $this->transationService->createTransation($transition);
    }
}
