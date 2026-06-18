<?php

namespace App\Services;

use App\Entities\TransationEntity;
use App\Entities\WalletEntity;
use App\Repositories\WalletRepository;

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
        $wallet = $this->walletRepository->getWalletById($walletId);

        if (!$wallet) {
            throw new \Exception("Wallet not found");
        }

        $wallet->balance = $wallet->balance + $amount;
        $updatedWallet = $this->walletRepository->updateWallet($walletId, $wallet);

        $this->recordTransation($walletId, $amount, 'deposit');

        return $updatedWallet;
    }

    public function withdraw(int $walletId, float $amount): WalletEntity
    {
        $wallet = $this->walletRepository->getWalletById($walletId);

        if (!$wallet) {
            throw new \Exception("Wallet not found");
        }

        if ($wallet->balance < $amount) {
            throw new \Exception("Insufficient funds");
        }

        $wallet->balance = $wallet->balance - $amount;
        return $this->walletRepository->updateWallet($walletId, $wallet);
    }

    public function transfer(int $fromWalletId, int $toWalletId, float $amount): bool
    {
        $fromWallet = $this->walletRepository->getWalletById($fromWalletId);
        $toWallet = $this->walletRepository->getWalletById($toWalletId);

        if (!$fromWallet || !$toWallet) {
            throw new \Exception("One or both wallets not found");
        }

        if ($fromWallet->balance < $amount) {
            throw new \Exception("Insufficient funds in the source wallet");
        }

        return $this->walletRepository->tranfer($fromWalletId, $toWalletId, $amount);
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