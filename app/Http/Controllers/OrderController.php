<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Helpers\PaginationHelper;
use App\Helpers\DataProcessing;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
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

            // Validasi apakah kolom ada di dalam tabel Orders
            if (!Schema::hasColumn('Orders', $sortColumn)) {
                return $this->apiResponse->error(400, 'Invalid sort column.',null);
            }

            $query = Order::query()->orderBy($sortColumn, $sortOrder);
            $result = PaginationHelper::createPaginateFromEloquent($query, (int) $limit, (int) $currentPage);

            $dataArrayOrders = DataProcessing::ConvertStructToMap(
                $result['data'], 
                ['createdAt', 'updatedAt', 'deletedAt'] // Hapus kunci ini
            );

            return $this->apiResponse->success(
                null,
                'Orders retrieved successfully.',
                $dataArrayOrders,
                $result['pagination']
            );
        } catch (Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while retrieving Orders.',null);
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
                $imagePath = $request->file('image')->store('Orders', 'public'); // Simpan di storage/app/public/Orders
            }

            $Order = Order::create([
                // 'id' => DB::raw('(UUID())'),
                'categorie_id' => $request->categorieId,
                'name' => $request->name,
                'price' => $request->price,
                'path_image' => $imagePath,
                'status' => $request->status,
            ]);

            return $this->apiResponse->success(
                201,
                'Order created successfully.',
                $Order,
                null
            );
        } catch (ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
    
            return $this->apiResponse->error(422, 'Validation failed.', $e->errors());
        } catch (Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while creating the Order.',null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Order = Order::findOrFail($id);
            return $this->apiResponse->success(
                null,
                'Order retrieved successfully.',
                $Order,
                null
            );
        } catch (Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return $this->apiResponse->error(404, 'Order not found.',null);
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
                $Order = Order::findOrFail($id);

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
                    if ($Order->path_image) {
                        Storage::disk('public')->delete($Order->path_image);
                    }

                    // Simpan gambar baru
                    $imagePath = $request->file('image')->store('Orders', 'public');
                    $validatedData['path_image'] = $imagePath;
                }

                // Update produk dengan data baru
                $Order->update([
                    'categorie_id' => $validatedData['categorieId'] ?? $Order->categorie_id,
                    'name'         => $validatedData['name'] ?? $Order->name,
                    'price'        => $validatedData['price'] ?? $Order->price,
                    'path_image'   => $validatedData['path_image'] ?? $Order->path_image,
                    'status'       => $validatedData['status'] ?? $Order->status,
                ]);

                return $this->apiResponse->success(
                    null,
                    'Order updated successfully.',
                    $Order,
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
            return $this->apiResponse->error(500, 'An error occurred while updating the Order.',null);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $modeDelete = "PERMANENT";
            $Order = Order::findOrFail($id);
        
            if ($modeDelete == "TEMPOARY") {
                // Hapus gambar dari storage jika ada
                if ($Order->path_image) {
                    $imagePath = storage_path("app/public/{$Order->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk dari database
                $Order->delete();
            
                return $this->apiResponse->success(
                    204,
                    'Order temporarily deleted successfully.',
                    null,
                    null
                );
            } else {
                // Hapus gambar dari storage jika ada
                if ($Order->path_image) {
                    $imagePath = storage_path("app/public/{$Order->path_image}");
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            
                // Hapus produk secara permanen dari database
                $Order->forceDelete();
            
                return $this->apiResponse->success(
                    204,
                    'Order permanently deleted successfully.',
                    null,
                    null
                );
            }
        } catch (Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return $this->apiResponse->error(500, 'An error occurred while deleting the Order.',null);
        }
    }


}
