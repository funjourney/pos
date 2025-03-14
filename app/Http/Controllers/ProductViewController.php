<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductViewController extends Controller
{
    /**
     * Menampilkan daftar produk.
     * -[_PAGE_]-
     */
    public function index()
    {
        $products = Product::orderBy('updated_at', 'desc')->paginate(10);
        return view('page.products.index', compact('products'));
    }

    /**
     * Menampilkan detail produk.
     * -[_PAGE_]-
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('page.products.show', compact('product'));
    }

    /**
     * Menampilkan form tambah produk.
     * -[_PAGE_]-
     */
    public function create()
    {
        return view('page.products.create');
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categorieId' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'categorie_id' => $request->categorieId,
            'name' => $request->name,
            'price' => $request->price,
            'path_image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit produk.
     * -[_PAGE_]-
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('page.products.edit', compact('product'));
    }

    /**
     * Memperbarui data produk.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'categorieId' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->path_image) {
                Storage::disk('public')->delete($product->path_image);
            }
            // Simpan gambar baru
            $product->path_image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'categorie_id' => $request->categorieId,
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar jika ada
        if ($product->path_image) {
            Storage::disk('public')->delete($product->path_image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
