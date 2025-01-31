<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return view('warehouses.index', compact('provinces'));
    }

    public function load(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $landingpages = Warehouse::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
        ->with(['province', 'regency', 'district', 'village'])
        ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($landingpages);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
             'address_detail' => 'required'
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse berhasil disimpan.');
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return $warehouse;
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
            'address_detail' => 'required'
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($validated);

        return response()
            ->json([
                'status' => 'success', 
                'code' => 200, 
                'message' => 'Success update data'
            ]);
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()
            ->json([
                'status' => 'success', 
                'code' => 200, 
                'message' => 'Success delete warehouse'
            ]);
    }
    public function getRegencies($province_id)
    {
        $regencies = Regency::where('province_id', $province_id)->get();
        return response()->json($regencies);
    }

    public function getDistricts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)->get();
        return response()->json($districts);
    }
    public function getVillages($district_id)
    {
        $villages = Village::where('district_id', $district_id)->get();
        return response()->json($villages);
    }
}
