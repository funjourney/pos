<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiResponseController extends Controller
{
    public function success($code, $message, $data, $paginationData = null)
    {
        return response()->json([
            'code' => $code ?? Response::HTTP_OK,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'total_pages' => $paginationData['total_pages'] ?? null,
            'total_items' => $paginationData['total_items'] ?? null,
        ], Response::HTTP_OK);
    }

    public function error($code, $message, $data)
    {
        return response()->json([
            'code' => $code,
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
