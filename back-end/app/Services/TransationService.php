<?php

namespace App\Services;

use App\Entities\TransationEntity;
use App\Repositories\TransationRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;

class TransationService
{
    public function __construct(
        private TransationRepository $transationRepository
    ) {
        // Initialization code if needed
    }

    public function createTransation(TransationEntity $transationData): TransationEntity
    {
        // Validate the transation data
        // You can add more complex validation logic here
        if (empty($transationData->amount) || empty($transationData->type) || empty($transationData->wallet_id)) {
            throw new \Exception("Invalid transation data");
        }

        // Create the transation
        return $this->transationRepository->createWallet($transationData);
    }

    public function extractTransation(int $walletId)
    {
        // Get the transation by ID
        return $this->transationRepository->getWalletByWalletId($walletId);
    }
}