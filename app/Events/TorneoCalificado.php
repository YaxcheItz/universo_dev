<?php

namespace App\Events;

use App\Models\Torneo;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TorneoCalificado
{
    use Dispatchable, SerializesModels;

    public $torneo;

    /**
     * Create a new event instance.
     */
    public function __construct(Torneo $torneo)
    {
        $this->torneo = $torneo;
    }
}
