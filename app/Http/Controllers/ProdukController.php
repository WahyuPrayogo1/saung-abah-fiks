<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Produk::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($row){
                    $editUrl = route('produk.edit', $row->id);
                    $deleteForm = '
                        <form action="'.route('produk.destroy', $row->id).'" method="POST" id="delete-form-'.$row->id.'" style="display:inline;">
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

        return view('backend.pages.produk.index');
    }

    public function create()
    {
        return view('backend.pages.produk.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:makanan,minuman',
            'status' => 'required|in:aktif,tidak_aktif',
            'harga' => 'required|integer',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Simpan gambar menggunakan metode store()
        $foto = $request->file('foto');
        $fotoPath = $foto->storeAs('uploads', time() . '_' . $foto->getClientOriginalName(), 'public');

        // Simpan data produk ke database
        Produk::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'harga' => $request->harga,
            'foto' => $fotoPath,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        return view('backend.pages.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:makanan,minuman',
            'status' => 'required|in:aktif,tidak_aktif',
            'harga' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->only('nama', 'jenis', 'status', 'harga', 'deskripsi');

        // Jika ada gambar baru
        if ($request->hasFile('foto')) {
            // Hapus gambar lama
            $pathLama = public_path('storage/' . $produk->foto); // Perbaiki path sesuai symbolic link
            if (File::exists($pathLama)) {
                File::delete($pathLama);
            }

            // Simpan gambar baru
            $file = $request->file('foto');
            $fotoPath = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName(), 'public');

            $data['foto'] = $fotoPath;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        // Hapus gambar
        $path = public_path('storage/' . $produk->foto); // Perbaiki path sesuai symbolic link
        if (File::exists($path)) {
            File::delete($path);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
