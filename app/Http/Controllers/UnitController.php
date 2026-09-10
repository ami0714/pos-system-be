<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function getUnits(){
         $getUnit = DB::select('SELECT 
                            id AS unitId,
                            name AS unitName
                            FROM
                            units');

                            return response()->json([
                                'status' => true,
                                'data' => $getUnit
                            ]);
    }
}
