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
        return TransationEntity::fromArray($this->transation->where('wallet_id', $id)->get()->toArray());
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