<?php

namespace App\Events;

use App\Models\Pinjaman;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PinjamanStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Pinjaman $pinjaman,
        public string $statusSebelumnya,
    ) {}
}
