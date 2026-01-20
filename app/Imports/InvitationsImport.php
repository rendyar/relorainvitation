<?php

namespace App\Imports;

use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\TypeInvitation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitationsImport implements ToModel
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $typeInvitation = TypeInvitation::where('name', strtolower($row['type_undangan']))->first();
        $souvenir = Souvenir::where('name', strtolower($row['souvenir']))->first();

        return new Invitation([
            'name' => $row['nama'],
            'type_invitation_id' => $typeInvitation ? $typeInvitation->id : null,
            'souvenir_id' => $souvenir ? $souvenir->id : null
        ]);
    }

    public function sheets(): array
    {
        return [
            'Data' => $this,
        ];
    }
}
