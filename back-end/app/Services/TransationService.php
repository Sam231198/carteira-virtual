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

    public function extract(int $walletId): ?TransationEntity
    {
        try {
            return $this->transationRepository->getByWalletId($walletId);
        } catch (\Exception $e) {
            Log::error('Extract transation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function list(int $walletId, int $limit = 10, int $offset = 0): array
    {
        try {
            return $this->transationRepository->getListByWalletId($walletId, $limit, $offset);
        } catch (\Exception $e) {
            Log::error('List extract transation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
