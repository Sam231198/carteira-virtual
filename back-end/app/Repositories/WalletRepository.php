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
        Wallet::where('id', $fromWalletId)->decrement('balance', $amount);
        Wallet::where('id', $toWalletId)->increment('balance', $amount);

        return true;
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