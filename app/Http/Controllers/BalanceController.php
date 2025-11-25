<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Http\Response;

class BalanceController extends Controller
{
    public function __construct(
        private AccountService $accountService
    ) {
    }

    public function getBalance(Request $request): Response
    {
        $accountId = $request->query(key: 'account_id', default: '');

        try {
            $balance = $this->accountService->getBalance(accountId: $accountId);

            return response(
                content: $balance,
                status: 200
            )->header(key: 'Content-Type', values: 'text/plain');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response(
                content: 0,
                status: 404
            )->header(key: 'Content-Type', values: 'text/plain');
        }
    }

    public function reset(): Response
    {
        $this->accountService->resetAccounts();
        return response(
            content: 'OK',
            status: 200
        )->header(key: 'Content-Type', values: 'text/plain');
    }
}
