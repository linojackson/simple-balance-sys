<?php

namespace App\Models;

class Event
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $origin,
        public readonly ?string $destination,
        public readonly int $amount
    ) {
    }
}
