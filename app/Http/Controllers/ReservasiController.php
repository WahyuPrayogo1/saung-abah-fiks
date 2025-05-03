<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $mejas = Meja::all();
        return view('reservasi', compact('mejas'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal' => 'required',
            'jam' => 'required',
            'jumlah_orang' => 'required|integer',
            'nomor_telepon' => 'required',
            'meja_id' => 'required|exists:mejas,id',
        ]);

        $meja = Meja::find($request->meja_id);
        if ($meja->status) {
            return back()->with('error', 'Meja sudah dipesan!');
        }

        Reservasi::create($request->all());

        $meja->status = true;
        $meja->save();

        return back()->with('success', 'Reservasi berhasil!');
    }
}
