<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected ProductService  $ProductService;

public function __construct(ProductService $ProductService)
{
 $this->ProductService = $ProductService;   
}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $categoryId)
    {
     
       $stockStatus = $request->query('stock');
       $parseCategoryId = (int) $categoryId;

        $responseProduct = $this->ProductService->getProduct($parseCategoryId,$stockStatus);

        if (!empty($responseProduct)) {
            return response()->json($responseProduct);
        }else {
 return response()->json([
            'status' => false,
            'message' => 'gagal dapatkan data produk'
            
        ]);
        }

       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getByBarcode(Request $request)
    {
      $barcode = $request->query('barcode');
      $parseBarcode =(int) $barcode;

        $responseProduct = $this->ProductService->getProductByBarcode($barcode);

        if (!empty($responseProduct)) {
            return response()->json([
                'status' => true,
                'data' => $responseProduct
            ]);
        }else {
 return response()->json([
            'status' => false,
            'message' => 'gagal dapatkan data produk'
            
        ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function get(Request $request)
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
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
