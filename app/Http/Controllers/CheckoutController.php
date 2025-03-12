<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Helpers\PaginationHelper;
use App\Helpers\DataProcessing;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
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
            $sortCheckout = strtolower($request->query('sortCheckout', 'desc')) === 'asc' ? 'asc' : 'desc'; // Default desc

            // Validasi apakah kolom ada di dalam tabel Checkouts
            if (!Schema::hasColumn('Checkouts', $sortColumn)) {
                return $this->apiResponse->error(400, 'Invalid sort column.',null);
            }

            $query = Checkout::query()->CheckoutBy($sortColumn, $sortCheckout);
            $result = PaginationHelper::createPaginateFromEloquent($query, (int) $limit, (int) $currentPage);

            $dataArrayCheckouts = DataProcessing::ConvertStructToMap(
                $result['data'], 
                ['createdAt', 'updatedAt', 'deletedAt'] // Hapus kunci ini
            );

            return $this->apiResponse->success(
                null,
                'Checkouts retrieved successfully.',
                $dataArrayCheckouts,
                $result['pagination']
            );
        } catch (Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while retrieving Checkouts.',null);
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
                'categorieId' => 'required|string',
                'name' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable|string',
            ]);            

            $imagePath = null;
            if ($request->hasFile('image')) {
                Log::error('Image file detected.');
                $imagePath = $request->file('image')->store('Checkouts', 'public'); // Simpan di storage/app/public/Checkouts
            }

            $Checkout = Checkout::create([
                // 'id' => DB::raw('(UUID())'),
                'categorie_id' => $request->categorieId,
                'name' => $request->name,
                'price' => $request->price,
                'path_image' => $imagePath,
                'status' => $request->status,
            ]);

            return $this->apiResponse->success(
                201,
                'Checkout created successfully.',
                $Checkout,
                null
            );
        } catch (ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
    
            return $this->apiResponse->error(422, 'Validation failed.', $e->errors());
        } catch (Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while creating the Checkout.',null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Checkout = Checkout::findOrFail($id);
            return $this->apiResponse->success(
                null,
                'Checkout retrieved successfully.',
                $Checkout,
                null
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(404, 'Checkout not found.',null);
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
    //CATATAN : GUNAKAN METHODE POST
    public function update(Request $request, string $id)
    {
        try {
            // Log data request
            Log::info('Full Request Data:', ['body' => $request->all(), 'headers' => $request->headers->all()]);

            // Cek apakah request sudah memakai _method=PUT atau _method=PATCH
            if ($request->isMethod('put') || $request->isMethod('patch') || $request->input('_method') === 'PUT') {
                // Cari produk berdasarkan ID
                $Checkout = Checkout::findOrFail($id);

                // Validasi input
                $validatedData = $request->validate([
                    'categorieId' => 'required|nullable|string',
                    'name'        => 'required|nullable|string',
                    'price'       => 'required|nullable|numeric|min:0',
                    'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'status'      => 'nullable|string',
                ]);

                // Cek apakah ada file gambar baru yang diupload
                if ($request->hasFile('image')) {
                    Log::info('Image file detected.');

                    // Hapus gambar lama jika ada
                    if ($Checkout->path_image) {
                        Storage::disk('public')->delete($Checkout->path_image);
                    }

                    // Simpan gambar baru
                    $imagePath = $request->file('image')->store('Checkouts', 'public');
                    $validatedData['path_image'] = $imagePath;
                }

                // Update produk dengan data baru
                $Checkout->update([
                    'categorie_id' => $validatedData['categorieId'] ?? $Checkout->categorie_id,
                    'name'         => $validatedData['name'] ?? $Checkout->name,
                    'price'        => $validatedData['price'] ?? $Checkout->price,
                    'path_image'   => $validatedData['path_image'] ?? $Checkout->path_image,
                    'status'       => $validatedData['status'] ?? $Checkout->status,
                ]);

                return $this->apiResponse->success(
                    null,
                    'Checkout updated successfully.',
                    $Checkout,
                    null
                );
            } else {
                return $this->apiResponse->error(405, 'Method Not Allowed',null);
            }
        } catch (ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
    
            return $this->apiResponse->error(422, 'Validation failed.', $e->errors());
        } catch (Exception $e) {
            Log::error('Error in update: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while updating the Checkout.',null);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $modeDelete = "PERMANENT";
            $Checkout = Checkout::findOrFail($id);
        
            if ($modeDelete == "TEMPOARY") {
                // Hapus gambar dari storage jika ada
                if ($Checkout->path_image) {
                    $imagePath = storage_path("app/public/{$Checkout->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk dari database
                $Checkout->delete();
            
                return $this->apiResponse->success(
                    204,
                    'Checkout temporarily deleted successfully.',
                    null,
                    null
                );
            } else {
                // Hapus gambar dari storage jika ada
                if ($Checkout->path_image) {
                    $imagePath = storage_path("app/public/{$Checkout->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk secara permanen dari database
                $Checkout->forceDelete();
            
                return $this->apiResponse->success(
                    204,
                    'Checkout permanently deleted successfully.',
                    null,
                    null
                );
            }
        } catch (Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while deleting the Checkout.',null);
        }
    }


}
