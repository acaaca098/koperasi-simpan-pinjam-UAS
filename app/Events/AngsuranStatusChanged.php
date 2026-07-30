<?php

namespace App\Events;

use App\Models\Angsuran;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AngsuranStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Angsuran $angsuran,
        public string $statusSebelumnya,
    ) {}
}
