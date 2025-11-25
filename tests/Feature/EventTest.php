<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Account;
use App\Repositories\AccountRepository;

beforeEach(function (): void {
    Cache::flush();
});

test('1. should be able to create and account with initial deposit', function (): void {
    $response = $this->postJson(uri: '/event', data: [
        'type' => 'deposit',
        'destination' => '100',
        'amount' => 10
    ]);

    $response->assertStatus(status: 201)
        ->assertJson(value: [
            'destination' => [
                'id' => '100',
                'balance' => 10
            ]
        ]);
});

test('2. should be able to deposit into existing account', function (): void {
    $repository = new AccountRepository();
    $repository->save(account: new Account(id: '100', balance: 10));

    $response = $this->postJson(uri: '/event', data: [
        'type' => 'deposit',
        'destination' => '100',
        'amount' => 10
    ]);

    $response->assertStatus(status: 201)
        ->assertJson(value: [
            'destination' => [
                'id' => '100',
                'balance' => 20
            ]
        ]);
});

test('3. should return 404 and 0 when withdraw from non-existing account', function (): void {
    $response = $this->postJson(uri: '/event', data: [
        'type' => 'withdraw',
        'origin' => '200',
        'amount' => 10
    ]);

    $response->assertStatus(status: 404)
        ->assertContent(value: '0');
});

test('4. should be able to withdraw from existing account', function (): void {
    $repository = new AccountRepository();
    $repository->save(account: new Account(id: '100', balance: 15));

    $response = $this->postJson(uri: '/event', data: [
        'type' => 'withdraw',
        'origin' => '100',
        'amount' => 5
    ]);

    $response->assertStatus(status: 201)
        ->assertJson(value: [
            'origin' => [
                'id' => '100',
                'balance' => 10
            ]
        ]);
});

test('5. should be able to transfer between account (creating destination if not exists)', function (): void {
    $repository = new AccountRepository();
    $repository->save(account: new Account(id: '100', balance: 15));

    $response = $this->postJson(uri: '/event', data: [
        'type' => 'transfer',
        'origin' => '100',
        'destination' => '300',
        'amount' => 15
    ]);

    $response->assertStatus(status: 201)
        ->assertJson(value: [
            'origin' => [
                'id' => '100',
                'balance' => 0
            ],
            'destination' => [
                'id' => '300',
                'balance' => 15
            ]
        ]);
});

test('6. should return 404 and 0 when transfer from non-existing account', function (): void {
    $response = $this->postJson(uri: '/event', data: [
        'type' => 'transfer',
        'origin' => '200',
        'destination' => '300',
        'amount' => 15
    ]);

    $response->assertStatus(status: 404)
        ->assertContent(value: '0');
});