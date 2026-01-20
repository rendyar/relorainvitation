<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\TypeInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class InvitationController extends Controller
{
    public function index()
    {
        $typeInvitations = TypeInvitation::all();
        return view('master.invitation', compact('typeInvitations'));
    }

    public function data()
    {
        $invitations = Invitation::leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->leftJoin('souvenirs', 'guests.type_invitation_id', 'souvenirs.type_invitation_id')
            ->select('guests.*', 'type_invitations.name as type_invitation_name', 'souvenirs.name as souvenir_name')
            ->get();

        return DataTables::of($invitations)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-primary btn-edit" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function edit($id)
    {
        $invitation = Invitation::leftJoin('type_invitations', 'guests.type_invitation_id', 'type_invitations.id')
            ->leftJoin('souvenirs', 'guests.type_invitation_id', 'souvenirs.type_invitation_id')
            ->select('guests.*', 'type_invitations.name as type_invitation_name', 'souvenirs.name as souvenir_name')
            ->findOrFail($id);
            
        return response()->json($invitation);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_invitation_id' => 'required|exists:type_invitations,id',
            'qr_token' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'type_invitation_id' => $request->type_invitation_id,
        ];

        if (!$request->id) {
            $data['qr_token'] = strtoupper(Str::random(5));
        }

        Invitation::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        $message = $request->id ? 'Invitation has been updated.' : 'Invitation created successfully.';

        return redirect()->route('type-invitation.index')->with('success', $message);
    }

    public function delete($id)
    {
        $invitation = Invitation::findOrFail($id);
        $invitation->delete();

        return response()->json(['success' => 'Invitation deleted successfully.']);
    }

    public function select2(Request $request)
    {
        $query = Invitation::query();

        if ($request->q) {
            $query->where('name', 'LIKE', '%' . $request->q . '%');
        }

        $data = $query->limit(20)->get();

        return response()->json([
            'results' => $data->map(fn($i) => [
                'id' => $i->id,
                'text' => $i->name,
            ])
        ]);
    }

}
