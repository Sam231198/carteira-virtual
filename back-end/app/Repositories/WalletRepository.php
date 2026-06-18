<?php

namespace App\Repositories;

use App\Entities\WalletEntity;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletRepository
{
    public function __construct(private Wallet $wallet)
    {
        // Initialization code if needed
    }

    public function getWalletById($id): ?WalletEntity
    {
        $wallet = $this->wallet->find($id);

        return WalletEntity::fromArray($wallet->toArray());
    }

    public function createWallet(WalletEntity $data): WalletEntity
    {
        $wallet = $this->wallet->create($data);

        return WalletEntity::fromArray($wallet->toArray());
    }

    public function updateWallet($id, WalletEntity $data): WalletEntity
    {
        $wallet = $this->wallet->find($id);

        if (!$wallet) {
            throw new \Exception("Wallet not found");
        }

        $wallet->update($data);

        return WalletEntity::fromArray($wallet->toArray());
    }

    public function tranfer($fromWalletId, $toWalletId, $amount): bool
    {
        $fromWallet = $this->wallet->find($fromWalletId);
        $toWallet = $this->wallet->find($toWalletId);

        if (!$fromWallet || !$toWallet) {
            throw new \Exception("One or both wallets not found");
        }

        if ($fromWallet->balance < $amount) {
            throw new \Exception("Insufficient funds in the source wallet");
        }

        Wallet::transaction(function () use ($fromWallet, $toWallet, $amount) {
            $fromWallet->balance -= $amount;
            $fromWallet->save();

            $toWallet->balance += $amount;
            $toWallet->save();

            return true;
        }, 5);

        return false;
    }

    public function deleteWallet($id): bool
    {
        $wallet = $this->wallet->find($id);

        if (!$wallet) {
            throw new \Exception("Wallet not found");
        }

        return $wallet->delete();
    }
}