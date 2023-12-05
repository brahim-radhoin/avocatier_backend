<?php

namespace App\Http\Controllers;

use App\Models\Avo;
use Illuminate\Http\Request;

class AvoController extends Controller
{
    public function index()
    {
        $avos = Avo::all();
        return response()->json($avos);
    }


    public function store(Request $request)
    {
        $avo = Avo::create($request->all());
        return response()->json($avo, 201);
    }

    public function show(Avo $avo)
    {
        return response()->json($avo);
    }


    public function update(Request $request, Avo $avo)
    {
        $avo->update($request->all());
        return response()->json($avo, 200);
    }

    public function destroy(Avo $avo)
    {
        $avo->delete();
        return response()->json(null, 204);
    }
}