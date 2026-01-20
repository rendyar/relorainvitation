<?php

namespace App\Imports;

use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\TypeInvitation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitationsDataSheet implements ToModel, WithHeadingRow
{
    protected int $rowNumber = 0;

    public function model(array $row)
    {
        $this->rowNumber++;

        Log::info('[InvitationsDataSheet] Processing row', [
            'row' => $this->rowNumber,
            'row_data' => $row,
        ]);

        $typeInvitation = TypeInvitation::where(
            'name',
            strtolower(trim($row['type_undangan'] ?? ''))
        )->first();

        $souvenir = Souvenir::where(
            'name',
            strtolower(trim($row['souvenir'] ?? ''))
        )->first();

        return new Invitation([
            'name' => $row['nama'] ?? null,
            'type_invitation_id' => $typeInvitation?->id,
            'souvenir_id' => $souvenir?->id,
        ]);
    }
}
