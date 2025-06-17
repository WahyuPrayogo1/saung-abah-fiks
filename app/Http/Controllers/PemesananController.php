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
        // Ambil data pemesanan dengan relasi meja dan details.produk
        $data = Pemesanans::with(['meja', 'details.produk'])->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('meja', function($row) {
                return $row->meja->nama ?? '-';
            })
            ->addColumn('produk', function($row) {
                $produkList = [];
                foreach ($row->details as $detail) {
                    $produkList[] = $detail->produk->nama . ' (x' . $detail->jumlah . ')';
                }
                return implode('<br>', $produkList);
            })
            ->addColumn('aksi', function($row){
                $editUrl = route('pemesanans.edit', $row->id);
                $deleteForm = '
                <form action="'.route('pemesanans.destroy', $row->id).'" method="POST" id="delete-form-'.$row->id.'" style="display:inline;">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button type="button" onclick="confirmDelete('.$row->id.')" class="btn btn-danger btn-sm">
                        Hapus
                    </button>
                </form>';

                return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm">Edit</a> '.$deleteForm;
            })
            ->rawColumns(['produk', 'aksi'])
            ->make(true);
    }

    return view('pemesanan.index');
}

   public function edit($id)
    {
        $pemesanan = Pemesanans::with(['meja', 'details.produk'])->findOrFail($id);
        $mejas = Meja::all();
        $produks = Produk::all();

        return view('pemesanan.edit', compact('pemesanan', 'mejas', 'produks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'meja_id' => 'required|exists:mejas,id',
            'status' => 'required|in:pending,completed,cancelled',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1'
        ]);

        // Mulai transaction
        \DB::beginTransaction();

        try {
            $pemesanan = Pemesanans::findOrFail($id);
            $pemesanan->update([
                'meja_id' => $request->meja_id,
                'status' => $request->status
            ]);

            // Hapus detail lama
            PemesananDetails::where('pemesanans_id', $id)->delete();

            // Simpan detail baru
            $total_harga = 0;
            foreach ($request->produk_id as $key => $produk_id) {
                $produk = Produk::find($produk_id);
                $subtotal = $produk->harga * $request->jumlah[$key];

                PemesananDetails::create([
                    'pemesanans_id' => $pemesanan->id,
                    'produk_id' => $produk_id,
                    'jumlah' => $request->jumlah[$key],
                    'harga' => $subtotal
                ]);

                $total_harga += $subtotal;
            }

            // Update total harga
            $pemesanan->update(['total_harga' => $total_harga]);

            \DB::commit();

            return redirect()->route('pemesanans.table')->with('success', 'Pemesanan berhasil diperbarui');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error', 'Gagal memperbarui pemesanan: '.$e->getMessage());
        }
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
        'pembayaran' => 'required|in:online,kasir',
    ]);

    // Simpan pemesanan utama
    $pemesanan = Pemesanans::create([
        'meja_id' => $request->meja_id,
        'waktu_pemesanan' => now(),
        'total_harga' => 0,
        'status' => 'pending',
        'pembayaran' => $request->pembayaran,
    ]);

    // Hitung total harga dan simpan detail pemesanan
    $totalHarga = 0;

    foreach ($request->produk_id as $key => $produkId) {
        $produk = Produk::find($produkId);
        $jumlah = $request->jumlah[$key] ?? 1;

        if (!$produk || $jumlah < 1) continue;

        $harga = $produk->harga * $jumlah;

        PemesananDetails::create([
            'pemesanans_id' => $pemesanan->id,
            'produk_id' => $produkId,
            'jumlah' => $jumlah,
            'harga' => $harga,
        ]);

        $totalHarga += $harga;
    }

    // Update total harga pemesanan
    $pemesanan->update(['total_harga' => $totalHarga]);

    if ($request->pembayaran === 'online') {
        $pemesanan->update([
            'payment_status' => 'pending',
            'snap_token' => 'ORDER-' . $pemesanan->id . '-' . time()
        ]);

        // Redirect ke halaman pembayaran Midtrans
        return redirect()->route('payment.create', ['pemesanan_id' => $pemesanan->id])
            ->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    // Jika pembayaran di kasir
    return back()->with('success', 'Pemesanan berhasil dibuat! Silakan bayar di kasir setelah selesai.');
}



    public function destroy($id)
    {
        $pemesanan = Pemesanans::findOrFail($id);

        \DB::beginTransaction();
        try {
            // Hapus detail terlebih dahulu
            PemesananDetails::where('pemesanans_id', $id)->delete();

            // Hapus pemesanan
            $pemesanan->delete();

            \DB::commit();

            return redirect()->route('pemesanans.table')->with('success', 'Pemesanan berhasil dihapus');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error', 'Gagal menghapus pemesanan: '.$e->getMessage());
        }
    }

    public function handleQr(Request $request)
    {
        $mejaId = $request->meja_id;
        return redirect()->route('pemesanans.create', ['meja_id' => $mejaId]);
    }



}
