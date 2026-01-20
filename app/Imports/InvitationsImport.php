<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ShouldQueueWithoutChain;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InvitationsImport implements WithMultipleSheets, ShouldQueueWithoutChain
{
    public function __construct()
    {
        Log::info('[InvitationsImport] Job STARTED');
    }

    public function sheets(): array
    {
        return [
            'Data' => new InvitationsDataSheet,
        ];
    }

    public function __destruct()
    {
        Log::info('[InvitationsImport] Job FINISHED');
    }
}
