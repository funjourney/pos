<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponseController $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);
            $currentPage = $request->query('page', 1);

            $query = Product::query();
            $result = PaginationHelper::createPaginateFromEloquent($query, (int) $limit, (int) $currentPage);

            return $this->apiResponse->success(
                null,
                'Products retrieved successfully.',
                $result['data'],
                $result['pagination']
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while retrieving products.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used in API-based applications
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                // 'image' => 'nullable|file',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public'); // Simpan di storage/app/public/products
            }

            $product = Product::create([
                // 'id' => DB::raw('(UUID())'),
                'name' => $request->name,
                // 'path_image' => $request->file('image') ? file_get_contents($request->file('image')) : null,
                'path_image' => $imagePath,
                'type' => $request->type,
                'status' => $request->status,
            ]);

            return $this->apiResponse->success(
                201,
                'Product created successfully.',
                $product,
                null
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while creating the product.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            return $this->apiResponse->success(
                null,
                'Product retrieved successfully.',
                $product,
                null
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(404, 'Product not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not used in API-based applications
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'name' => 'string',
                // 'image' => 'nullable|file',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
        
                // Simpan gambar baru
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->update([
                'name' => $request->name ?? $product->name,
                // 'path_image' => $request->file('image') ? file_get_contents($request->file('image')) : $product->image,
                'path_image' => $product->image,
                'type' => $request->type ?? $product->type,
                'status' => $request->status ?? $product->status,
            ]);

            return $this->apiResponse->success(
                null,
                'Product updated successfully.',
                $product,
                null
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while updating the product.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $modeDelete = "PERMANENT";
            $product = Product::findOrFail($id);
        
            if ($modeDelete == "TEMPOARY") {
                // Hapus gambar dari storage jika ada
                if ($product->path_image) {
                    $imagePath = storage_path("app/public/{$product->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk dari database
                $product->delete();
            
                return $this->apiResponse->success(
                    204,
                    'Product temporarily deleted successfully.',
                    null,
                    null
                );
            } else {
                // Hapus gambar dari storage jika ada
                if ($product->path_image) {
                    $imagePath = storage_path("app/public/{$product->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk secara permanen dari database
                $product->forceDelete();
            
                return $this->apiResponse->success(
                    204,
                    'Product permanently deleted successfully.',
                    null,
                    null
                );
            }
        } catch (Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while deleting the product.');
        }
    }


}
