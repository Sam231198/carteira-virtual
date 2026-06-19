<?php

use PHPUnit\Framework\TestCase;
use App\Services\ContaService;
use App\Entities\UserEntity;
use App\Entities\WalletEntity;

class ContaServiceTest extends TestCase
{
    public function test_login_success_returns_token()
    {
        $email = 'a@b.com';
        $password = 'secret';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $userObj = new class(0, 'U', $email, $hash, null) extends \App\Entities\UserEntity {
            public function createToken($name) {
                return (object)['plainTextToken' => 'token123'];
            }
        };

        $userRepo = $this->createMock(\App\Repositories\UserRepository::class);
        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);

        $userRepo->expects($this->once())->method('getUserByEmail')->with($email)->willReturn($userObj);

        $service = new ContaService($userRepo, $walletRepo);
        $token = $service->login($email, $password);

        $this->assertEquals('token123', $token);
    }

    public function test_login_invalid_credentials_throws()
    {
        $email = 'x@y.com';
        $password = 'wrong';

        $userRepo = $this->createMock(\App\Repositories\UserRepository::class);
        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);

        $userRepo->expects($this->once())->method('getUserByEmail')->with($email)->willReturn(null);

        $service = new ContaService($userRepo, $walletRepo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid credentials');

        $service->login($email, $password);
    }

    public function test_get_user_by_id_attaches_wallet()
    {
        $id = 10;
        $userEntity = UserEntity::fromArray(['id' => $id, 'name' => 'U']);
        $walletEntity = WalletEntity::fromArray(['id' => 2, 'user_id' => $id, 'balance' => 0.0]);

        $userRepo = $this->createMock(\App\Repositories\UserRepository::class);
        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);

        $userRepo->expects($this->once())->method('getUserById')->with($id)->willReturn($userEntity);
        $walletRepo->expects($this->once())->method('getWalletById')->with($userEntity->id)->willReturn($walletEntity);

        $service = new ContaService($userRepo, $walletRepo);
        $result = $service->getUserById($id);

        $this->assertInstanceOf(UserEntity::class, $result);
        $this->assertEquals($walletEntity, $result->wallet);
    }

    public function test_delete_user_with_wallet_calls_repositories()
    {
        $id = 11;
        $walletEntity = WalletEntity::fromArray(['id' => 3, 'user_id' => $id]);

        $userRepo = $this->createMock(\App\Repositories\UserRepository::class);
        $walletRepo = $this->createMock(\App\Repositories\WalletRepository::class);

        $walletRepo->expects($this->once())->method('getWalletById')->with($id)->willReturn($walletEntity);
        $walletRepo->expects($this->once())->method('deleteWallet')->with($walletEntity->id);
        $userRepo->expects($this->once())->method('deleteUser')->with($id)->willReturn(true);

        $service = new ContaService($userRepo, $walletRepo);
        $result = $service->deleteUserWithWallet($id);

        $this->assertTrue($result);
    }
}
