<?php

namespace App\Services;

use App\Entities\TransationEntity;
use App\Repositories\TransationRepository;
use Illuminate\Support\Facades\Log;

class TransationService
{
    public function __construct(
        private TransationRepository $transationRepository
    ) {}

    public function createTransation(TransationEntity $transationData): TransationEntity
    {
        try {
            if (empty($transationData->amount) || empty($transationData->type) || empty($transationData->wallet_id)) {
                throw new \Exception("Invalid transation data");
            }

            return $this->transationRepository->createWallet($transationData);
        } catch (\Exception $e) {
            Log::error('Create transation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function extractTransation(int $walletId): ?TransationEntity
    {
        try {
            return $this->transationRepository->getWalletByWalletId($walletId);
        } catch (\Exception $e) {
            Log::error('Extract transation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function listExtractTransation(int $walletId, int $limit = 10, int $offset = 0): array
    {
        try {
            return $this->transationRepository->getTransactionsByWalletId($walletId, $limit, $offset);
        } catch (\Exception $e) {
            Log::error('List extract transation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
