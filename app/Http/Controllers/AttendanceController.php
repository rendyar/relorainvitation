<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TypeInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('master.attendance');
    }

    public function dataCheckIn()
    {
        $checkInData = Attendance::leftJoin('guests', 'attendances.guest_id', 'guests.id')
            ->leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->selectRaw('
                attendances.*,
                guests.name as guest_name,
                type_invitations.name as type_invitation_name,
                DATE_FORMAT(attendances.check_in_at, "%H:%i") as check_in_at,
                IFNULL(DATE_FORMAT(attendances.check_out_at, "%H:%i"), "-") as check_out_at
            ')
            ->get();

        return DataTables::of($checkInData)->addIndexColumn()->make(true);
    }

    public function dataCheckOut()
    {
        $checkOutData = Attendance::leftJoin('guests', 'attendances.guest_id', 'guests.id')
            ->leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->whereNotNull('check_out_at')
            ->selectRaw('
                attendances.*,
                guests.name as guest_name,
                type_invitations.name as type_invitation_name,
                DATE_FORMAT(attendances.check_in_at, "%H:%i") as check_in_at,
                IFNULL(DATE_FORMAT(attendances.check_out_at, "%H:%i"), "-") as check_out_at
            ')
            ->get();

        return DataTables::of($checkOutData)->addIndexColumn()->make(true);
    }
}
