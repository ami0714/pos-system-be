<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockAdjustRequest;
use App\Http\Requests\StockLogRequest;
use App\Http\Requests\StockStockInRequest;
use Illuminate\Http\Request;
use App\Services\StockService;
class StockController extends Controller
{
    protected StockService $StockService;


    public function __construct(StockService $StockService)
    {


        $this->StockService = $StockService;
    }
      /**
     * Display a listing of the resource.
     */
    public function getProductLog(StockLogRequest $request)
    {
    $typeFilter = $request->query('type',null);
    $startDate = $request->query('startDate',null);
    $endDate = $request->query('endDate',null);

    $respone = $this->StockService->getLogProduct($typeFilter,$startDate,$endDate);


    return response()->json([
        'status' => true,
        'data' => $respone
    ]);



    }

    /**
     * Store stock movement in.
     */
    public function stockIn(StockStockInRequest $request,$productId)
    {
        
        $quantity = $request->input('stock');
        $type = $request->query('type');
        $sellPrice = $request->input('sell_price', null);
        $buyPrice = $request->input('cost_price', null);
        $note = $request->input('note', null);
        $adminId = $request->user()->id;

        $response = $this->StockService->stockIn($productId, $quantity,$sellPrice,$buyPrice, $type, $note,$adminId);

        return response()->json($response);
    }

    /**
     * Adjust stock.
     */
    public function adjust(StockAdjustRequest $request,$productId)
    {
         $quantity = $request->input('stock');
        $type = $request->query('type');
        $sellPrice = $request->input('sell_price', null);
        $buyPrice = $request->input('cost_price', null);
        $note = $request->input('note', null);
        $adminId = $request->user()->id;

        $response = $this->StockService->stockAdjust($productId, $quantity,$sellPrice,$buyPrice, $type, $note,$adminId);

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
