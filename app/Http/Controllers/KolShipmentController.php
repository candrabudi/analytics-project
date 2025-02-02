<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\KolManagement;
use App\Models\KolShipment;
use App\Models\Province;
use App\Models\RawTiktokAccount;
use App\Models\Regency;
use App\Models\ShippingProvider;
use App\Models\Village;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class KolShipmentController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        $kolManagements = KolManagement::leftJoin('kol_shipments', 'kol_management.id', '=', 'kol_shipments.kol_management_id')
            ->whereNull('kol_shipments.kol_management_id')
            ->select('kol_management.*')
            ->with('rawTiktokAccount')
            ->get();

        $kolManagementsEdit = KolManagement::all();
        $shippingProviders = ShippingProvider::where('status', 1)
            ->get();

        $warehouses = Warehouse::get();
        return view('kol.shipment.index', compact('provinces', 'kolManagements', 'kolManagementsEdit','shippingProviders', 'warehouses'));
    }

    public function load(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $landingpages = KolShipment::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
        ->with(['province', 'regency', 'district', 'village', 'rawTiktokAccount', 'kolManagement'])
        ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($landingpages);
    }

    public function store(Request $request)
    {
        if (!$request->has(['kol_management_id', 'shipping_provider_id', 'warehouse_id', 'shipment_number', 'receiver_name', 'province_id', 'regency_id', 'district_id', 'village_id', 'destination_address', 'shipment_date', 'shipping_cost', 'status'])) {
            return redirect()->back()->withErrors('Semua field wajib diisi.');
        }

        $existingShipment = KolShipment::where('kol_management_id', $request->kol_management_id)->first();
        if ($existingShipment) {
            return redirect()->back()->withErrors('Pengiriman untuk KOL ini sudah ada.');
        }

        if (!in_array($request->status, ['pending', 'shipped', 'delivered', 'returned'])) {
            return redirect()->back()->withErrors('Status pengiriman tidak valid.');
        }

        // if (!RawTiktokAccount::find($request->raw_tiktok_account_id)) {
        //     return redirect()->back()->withErrors('Akun TikTok tidak ditemukan.');
        // }

        if (!KolManagement::find($request->kol_management_id)) {
            return redirect()->back()->withErrors('KOL management tidak ditemukan.');
        }

        if (!ShippingProvider::find($request->shipping_provider_id)) {
            return redirect()->back()->withErrors('Penyedia jasa pengiriman tidak ditemukan.');
        }

        if (!Warehouse::find($request->warehouse_id)) {
            return redirect()->back()->withErrors('Gudang tidak ditemukan.');
        }

        if (!Province::find($request->province_id) || !Regency::find($request->regency_id) || !District::find($request->district_id) || !Village::find($request->village_id)) {
            return redirect()->back()->withErrors('Lokasi pengiriman tidak valid.');
        }

        $kolManagement = KolManagement::where('id', $request->kol_management_id)
            ->first();

        KolShipment::create([
            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
            'kol_management_id' => $request->kol_management_id,
            'shipping_provider_id' => $request->shipping_provider_id,
            'warehouse_id' => $request->warehouse_id,
            'shipment_number' => $request->shipment_number,
            'receiver_name' => $request->receiver_name,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'destination_address' => $request->destination_address,
            'shipment_date' => $request->shipment_date,
            'shipping_cost' => $request->shipping_cost,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->route('kol_shipments.index')->with('success', 'Pengiriman KOL berhasil disimpan.');
    }


    public function edit($id)
    {
        $warehouse = KolShipment::findOrFail($id);
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

        $warehouse = KolShipment::findOrFail($id);
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
        $warehouse = KolShipment::findOrFail($id);
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
