<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleCheckoutRequest;
use Illuminate\Http\Request;
use App\Services\SaleService;

class SaleController extends Controller
{
    protected SaleService $saleService;


    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    //
      /**
     * Display a listing of the resource.
     */
    public function getSaleHistory(){
        $sales = $this->saleService->getSaleHistory();
        return response()->json([
            'status' => true,
            'sales' => $sales
        ]);
    }
    public function getReceipt($id){
        $receipt = $this->saleService->getReceipt($id);
        return response()->json([
            'status' => true,
            'receipt' => $receipt
        ]);
    }   
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkout(SaleCheckoutRequest $request)
    {
            $items = $request->input('items');
            $total = $request->input('total');
            $paymentMethod = $request->input('paymentMethod');
            $paidAmount = $request->input('paidAmount');
            $balance = $request->input('balance');
            $discount = $request->input('discount') ?? 0; // Jika tiada diskaun, tetapkan kepada 0
            $adminId = $request->user()->id;

            $response = $this->saleService->checkout($items, $total, $paymentMethod, $paidAmount, $balance, $discount,$adminId);

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
