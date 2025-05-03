<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class MakananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Makanan::latest()->get(); // Mengambil data makanan
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($row){
                    $editUrl = route('makanan.edit', $row->id);
                    $deleteForm = '
                        <form action="'.route('makanan.destroy', $row->id).'" method="POST" id="delete-form-'.$row->id.'" style="display:inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button" onclick="confirmDelete('.$row->id.')" class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>
                    ';
                    return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm">Edit</a> '.$deleteForm;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('backend.pages.makanan.index');
    }

    public function create()
    {
        return view('backend.pages.makanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
        ]);

        // Menyimpan foto jika ada
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads', $foto); // Menyimpan foto menggunakan Storage
        }

        Makanan::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
        ]);

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $makanan = Makanan::findOrFail($id);
        return view('backend.pages.makanan.edit', compact('makanan'));
    }

    public function update(Request $request, $id)
    {
        $makanan = Makanan::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validasi file foto
        ]);

        // Update data makanan
        $makanan->nama = $request->nama;
        $makanan->harga = $request->harga;
        $makanan->deskripsi = $request->deskripsi;

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($makanan->foto && Storage::exists('public/uploads/'.$makanan->foto)) {
                Storage::delete('public/uploads/'.$makanan->foto);
            }

            // Simpan foto baru
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/uploads', $fotoName);

            $makanan->foto = $fotoName;
        }

        // Simpan perubahan
        $makanan->save();

        return redirect()->route('makanan.index')->with('success', 'Data makanan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $makanan = Makanan::findOrFail($id);

        // Hapus foto jika ada
        if ($makanan->foto && Storage::exists('public/uploads/'.$makanan->foto)) {
            Storage::delete('public/uploads/'.$makanan->foto);
        }

        $makanan->delete();

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil dihapus.');
    }
}
