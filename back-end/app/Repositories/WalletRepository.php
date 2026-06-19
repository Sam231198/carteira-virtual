<?php

namespace App\Repositories;

use App\Entities\WalletEntity;
use App\Models\Wallet;

class WalletRepository
{
    public function __construct(private Wallet $wallet) {}

    public function getById(int $id): ?WalletEntity
    {
        $wallet = $this->wallet->find($id);

        return $wallet ? WalletEntity::fromArray($wallet->toArray()) : null;
    }

    public function create(WalletEntity $data): WalletEntity
    {
        $wallet = $this->wallet->create($data);

        return WalletEntity::fromArray($wallet->toArray());
    }

    public function update(int $id, WalletEntity $data): WalletEntity
    {
        $wallet = $this->wallet->find($id);

        if (!$wallet) {
            throw new \Exception("Wallet not found");
        }

        $wallet->update($data);

        return WalletEntity::fromArray($wallet->toArray());
    }

    public function tranfer(int $fromWalletId, int $toWalletId, float $amount): bool
    {
        Wallet::where('id', $fromWalletId)->decrement('balance', $amount);
        Wallet::where('id', $toWalletId)->increment('balance', $amount);

        return true;
    }

    public function delete(int $id): bool
    {
        $wallet = $this->wallet->find($id);

        if (!$wallet) {
            throw new \Exception("Wallet not found");
        }

        return $wallet->delete();
    }
}