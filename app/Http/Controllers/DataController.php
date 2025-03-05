<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{
    public function getData(): JsonResponse
    {
        return response()->json(['message' => 'Data retrieved successfully']);
    }

    public function postData(Request $request): JsonResponse
    {
        $name = $request->input('name');
        return response()->json(['message' => "Data posted successfully, Name: $name"]);
    }

    public function putData(Request $request, $id): JsonResponse
    {
        $name = $request->input('name');
        return response()->json(['message' => "Data with ID $id updated successfully, Name: $name"]);
    }

    public function deleteData($id): JsonResponse
    {
        return response()->json(['message' => "Data with ID $id deleted successfully"]);
    }
}
