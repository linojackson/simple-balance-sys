<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Event;
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

    public function processEvent(Event $event): array
    {
        return match($event->type) {
            'deposit' => $this->handleDeposit($event),
            'withdraw' => $this->handleWithdraw($event),
            'transfer' => $this->handleTransfer($event),
            default => throw new \InvalidArgumentException(message: "Unknown event type: {$event->type}"),
        };
    }

    private function handleDeposit(Event $event): array
    {
        $destinationAccount = $this->accountRepository->find(id: $event->destination);
        if (!$destinationAccount) {
            $destinationAccount = new Account(id: $event->destination, balance: 0);
        }

        $destinationAccount->balance += $event->amount;

        $this->accountRepository->save($destinationAccount);

        return ['destination' => $destinationAccount->toArray()];
    }

    private function handleWithdraw(Event $event): array
    {
        $originAccount = $this->accountRepository->find(id: $event->origin);
        if (!$originAccount) {
            throw new ModelNotFoundException(message: "Account with ID {$event->origin} not found.");
        }

        // TODO: Make this validation after discussion with owner of position
        // if ($originAccount->balance < $event->amount) {
        //     throw new \InvalidArgumentException(message: "Insufficient funds in account ID {$event->origin}.");
        // }

        $originAccount->balance -= $event->amount;

        $this->accountRepository->save($originAccount);

        return ['origin' => $originAccount->toArray()];
    }

    private function handleTransfer(Event $event): array
    {
        $originAccount = $this->accountRepository->find(id: $event->origin);
        if (!$originAccount) {
            throw new ModelNotFoundException(message: "Account with ID {$event->origin} not found.");
        }

        $destinationAccount = $this->accountRepository->find(id: $event->destination);

        // At no point in the assignment did I find instructions on how to handle this step, so I followed the same approach used in the deposit method, which is to create the deposit account when it doesn’t already exist.
        if (!$destinationAccount) {
            $destinationAccount = new Account(id: $event->destination, balance: 0);
        }

        // TODO: Make this validation after discussion with owner of position
        // if ($originAccount->balance < $event->amount) {
        //     throw new \InvalidArgumentException(message: "Insufficient funds in account ID {$
        //         $event->origin}.");
        // }

        $originAccount->balance -= $event->amount;
        $destinationAccount->balance += $event->amount;

        $this->accountRepository->save($originAccount);
        $this->accountRepository->save($destinationAccount);

        return [
            'origin' => $originAccount->toArray(),
            'destination' => $destinationAccount->toArray(),
        ];
    }
}
