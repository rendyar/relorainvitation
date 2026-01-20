<?php

namespace App\Http\Controllers;

use App\Imports\InvitationsImport;
use App\Models\Invitation;
use App\Models\Souvenir;
use App\Models\TypeInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class TypeInvitationController extends Controller
{
    public function index()
    {
        return view('master.type-invitation');
    }

    public function data()
    {
        $typeInvitations = TypeInvitation::all();

        return DataTables::of($typeInvitations)
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
        $typeInvitation = TypeInvitation::findOrFail($id);
        return response()->json($typeInvitation);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TypeInvitation::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'name' => $request->name,
            ]
        );

        $message = $request->id ? 'Type Invitation has been updated.' : 'Type Invitation created successfully.';

        return redirect()->route('type-invitation.index')->with('success', $message);
    }

    public function delete($id)
    {
        $typeInvitation = TypeInvitation::findOrFail($id);
        $typeInvitation->delete();

        return response()->json(['success' => 'Type Invitation deleted successfully.']);
    }

    public function select2(Request $request)
    {
        $query = TypeInvitation::query();

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

    public function export()
    {
        $invitations = Invitation::with(['typeInvitation', 'souvenirs'])
            ->select('guests.name', 'guests.qr_token', 'type_invitations.name as type_invitation_name', 'souvenirs.name as souvenir_name')
            ->leftJoin('type_invitations', 'guests.type_invitation_id','type_invitations.id')
            ->leftJoin('souvenirs', 'type_invitations.id', 'souvenirs.type_invitation_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Invitations');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ];

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Type Invitation');
        $sheet->setCellValue('C1', 'Souvenir');
        $sheet->setCellValue('D1', 'QR Token');
        $sheet->setCellValue('E1', 'QR Code');
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(20);

        $row = 2;
        foreach ($invitations as $invitation) {
            $sheet->setCellValue('A' . $row, $invitation->name);
            $sheet->setCellValue('B' . $row, $invitation->type_invitation_name);
            $sheet->setCellValue('C' . $row, $invitation->souvenir_name);
            $sheet->setCellValue('D' . $row, $invitation->qr_token);


            if ($invitation->qr_token) {
                $qrCode = QrCode::format('png')
                    ->size(150)
                    ->generate($invitation->qr_token);

                $drawing = new MemoryDrawing();
                $drawing->setName('QR Code');
                $drawing->setDescription('QR Code');
                $drawing->setImageResource(imagecreatefromstring($qrCode));
                $drawing->setRenderingFunction(MemoryDrawing::RENDERING_PNG);
                $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
                $drawing->setHeight(100);
                $drawing->setCoordinates('E' . $row);
                $drawing->setWorksheet($sheet);

                $sheet->getRowDimension($row)->setRowHeight(80);
            }

            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'invitations_with_qr.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::queueImport( new InvitationsImport, $request->file('file'));
            return redirect()->route('type-invitation.index')->with('success', 'Type Invitations imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('type-invitation.index')->with('error', 'There was an error importing the file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $typeInvitations = TypeInvitation::select('name')->get();
        $souvenirs = Souvenir::select('name')->get();

        $spreadsheet = new Spreadsheet();

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ];

        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Type Undangan');
        $sheet1->setCellValue('A1', 'Name');
        $sheet1->getStyle('A1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($typeInvitations as $type) {
            $sheet1->setCellValue('A' . $row, $type->name);
            $row++;
        }

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Souvenir');
        $sheet2->setCellValue('A1', 'Name');
        $sheet2->getStyle('A1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($souvenirs as $souvenir) {
            $sheet2->setCellValue('A' . $row, $souvenir->name);
            $row++;
        }

        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Data');
        $sheet3->setCellValue('A1', 'Nama');
        $sheet3->setCellValue('B1', 'Type Invitation');
        $sheet3->setCellValue('C1', 'Souvenir');
        $sheet3->getStyle('A1:C1')->applyFromArray($headerStyle);

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'invitations.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, $headers);
    }

    public function generateQRCode(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $qrCode = QrCode::format('png')->size(300)->generate($request->text);

        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
