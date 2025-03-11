<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Validation\ValidationException;

class CategorieController extends Controller
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
            $sortColumn = $request->query('sortColumn', 'updated_at'); // Default sort by 'updated_at'
            $sortOrder = strtolower($request->query('sortOrder', 'desc')) === 'asc' ? 'asc' : 'desc'; // Default desc

            // Validasi apakah kolom ada di dalam tabel categories
            if (!Schema::hasColumn('categories', $sortColumn)) {
                return $this->apiResponse->error(400, 'Invalid sort column.',null);
            }

            $query = Categorie::query()->orderBy($sortColumn, $sortOrder);
            $result = PaginationHelper::createPaginateFromEloquent($query, (int) $limit, (int) $currentPage);

            return $this->apiResponse->success(
                null,
                'Categories retrieved successfully.',
                $result['data'],
                $result['pagination']
            );
        } catch (Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while retrieving Categories.',null);
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
                'status' => 'nullable|string',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('Categories', 'public'); // Simpan di storage/app/public/Categories
            }

            $Categorie = Categorie::create([
                // 'id' => DB::raw('(UUID())'),
                'name' => $request->name,
                // 'path_image' => $request->file('image') ? file_get_contents($request->file('image')) : null,
                'path_image' => $imagePath,
                'status' => $request->status,
            ]);

            return $this->apiResponse->success(
                201,
                'Categorie created successfully.',
                $Categorie,
                null
            );
        } catch (ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));

            return $this->apiResponse->error(422, 'Validation failed.', $e->errors());
        } catch (Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while creating the Categorie.', null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Categorie = Categorie::findOrFail($id);
            return $this->apiResponse->success(
                null,
                'Categorie retrieved successfully.',
                $Categorie,
                null
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(404, 'Categorie not found.', null);
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
            // Log data request
            Log::info('Full Request Data:', ['body' => $request->all(), 'headers' => $request->headers->all()]);

            // Cek apakah request sudah memakai _method=PUT atau _method=PATCH
            if ($request->isMethod('put') || $request->isMethod('patch') || $request->input('_method') === 'PUT') {
                // Cari kategori berdasarkan ID
                $categorie = Categorie::findOrFail($id);

                // Validasi input
                $validatedData = $request->validate([
                    'name'   => 'required|string',
                    // 'image' => 'nullable|file',
                    'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'status' => 'nullable|string',
                ]);

                // Cek apakah ada file gambar baru yang diupload
                if ($request->hasFile('image')) {
                    Log::info('Image file detected.');

                    // Hapus gambar lama jika ada
                    if ($categorie->path_image) {
                        Storage::disk('public')->delete($categorie->path_image);
                    }

                    // Simpan gambar baru
                    $imagePath = $request->file('image')->store('categories', 'public');
                    $validatedData['path_image'] = $imagePath;
                }

                // Update kategori dengan data baru
                $categorie->update([
                    'name'       => $validatedData['name'] ?? $categorie->name,
                    // 'path_image' => $request->file('image') ? file_get_contents($request->file('image')) : $Categorie->image,
                    'path_image' => $validatedData['path_image'] ?? $categorie->path_image,
                    'status'     => $validatedData['status'] ?? $categorie->status,
                ]);

                return $this->apiResponse->success(
                    null,
                    'Categorie updated successfully.',
                    $categorie,
                    null
                );
            } else {
                return $this->apiResponse->error(405, 'Method Not Allowed', null);
            }
        } catch (ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));

            return $this->apiResponse->error(422, 'Validation failed.', $e->errors());
        } catch (Exception $e) {
            Log::error('Error in update: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while updating the Categorie.', null);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $modeDelete = "PERMANENT";
            $Categorie = Categorie::findOrFail($id);
        
            if ($modeDelete == "TEMPOARY") {
                // Hapus gambar dari storage jika ada
                if ($Categorie->path_image) {
                    $imagePath = storage_path("app/public/{$Categorie->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk dari database
                $Categorie->delete();
            
                return $this->apiResponse->success(
                    204,
                    'Categorie temporarily deleted successfully.',
                    null,
                    null
                );
            } else {
                // Hapus gambar dari storage jika ada
                if ($Categorie->path_image) {
                    $imagePath = storage_path("app/public/{$Categorie->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk secara permanen dari database
                $Categorie->forceDelete();
            
                return $this->apiResponse->success(
                    204,
                    'Categorie permanently deleted successfully.',
                    null,
                    null
                );
            }
        } catch (Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while deleting the Categorie.', null);
        }
    }


}
