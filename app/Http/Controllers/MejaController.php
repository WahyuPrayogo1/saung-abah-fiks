<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MejaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Meja::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
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
                ->rawColumns(['aksi'])
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

        Meja::create($request->all());

        return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan!');
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
        $meja->delete();

        return redirect()->route('meja.index')->with('success', 'Meja berhasil dihapus!');
    }
}
