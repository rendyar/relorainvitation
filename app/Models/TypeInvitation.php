<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeInvitation extends Model
{
    use HasFactory;

    protected $table = 'type_invitations';
    protected $guarded = [];

    public function souvenirs()
    {
        return $this->hasMany(Souvenir::class, 'type_invitation_id');
    }
}
