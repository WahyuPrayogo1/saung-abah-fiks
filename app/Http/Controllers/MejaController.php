<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Generator;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class MejaController extends Controller
{
public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Meja::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('qr_code', function ($row) {
                if ($row->qr_code && file_exists(public_path($row->qr_code))) {
                    $url = asset($row->qr_code);
                    return '<img src="'.$url.'" width="70">';
                } else {
                    return '<span class="text-muted">Tidak Ada</span>';
                }
            })
            ->addColumn('aksi', function($row){
                $editUrl = route('meja.edit', $row->id);
                $deleteForm = '
                    <form action="'.route('meja.destroy', $row->id).'" method="POST" id="delete-form-'.$row->id.'" style="display:inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" onclick="confirmDelete('.$row->id.')" class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>
                ';
                return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm">Edit</a> '.$deleteForm;
            })
            ->rawColumns(['qr_code', 'aksi']) // <- pastikan ini termasuk
            ->make(true);
    }

    return view('backend.pages.meja.index');
}



    public function create()
    {
        return view('backend.pages.meja.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'is_available' => 'required|boolean',
    ]);

    $meja = Meja::create($request->only('nama', 'is_available'));

    // QR code data dan nama file
    $qrData = route('pemesanan.qr', ['meja_id' => $meja->id]);
    $qrName = 'meja_' . $meja->id . '.png';
    $qrPath = public_path('qr/' . $qrName); // ← langsung ke folder public/qr

    // Bikin QR code
    $result = Builder::create()
        ->writer(new PngWriter())
        ->data($qrData)
        ->size(300)
        ->build();

    // Simpan ke public/qr
    file_put_contents($qrPath, $result->getString());

    // Simpan path-nya (relatif dari public biar bisa ditampilkan di blade)
    $meja->update(['qr_code' => 'qr/' . $qrName]);

    return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan dengan QR Code!');
}
    public function edit(Meja $meja)
    {
        return view('backend.pages.meja.edit', compact('meja'));
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'is_available' => 'required|boolean',
        ]);

        $meja->update($request->all());

        return redirect()->route('meja.index')->with('success', 'Meja berhasil diperbarui!');
    }

  public function destroy(Meja $meja)
{
    // Hapus QR code dari folder public
    if ($meja->qr_code && file_exists(public_path($meja->qr_code))) {
        unlink(public_path($meja->qr_code));
    }

    $meja->delete();

    return redirect()->route('meja.index')->with('success', 'Meja berhasil dihapus!');
}


}
