<?php

namespace App\Models;

class Account
{
    public function __construct(
        public string $id,
        public int $balance = 0
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->balance,
        ];
    }
}
