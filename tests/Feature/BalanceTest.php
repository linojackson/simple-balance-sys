<?php

use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    Cache::flush();
});

test('get balance for existing account', function (): void {
    $accountRepository = new AccountRepository();
    $accountRepository->save(account: new Account(id: 'user_1', balance: 1000));

    $this->get(uri: '/balance?account_id=user_1')
        ->assertStatus(status: 200)
        ->assertContent(value: '1000');
});

test('get balance for non-existing account returns 404', function (): void {
    $this->get(uri: '/balance?account_id=1234')
        ->assertStatus(status: 404)
        ->assertContent(value: '0');
});

test('reset accounts, clears all data successfully', function (): void {
    $this->post(uri: '/reset')
        ->assertStatus(status: 200)
        ->assertContent(value: 'OK');
});
