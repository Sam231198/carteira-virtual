<?php

namespace App\Services;

use App\Entities\TransationEntity;
use App\Entities\WalletEntity;
use App\Repositories\TransationRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OperationService
{
    public function __construct(
        private WalletRepository $walletRepository,
        private TransationRepository $transationRepository
    ) {}

    public function deposit(int $walletId, float $amount): WalletEntity
    {
        try {
            return DB::transaction(function () use ($walletId, $amount) {
                $wallet = $this->walletRepository->getById($walletId);

                if (!$wallet) {
                    throw new \Exception("Wallet not found");
                }

                $wallet->balance = $wallet->balance + $amount;
                $updatedWallet = $this->walletRepository->update($walletId, $wallet);

                $this->recordTransation($walletId, $amount, 'deposit');

                return $updatedWallet;
            });
        } catch (\Exception $e) {
            $this->recordTransation($walletId, $amount, 'deposit', 0, 'Falha no depósito');
            Log::error('Deposit failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function withdraw(int $walletId, float $amount): WalletEntity
    {
        try {
            return DB::transaction(function () use ($walletId, $amount) {
                $wallet = $this->walletRepository->getById($walletId);

                if (!$wallet) {
                    throw new \Exception("Wallet not found");
                }

                if ($wallet->balance < $amount) {
                    throw new \Exception("Insufficient funds");
                }

                $wallet->balance = $wallet->balance - $amount;

                $updatedWallet = $this->walletRepository->update($walletId, $wallet);

                $this->recordTransation($walletId, $amount, 'withdraw');

                return $updatedWallet;
            });
        } catch (\Exception $e) {
            $this->recordTransation($walletId, $amount, 'withdraw', 0, 'Falha no saque');
            Log::error('Withdraw failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function transfer(int $fromWalletId, int $toWalletId, float $amount): bool
    {
        try {
            return DB::transaction(function () use ($fromWalletId, $toWalletId, $amount) {
                $fromWallet = $this->walletRepository->getById($fromWalletId);
                $toWallet = $this->walletRepository->getById($toWalletId);

                if (!$fromWallet || !$toWallet) {
                    throw new \Exception("One or both wallets not found");
                }

                if ($fromWallet->balance < $amount) {
                    throw new \Exception("Insufficient funds in the source wallet");
                }

                $result = $this->walletRepository->tranfer($fromWalletId, $toWalletId, $amount);

                $this->recordTransation($fromWalletId, $amount, 'transfer', $toWalletId);
                $this->recordTransation($toWalletId, $amount, 'transfer', $fromWalletId);

                return $result;
            });
        } catch (\Exception $e) {
            $this->recordTransation($fromWalletId, $amount, 'transfer', $toWalletId, 'Falha na transferência');
            $this->recordTransation($toWalletId, $amount, 'transfer', $fromWalletId, 'Falha na transferência');
            Log::error('Transfer failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function recoveryTransfer(int $transationId): bool
    {
        try {
            $transation = $this->transationRepository->getById($transationId);

            if (!$transation) {
                throw new \Exception("Transation not found");
            }

            $result = $this->walletRepository->tranfer($transation->wallet_transfer_id, $transation->wallet_id, $transation->amount);

            $this->recordTransation($transation->wallet_id, $transation->amount, 'reverse', $transation->wallet_transfer_id, 'Recovery transfer');
            $this->recordTransation($transation->wallet_transfer_id, $transation->amount, 'reverse', $transation->wallet_id, 'Recovery transfer');

            return $result;
        } catch (\Exception $e) {
            $this->recordTransation($transation->wallet_id, $transation->amount, 'reverse', $transation->wallet_transfer_id, 'Falha na recuperação da transferência');
            $this->recordTransation($transation->wallet_transfer_id, $transation->amount, 'reverse', $transation->wallet_id, 'Falha na recuperação da transferência');
            Log::error('Recovery transfer failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function recordTransation(int $walletId, float $amount, string $type, int $walletIdTransfer = 0, string $message = ''): void
    {
        $transition = TransationEntity::fromArray([
            'wallet_id' => $walletId,
            'amount' => $amount,
            'type' => $type,
            'wallet_transfer_id' => $walletIdTransfer,
            'message' => $message
        ]);

        $this->transationRepository->create($transition);
    }
}
