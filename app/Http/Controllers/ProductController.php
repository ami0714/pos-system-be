<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProductBarcodeRequest;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
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
    public function index(ProductCategoryRequest $request, $categoryId)
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
    public function getByBarcode(ProductBarcodeRequest $request)
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
    public function addProduct(ProductStoreRequest $request)
    {
        $barcode = $request->input('barcode');
        $name = $request->input('name');
        $categoryId =(int) $request->input('category');
        $costPrice = $request->input('cost_price');
        $sellingPrice = $request->input('sell_price');
        $stockQuantity = $request->input('stock', 0);
        $minStock = $request->input('min_stock', 0);
        $unitId = (int) $request->input('unit');

        

        if (!$categoryId) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori tidak ditemukan.'
            ], 422);
        }

       

        if (!$unitId) {
            return response()->json([
                'status' => false,
                'message' => 'Unit tidak ditemukan.'
            ], 422);
        }

        $response = $this->ProductService->addProduct(
            $barcode,
            $name,
            $categoryId,
            $costPrice,
            $sellingPrice,
            $stockQuantity,
            $minStock,
            $unitId
        );

        return response()->json($response);
    }
    public function editProduct(ProductUpdateRequest $request,$productId)
    {
        $barcode = $request->input('barcode');
        $name = $request->input('name');
        $categoryId =(int) $request->input('category');
        $costPrice = $request->input('cost_price');
        $sellingPrice = $request->input('sell_price');
        $stockQuantity = $request->input('stock', 0);
        $minStock = $request->input('min_stock', 0);
        $unitId = (int) $request->input('unit');

        

        if (!$categoryId) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori tidak ditemukan.'
            ], 422);
        }

       

        if (!$unitId) {
            return response()->json([
                'status' => false,
                'message' => 'Unit tidak ditemukan.'
            ], 422);
        }

        $response = $this->ProductService->editProduct(
            $barcode,
            $name,
            $categoryId,
            $costPrice,
            $sellingPrice,
            $stockQuantity,
            $minStock,
            $unitId,
            $productId
        );

        return response()->json($response);
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
    public function update(ProductUpdateRequest $request, string $id)
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
