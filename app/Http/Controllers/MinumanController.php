<?php

namespace App\Http\Controllers;

use App\Models\Minuman;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MinumanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Minuman::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($row){
                    $editUrl = route('minuman.edit', $row->id);
                    $deleteForm = '
                        <form action="'.route('minuman.destroy', $row->id).'" method="POST" id="delete-form-'.$row->id.'" style="display:inline;">
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

        return view('minuman.index');
    }

    public function create()
    {
        return view('minuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        Minuman::create($request->all());

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $minuman = Minuman::findOrFail($id);
        return view('minuman.edit', compact('minuman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        $minuman = Minuman::findOrFail($id);
        $minuman->update($request->all());

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $minuman = Minuman::findOrFail($id);
        $minuman->delete();

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil dihapus.');
    }
}
