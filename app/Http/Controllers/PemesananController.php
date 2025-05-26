<?php
// app/Http/Controllers/PemesananController.php

namespace App\Http\Controllers;

use App\Models\PemesananDetails;
use App\Models\Pemesanans;
use App\Models\Produk;
use App\Models\Meja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class PemesananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil data pemesanan yang terbaru
            $data = Pemesanans::with('meja')->latest()->get();

            // Gunakan DataTables untuk mengolah data
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('meja_id', function($row) {
                    return $row->meja->nama ?? '-';
                })
                ->addColumn('aksi', function($row){
                    // URL untuk edit
                    $editUrl = route('pemesanans.edit', $row->id);

                    // Form delete dengan tombol
                    $deleteForm = '
                    <form action="'.route('pemesanans.destroy', $row->id).'" method="POST" id="delete-form-'.$row->id.'" style="display:inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" onclick="confirmDelete('.$row->id.')" class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>
                ';


                    // Tombol edit dan delete
                    return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm">Edit</a> '.$deleteForm;
                })
                ->rawColumns(['aksi']) // Menampilkan tombol aksi sebagai HTML
                ->make(true); // Mengembalikan data dalam format JSON untuk DataTables
        }

        // Tampilkan view dengan DataTables
        return view('pemesanan.index');
    }

    public function edit($id)
    {
        // Ambil data pemesanan berdasarkan ID
        $pemesanan = Pemesanan::findOrFail($id);

        // Kirim data pemesanan ke view edit
        return view('pemesanan.edit', compact('pemesanan'));
    }

    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanans::findOrFail($id);

        // Validasi data
        $request->validate([
            'meja_id' => 'required',
            'total_harga' => 'required|numeric',
            'status' => 'required',
        ]);

        // Update data pemesanan
        $pemesanan->update([
            'meja_id' => $request->meja_id,
            'total_harga' => $request->total_harga,
            'status' => $request->status,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('pemesanans.table')->with('success', 'Pemesanan berhasil diperbarui');
    }



  public function create(Request $request)
    {
        $produk = Produk::all();  // Ambil semua produk
        $meja = Meja::all();      // Ambil semua meja
        $selectedMejaId = $request->query('meja_id'); // Ambil meja_id dari URL jika ada

        return view('pemesanan.create', compact('produk', 'meja', 'selectedMejaId'));
    }


    public function store(Request $request)
{
    // Validasi dasar
    $request->validate([
        'meja_id' => 'required|exists:mejas,id',
        'produk_id' => 'required|array|min:1',
        'produk_id.*' => 'exists:produks,id',
        'jumlah' => 'required|array|min:1',
        'jumlah.*' => 'integer|min:1',
    ]);

    // Simpan pemesanan utama
    $pemesanans = Pemesanans::create([
        'meja_id' => $request->meja_id,
        'waktu_pemesanan' => now(),
        'total_harga' => 0, // Akan diupdate setelahnya
        'status' => 'pending',
    ]);

    // Hitung total harga dan simpan detail pemesanan
    $totalHarga = 0;

    foreach ($request->produk_id as $key => $produkId) {
        $produk = Produk::find($produkId);
        $jumlah = $request->jumlah[$key] ?? 1;

        // Hindari bug jika produk tidak ditemukan atau jumlah tidak valid
        if (!$produk || $jumlah < 1) continue;

        $harga = $produk->harga * $jumlah;

        PemesananDetails::create([
            'pemesanans_id' => $pemesanans->id,
            'produk_id' => $produkId,
            'jumlah' => $jumlah,
            'harga' => $harga,
        ]);

        $totalHarga += $harga;
    }

    // Update total harga pemesanan
    $pemesanans->update(['total_harga' => $totalHarga]);

    return back()->with('success', 'Pemesanan berhasil dibuat!');
}



    public function destroy($id)
    {
        // Cari data pemesanan berdasarkan ID
        $pemesanan = Pemesanans::findOrFail($id);

        // Hapus data terkait di pemesanan_details
        PemesananDetails::where('pemesanans_id', $id)->delete();

        // Hapus data pemesanan
        $pemesanan->delete();

        // Kembalikan respons sukses
        return redirect()->route('pemesanans.table')->with('success', 'Pemesanan berhasil dihapus');
    }

    public function handleQr(Request $request)
    {
        $mejaId = $request->meja_id;
        return redirect()->route('pemesanans.create', ['meja_id' => $mejaId]);
    }



}
