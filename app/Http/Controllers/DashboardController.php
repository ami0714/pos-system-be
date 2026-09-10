<?php

namespace App\Http\Controllers;
use App\Http\Requests\DashboardIndexRequest;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    protected DashboardService $DashboardService;

    public function __construct(DashboardService $DashboardService)
    {
        $this->DashboardService = $DashboardService;
    }
     
    public function getDashboard(DashboardIndexRequest $request)
    {

     $filter = $request->query('filter');
     $startDate = $request->query('startDate', null) ;
     $endDate = $request->query('endDate',null);

     $kpiData = $this->DashboardService->getKpi($filter,  $startDate , $endDate);
     $bestSelling = $this->DashboardService->getBestSelling();
     $lowStock = $this->DashboardService->getLowStock();
     $chartData = $this->DashboardService->getChart($filter,  $startDate , $endDate);

     return response()->json([
        'status' => true,
        'kpiData' => $kpiData,
        'bestSelling' => $bestSelling,
        'lowStock' => $lowStock,
        'chart' => $chartData
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
