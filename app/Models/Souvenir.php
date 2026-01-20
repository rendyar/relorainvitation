<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souvenir extends Model
{
    use HasFactory;

    protected $table = 'souvenirs';
    protected $guarded = [];

    public function typeInvitation()
    {
        return $this->belongsTo(TypeInvitation::class, 'type_invitation_id');
    }

    public function souvenirLogs()
    {
        return $this->hasMany(SouvenirLog::class, 'souvenir_id');
    }
}
