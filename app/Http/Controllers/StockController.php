<?php

namespace App\Http\Controllers;

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
    public function getProductLog(Request $request)
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
