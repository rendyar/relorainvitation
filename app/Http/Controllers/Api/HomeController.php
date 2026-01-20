<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\SouvenirLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getTotalGuest()
    {
        $totalGuests = Invitation::count();
        
        return response()->json(['total_guests' => $totalGuests]);
    }
}
