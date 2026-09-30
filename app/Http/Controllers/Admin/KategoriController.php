<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Show the paginated category list.
     */
    public function index()
    {
        $kategori = Kategori::latest('id_kategori')->paginate(10);

        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Show the category creation form.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Validate and store a new category.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori,nama_kategori'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the edit form for an existing category.
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Validate and save changes to a category.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori', 'nama_kategori')
                    ->ignore($kategori->id_kategori, 'id_kategori'),
            ],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Delete a category.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
