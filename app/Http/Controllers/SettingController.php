<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingUpdateRequest;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json([]);
    }

    public function update(SettingUpdateRequest $request)
    {
        return response()->json([]);
    }
}
