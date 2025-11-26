<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Support\Facades\Cache;

class AccountRepository
{
    private const CACHE_PREFIX = 'account_';

    public function find(string $id): ?Account
    {
        $data = Cache::get(key: self::CACHE_PREFIX . $id);
        if (!$data) {
            return null;
        }
        return new Account(id: $data['id'], balance: $data['balance']);
    }

    public function save(Account $account): void
    {
        Cache::put(
            key: self::CACHE_PREFIX . $account->id,
            value: $account->toArray()
        );
    }

    public function reset(): void
    {
        Cache::flush();
    }
}
