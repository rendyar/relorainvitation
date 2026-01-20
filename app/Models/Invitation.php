<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $table = 'guests';
    protected $guarded = [];

    public function souvenirs()
    {
        return $this->hasMany(Souvenir::class, 'type_invitation_id');
    }

    public function typeInvitation()
    {
        return $this->belongsTo(TypeInvitation::class, 'type_invitation_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'guest_id');
    }
}
