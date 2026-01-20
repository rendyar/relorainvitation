<?php

namespace App\Http\Controllers;

use App\Models\Souvenir;
use App\Models\TypeInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SouvenirController extends Controller
{
    public function index()
    {
        $typeInvitations = TypeInvitation::all();

        return view('master.souvenir', compact('typeInvitations'));
    }

    public function data()
    {
        $souvenirs = Souvenir::with('typeInvitation')->get();
        
        return DataTables::of($souvenirs)
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
        $souvenir = Souvenir::findOrFail($id);

        return response()->json($souvenir);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
            'type_invitation_id' => 'required|exists:type_invitations,id',
        ]);

        Souvenir::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'stock' => $request->stock,
                'type_invitation_id' => $request->type_invitation_id,
            ]
        );

        $message = $request->id ? 'Souvenir has been updated.' : 'Souvenir created successfully.';

        return redirect()->route('souvenir.index')->with('success', $message);
    }

    public function delete($id)
    {
        $souvenir = Souvenir::findOrFail($id);
        $souvenir->delete();

        return response()->json(['success' => 'Souvenir deleted successfully.']);
    }

    public function select2(Request $request)
    {
        $search = $request->input('q');

        $query = Souvenir::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $souvenirs = $query->limit(10)->get();

        $results = [];
        foreach ($souvenirs as $souvenir) {
            $results[] = [
                'id' => $souvenir->id,
                'text' => $souvenir->name,
            ];
        }

        return response()->json(['results' => $results]);
    }
}
