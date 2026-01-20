<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\SouvenirLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function totalGuests()
    {
        $totalGuests = Invitation::count();

        $totalCheckIns = Attendance::whereNotNull('attendances.check_in_at')
            ->distinct('guest_id')
            ->count('guest_id');

        $totalInside = Attendance::whereNull('attendances.check_out_at')
            ->distinct('guest_id')
            ->count('guest_id');

        $totalCheckOuts = Attendance::whereNotNull('attendances.check_in_at')
            ->whereNotNull('attendances.check_out_at')
            ->distinct('guest_id')
            ->count('guest_id');


             $liveActivities = [];

        $attendances = Attendance::with(['invitation', 'invitation.typeInvitation'])
            ->orderBy('check_in_at', 'asc')
            ->get();

        foreach ($attendances as $attendance) {
            if ($attendance->check_in_at) {
                $liveActivities[] = [
                    'time' => Carbon::parse($attendance->check_in_at)->format('H:i'),
                    'name' => $attendance->invitation->name,
                    'status' => 'Check-In',
                    'type_invitation' => $attendance->invitation->typeInvitation ? $attendance->invitation->typeInvitation->name : null,
                ];
            }
            if ($attendance->check_out_at) {
                $liveActivities[] = [
                    'time' => Carbon::parse($attendance->check_out_at)->format('H:i'),
                    'name' => $attendance->invitation->name,
                    'status' => 'Check-Out',
                    'type_invitation' => $attendance->invitation->typeInvitation ? $attendance->invitation->typeInvitation->name : null,
                ];
            }
        }

        $souvenirs = Souvenir::all();
        $souvenirData = [];
        foreach($souvenirs as $item) {
            $usedQuantity = SouvenirLog::leftJoin('guests', 'souvenir_logs.guest_id', 'guests.id')
            ->leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->where('type_invitations.id', $item->type_invitation_id)
            ->count();

            $souvenirData[] = [
                'name' => $item->name,
                'quantity' => $item->stock,
                'current_stock' => $usedQuantity,
            ];
        }

        return response()->json([
            'total_guests' => $totalGuests,
            'total_check_in' => $totalCheckIns,
            'total_inside' => $totalInside,
            'total_check_out' => $totalCheckOuts,
            'live_activities' => $liveActivities,
            'souvenir_data' => $souvenirData,
        ]);
    }
}
