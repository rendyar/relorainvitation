<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\SouvenirLog;
use App\Models\TypeInvitation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CheckInController extends Controller
{
    public function index()
    {
        return view('checkin.qr');
    }

    public function checkin(Request $request)
    {
        $qr = $request->qr_code;
        $qr = Invitation::where('qr_token', $qr)->first();

        if ($qr) {
            if(!Attendance::where('guest_id', $qr->id)->exists()) {
                Attendance::create([
                    'guest_id' => $qr->id,
                    'check_in_at' => Carbon::now('Asia/Jakarta'),
                    'checked_in_by' => Auth::id(),
                ]);

                return response()->json([
                    'name' => $qr->name,
                    'type_invitation' => $qr->typeInvitation->name,
                    'success' => true,
                    'message' => 'Check-in berhasil'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tamu atas nama ' . $qr->name . ' sudah melakukan check-in sebelumnya'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid'
            ]);
        }
    }
    public function checkout(Request $request)
    {
        $qr = $request->qr_code;
        $qr = Invitation::where('qr_token', $qr)->first();

        if ($qr) {
            if(Attendance::where('guest_id', $qr->id)->whereNotNull('check_in_at')->whereNull('check_out_at')->exists()) {

                $souvenir = Souvenir::where('type_invitation_id', $qr->type_invitation_id)->first();

                Attendance::updateOrCreate([
                    'guest_id' => $qr->id,
                ], [
                    'check_out_at' => Carbon::now('Asia/Jakarta'),
                    'checked_out_by' => Auth::id(),
                ]);

                SouvenirLog::create([
                    'guest_id' => $qr->id,
                    'admin_id' => Auth::id(),
                    'taken_at' => Carbon::now('Asia/Jakarta'),
                ]);

                return response()->json([
                    'name' => $qr->name,
                    'type_invitation' => $qr->typeInvitation->name,
                    'type_souvenir' => $souvenir->name ?? '-',
                    'success' => true,
                    'message' => 'Check-out berhasil'
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tamu atas nama ' . $qr->name . ' sudah melakukan check-out sebelumnya'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid'
            ]);
        }
    }

    public function manual()
    {
        $typeInvitations = TypeInvitation::all();

        return view('checkin.manual', compact('typeInvitations'));
    }

    public function dataCheckIn()
    {
        $invitations = Invitation::leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->leftJoin('souvenirs', 'guests.type_invitation_id', 'souvenirs.type_invitation_id')
            ->leftJoin('attendances', 'guests.id', 'attendances.guest_id')
            ->select('guests.*', 'type_invitations.name as type_invitation_name', 'souvenirs.name as souvenir_name', 'attendances.check_in_at', 'attendances.check_out_at')
            ->whereNull('attendances.check_in_at')
            ->get();

        return DataTables::of($invitations)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-checkin" data-name="'.$row->name.'" data-qr-code="'.$row->qr_token.'" style="background-color: #b96bb0; color: #fff;">Check In</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataCheckOut()
    {
        $invitations = Invitation::leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->leftJoin('souvenirs', 'guests.type_invitation_id', 'souvenirs.type_invitation_id')
            ->leftJoin('attendances', 'guests.id', 'attendances.guest_id')
            ->select('guests.*', 'type_invitations.name as type_invitation_name', 'souvenirs.name as souvenir_name', 'attendances.check_in_at', 'attendances.check_out_at')
            ->whereNotNull('attendances.check_in_at')
            ->whereNull('attendances.check_out_at')
            ->get();

        return DataTables::of($invitations)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-checkout" data-name="'.$row->name.'" data-qr-code="'.$row->qr_token.'" style="background-color: #b96bb0; color: #fff;">Check Out</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
