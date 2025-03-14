<?php

namespace App\Http\Controllers;

// use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Helpers\PaginationHelper;
use App\Helpers\DataProcessing;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Validation\ValidationException;

class ShoppingCartController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponseController $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = [
            [
                "id" => "460b123e-e0b4-4681-b67d-76c546ea3b66",
                "name" => "Makanan",
                "img" => "https://img.freepik.com/premium-photo/traditional-japanese-meal-with-fried-chicken-pork-cutlets-soup_1007521-47245.jpg",
                "listProducts" => [
                    ["id" => "cde2fc63-504a-40d8-a4f3-6f72bb26f94a", "name" => "Burger", "price" => 25000, "img" => "https://img.freepik.com/free-photo/burger_1339-1550.jpg"],
                    ["id" => "1771400f-2a5f-4352-b845-da20424ad2e1", "name" => "Pizza", "price" => 18000, "img" => "https://img.freepik.com/free-photo/hawaiian-pizza_1203-2455.jpg"],
                    ["id" => "3393d6f5-561d-4be3-a436-c743c6587017", "name" => "Fried Rice", "price" => 18000, "img" => "https://img.freepik.com/free-photo/stir-fried-chili-paste-chicken-with-rice-fried-eggs-white-plate-wooden-table_1150-28443.jpg"],
                    ["id" => "3bcb90c4-1955-4093-8bc6-7ca53c3b943e", "name" => "Fried Chicken", "price" => 18000, "img" => "https://img.freepik.com/free-photo/close-up-fried-chicken-drumsticks_23-2148682835.jpg"]
                ]
            ],
            [
                "id" => "7a0c2878-35de-4f5a-97f9-5de57c19fff6",
                "name" => "Minuman",
                "img" => "https://img.freepik.com/premium-photo/cup-hot-tea-drink-tea_87720-32695.jpg",
                "listProducts" => [
                    ["id" => "e2415848-7f8f-478b-9828-2d2be6ae2a3b", "name" => "Soda", "price" => 15000, "img" => "https://img.freepik.com/free-photo/tasty-bubble-tea-drinks-arrangement_23-2149870687.jpg"],
                    ["id" => "fbe287e4-8813-4d6b-b1f2-307e25b8476c", "name" => "Milk", "price" => 35000, "img" => "https://img.freepik.com/free-photo/glass-with-milk-chocolate_23-2148937237.jpg"],
                    ["id" => "55e0b90f-3648-436c-9efb-d5e7a24cf5d9", "name" => "Orange Juice", "price" => 15000, "img" => "https://img.freepik.com/premium-photo/glass-orange-juice_106857-98.jpg"],
                    ["id" => "c75f6b06-9573-4e1b-a7dd-b70d6ccf3bf9", "name" => "Manggo Juice", "price" => 15000, "img" => "https://img.freepik.com/free-photo/mango-shake-fresh-tropical-fruit-smoothies_501050-963.jpg"]
                ]
            ]
        ];

        return view('dashboard', compact('categories'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
