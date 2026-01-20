<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\SouvenirLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }
}
