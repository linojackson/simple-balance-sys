<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(
        private AccountService $accountService
    ) {
    }

    public function handleEvent(Request $request): JsonResponse
    {
        $data = $request->validate(rules: [
            'type' => 'required|in:deposit,withdraw,transfer',
            'origin' => 'nullable|required_if:type,withdraw,transfer|string',
            'destination' => 'nullable|required_if:type,deposit,transfer|string',
            'amount' => 'required|integer|min:1',
        ]);

        $event = new Event(
            type: $data['type'],
            origin: $data['origin'] ?? null,
            destination: $data['destination'] ?? null,
            amount: $data['amount']
        );

        try {
            $result = $this->accountService->processEvent(event: $event);

            $responseBody = [];

            foreach ($result as $key => $accountData) {
                $responseBody[$key] = [
                    'id' => $accountData['id'],
                    'balance' => $accountData['balance'],
                ];
            }

            return response()->json(data: $responseBody, status: 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(data: ['error' => $e->getMessage()], status: 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(data: 0, status: 404);
        }
    }
}
