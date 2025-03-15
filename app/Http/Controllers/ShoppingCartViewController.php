<?php

namespace App\Http\Controllers;

// use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


use App\Models\Categorie;
use Illuminate\Support\Facades\Schema;
use App\Helpers\PaginationHelper;
use App\Helpers\DataProcessing;
use Exception;

class ShoppingCartViewController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponseController $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     * Menampilkan daftar produk.
     * -[_PAGE_]-
     */
    public function index()
    {
        try {
            $limit = 10;
            $currentPage = 1;
            $sortColumn = 'updated_at';
            $sortOrder = 'desc';

            if (!Schema::hasColumn('products', $sortColumn)) {
                return $this->apiResponse->error(400, 'Invalid sort column.', null);
            }

            // Menggunakan eager loading dengan `with('products')`
            $query = Categorie::with('products')->orderBy($sortColumn, $sortOrder);
            $result = PaginationHelper::createPaginateFromEloquent($query, (int) $limit, (int) $currentPage);

            $dataArrayCategories = DataProcessing::ConvertStructToMap(
                $result['data'], 
                ['createdAt', 'updatedAt', 'deletedAt'] // Hapus kunci ini
            );
            
            foreach ($dataArrayCategories as &$categories) {
                $categories['products'] = DataProcessing::ConvertStructToMap($categories['products'], ['createdAt', 'updatedAt', 'deletedAt']);
            }
            
            // Pastikan $categories dalam format array dan bukan string
            if (is_string($categories)) {
                $categories = json_decode($categories, true);
            }
            
            // return $this->apiResponse->success(
            //     null,
            //     'Categories retrieved successfully.',
            //     $dataArrayCategories,
            //     $result['pagination']
            // );
            
            return view('page.shopping-cart.index', compact('dataArrayCategories'));
        } catch (Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while retrieving Categories.', null);
        }
    }

    /**
     * Menampilkan detail produk.
     * -[_PAGE_]-
     */
    public function show($id)
    {
        $product = Categorie::findOrFail($id);
        return view('page.shopping-cart.show', compact('product'));
    }

    /**
     * Menampilkan form tambah produk.
     * -[_PAGE_]-
     */
    public function create()
    {
        return view('page.shopping-cart.create');
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
            $imagePath = $request->file('image')->store('dataArrayCategories', 'public');
        }

        Categorie::create([
            'categorie_id' => $request->categorieId,
            'name' => $request->name,
            'price' => $request->price,
            'path_image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('categories.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit produk.
     * -[_PAGE_]-
     */
    public function edit($id)
    {
        $product = Categorie::findOrFail($id);
        return view('page.shopping-cart.edit', compact('product'));
    }

    /**
     * Memperbarui data produk.
     */
    public function update(Request $request, $id)
    {
        $product = Categorie::findOrFail($id);

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
            $product->path_image = $request->file('image')->store('dataArrayCategories', 'public');
        }

        $product->update([
            'categorie_id' => $request->categorieId,
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('categories.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk.
     */
    public function destroy($id)
    {
        $product = Categorie::findOrFail($id);

        // Hapus gambar jika ada
        if ($product->path_image) {
            Storage::disk('public')->delete($product->path_image);
        }

        $product->delete();

        return redirect()->route('categories.index')->with('success', 'Produk berhasil dihapus.');
    }
}
