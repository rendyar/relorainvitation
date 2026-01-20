<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirLog extends Model
{
    use HasFactory;

    protected $table = 'souvenir_logs';
    protected $guarded = [];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class, 'guest_id');
    }
}
