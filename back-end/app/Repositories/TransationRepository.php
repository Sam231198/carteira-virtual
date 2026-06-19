<?php

namespace App\Repositories;

use App\Entities\TransationEntity;
use App\Models\Transation;

class TransationRepository
{
    public function __construct(private Transation $transation)
    {
        // Initialization code if needed
    }

    public function getWalletByWalletId(int $id): ?TransationEntity
    {
        $transaction = $this->transation->where('wallet_id', $id)->first();

        return $transaction ? TransationEntity::fromArray($transaction->toArray()) : null;
    }

    public function getTransactionsByWalletId(int $id, int $limit = 10, int $offset = 0): array
    {
        return $this->transation->where('wallet_id', $id)
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($t) {
                return TransationEntity::fromArray($t->toArray());
            })->toArray();
    }

    public function getWalletById(int $id): ?TransationEntity
    {
        return TransationEntity::fromArray($this->transation->find($id)->toArray());
    }

    public function createWallet(TransationEntity $data): TransationEntity
    {
        return TransationEntity::fromArray($this->transation->create($data)->toArray());
    }

    public function updateWallet(int $id, TransationEntity $data): TransationEntity
    {
        $wallet = $this->transation->find($id);

        if ($wallet) {
            $wallet->update($data);
        } else {
            throw new \Exception("Transation not found");
        }

        return TransationEntity::fromArray($wallet->toArray());
    }

    public function deleteWallet(int $id): bool
    {
        $wallet = $this->transation->find($id);

        if (! $wallet) {
            throw new \Exception("Transation not found");
        }

        return (bool) $wallet->delete();
    }
}