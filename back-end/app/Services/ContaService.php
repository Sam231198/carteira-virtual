<?php

namespace App\Services;

use App\Entities\UserEntity;
use App\Entities\WalletEntity;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContaService
{
    public function __construct(
        private UserRepository $userRepository,
        private WalletRepository $walletRepository
    ) {}

    public function login(string $email, string $password): string
    {
        try {
            $user = $this->userRepository->getUserByEmail($email);

            if (!$user || !password_verify($password, $user->password)) {
                throw new \Exception('Invalid credentials');
            }

            if (!method_exists($user, 'createToken')) {
                throw new \Exception('Token generation not available for this user.');
            }

            return $user->{'createToken'}('auth_token')->plainTextToken;
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createUserWithWallet(UserEntity $userData, WalletEntity $walletData): UserEntity
    {
        try {
            $userData->password = Hash::make($userData->password);
            $user = $this->userRepository->createUser($userData);

            $walletData->user_id = $user->id;
            $this->walletRepository->createWallet($walletData);

            return $user;
        } catch (\Exception $e) {
            Log::error('Create user failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getUserById(int $id): ?UserEntity
    {
        try {
            $user = $this->userRepository->getUserById($id);
            $user->wallet = $this->walletRepository->getWalletById($user->id);

            return $user;
        } catch (\Exception $e) {
            Log::error('Get user failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateUserWithWallet(int $id, UserEntity $userData, WalletEntity $walletData): UserEntity
    {
        try {
            $user = $this->userRepository->updateUser($id, $userData);

            $wallet = $this->walletRepository->getWalletById($user->id);
            if ($wallet) {
                $this->walletRepository->updateWallet($wallet->id, $walletData);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('Update user failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteUserWithWallet(int $id): bool
    {
        try {
            $wallet = $this->walletRepository->getWalletById($id);
            if ($wallet) {
                $this->walletRepository->deleteWallet($wallet->id);
            }

            return $this->userRepository->deleteUser($id);
        } catch (\Exception $e) {
            Log::error('Delete user failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
