<?php

namespace App\Services;

use App\Repositories\AccountRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountService
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {
    }

    public function getBalance(string $accountId): int
    {
        $account = $this->accountRepository->find(id: $accountId);
        if (!$account) {
            throw new ModelNotFoundException(message: "Account with ID {$accountId} not found.");
        }
        return $account->balance;
    }

    public function resetAccounts(): void
    {
        $this->accountRepository->reset();
    }
}
