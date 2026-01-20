<?php

namespace App\Imports;

use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\TypeInvitation;
use Maatwebsite\Excel\Concerns\ShouldQueueWithoutChain;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitationsImport implements ToModel, WithHeadingRow, ShouldQueueWithoutChain
{
    public function model(array $row)
    {
        $typeInvitation = TypeInvitation::where(
            'name',
            strtolower(trim($row['type_undangan']))
        )->first();

        $souvenir = Souvenir::where(
            'name',
            strtolower(trim($row['souvenir']))
        )->first();

        return new Invitation([
            'name' => $row['nama'],
            'type_invitation_id' => $typeInvitation->id,
            'souvenir_id' => $souvenir->id,
        ]);
    }
}
