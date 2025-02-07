<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\District;
use App\Models\KolManagement;
use App\Models\KolShipment;
use App\Models\Province;
use App\Models\RawTiktokAccount;
use App\Models\Regency;
use App\Models\ShippingProvider;
use App\Models\TiktokInvoice;
use App\Models\TiktokProgressPost;
use App\Models\User;
use App\Models\Village;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class KolManagementController extends Controller
{
    public function index()
    {
        $rawTikTokAccounts = RawTiktokAccount::all();
        $picUsers = User::where('role', 'kol_pic')
            ->get();
        $kolManagements = KolManagement::with(['rawTiktokAccount', 'kolShipment', 'tiktokInvoice', 'assignCategory'])
            ->paginate(10);

        $banks = Bank::all();
        $shippingProviders = ShippingProvider::where('status', 1)
            ->get();

        $warehouses = Warehouse::get();
        $provinces = Province::all();
        return view('kol.management.index', compact('kolManagements', 'banks', 'shippingProviders', 'warehouses', 'provinces', 'rawTikTokAccounts', 'picUsers'));
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $startDate = $request->input('start_date');
        if (!$startDate) {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
        }
        $endDate = $request->input('end_date');
        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        }
        $kols = KolManagement::when($search, function ($query) use ($search) {
            $query->where('nickname', 'LIKE', "%$search%")
                ->orWhere('unique_id', 'LIKE', "%$search%");
        })
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with('rawTiktokAccount', 'assignCategory', 'kolShipment', 'tiktokInvoice')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($kols);
    }


    public function edit($a)
    {
        $kols = KolManagement::where('id', $a)
            ->with('rawTiktokAccount', 'assignCategory')
            ->first();

        return response()->json($kols);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'raw_tiktok_account_id' => 'required',
                'pic_id' => 'required|string|max:255',
                'platform' => 'required|string',
                'type_payment' => 'required|string',
                'ratecard_kol' => 'required|numeric',
                'ratecard_deal' => 'required|numeric',
                'deal_date' => 'required|date',
                'deal_post' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $store = new KolManagement();
            $store->kol_trx_no = "KOL-TIK" . time();
            $store->raw_tiktok_account_id = $request->input('raw_tiktok_account_id');
            $store->pic_id = $request->input('pic_id');
            $store->platform = $request->input('platform');
            $store->ratecard_kol = $request->input('ratecard_kol');
            $store->ratecard_deal = $request->input('ratecard_deal');
            $store->target_views = $request->input('ratecard_deal') / 10;
            $store->status = 'pending';
            $store->deal_date = $request->input('deal_date');
            $store->deal_post = $request->input('deal_post');
            $store->type_payment = $request->input('type_payment');
            $store->notes = $request->input('notes');
            $store->save();
            $store->fresh();

            if($request->type_payment == "before") {
                $targer_views = $request->input('ratecard_deal') / 10;
                if ($request->deal_post >= 0) {
                    for ($i = 0; $i < $request->deal_post; $i++) {
                        TiktokProgressPost::create([
                            'raw_tiktok_account_id' => $request->input('raw_tiktok_account_id'),
                            'kol_management_id' => $store->id,
                            'title' => $request->title,
                            'link_post' => null,
                            'deadline' => null,
                            'target_views' => round($targer_views / $request->deal_post),
                            'date_post' => null,
                            'brief' => null,
                            'views' => 0,
                            'likes' => 0,
                            'shares' => 0,
                            'comments' => 0,
                            'saves' => 0,
                        ]);
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approve($id)
    {
        $kolManagement = KolManagement::where('id', $id)
            ->where('status', 'pending')
            ->first();

        if ($kolManagement) {
            $kolManagement->status = 'approved';
            $kolManagement->save();

            if($kolManagement->type_payment == "after") {
                if ($kolManagement->deal_post >= 0) {
                    for ($i = 0; $i < $kolManagement->deal_post; $i++) {
                        TiktokProgressPost::create([
                            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
                            'kol_management_id' => $kolManagement->id,
                            'title' => null,
                            'link_post' => null,
                            'deadline' => null,
                            'target_views' => round($kolManagement->targer_views / $kolManagement->deal_post),
                            'date_post' => null,
                            'brief' => null,
                            'views' => 0,
                            'likes' => 0,
                            'shares' => 0,
                            'comments' => 0,
                            'saves' => 0,
                        ]);
                    }
                }
            }


            return redirect()->back()->with('success', 'KOL berhasil di-approve.');
        } else {
            return redirect()->back()->with('error', 'Gagal approve KOL. Status tidak pending.');
        }
    }

    public function reject($id)
    {
        $kolManagement = KolManagement::where('id', $id)
            ->where('status', 'pending')
            ->first();

        if ($kolManagement) {
            $kolManagement->status = 'rejected';
            $kolManagement->save();

            return redirect()->back()->with('success', 'KOL berhasil di-reject.');
        } else {
            return redirect()->back()->with('error', 'Gagal reject KOL. Status tidak pending.');
        }
    }

    public function storeTiktokInvoice(Request $request)
    {
        $validatedData = $request->validate([
            'kol_id' => 'required|exists:kol_management,id',
            'bank_id' => 'required|exists:banks,id',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
        ]);

        $kolManagement = KolManagement::find($validatedData['kol_id']);
        if (!$kolManagement) {
            return redirect()->back()->withErrors('KOL management tidak ditemukan.');
        }

        $existingInvoice = TiktokInvoice::where('kol_management_id', $validatedData['kol_id'])->first();
        if ($existingInvoice) {
            return redirect()->back()->withErrors('Invoice untuk KOL ini sudah ada.');
        }

        TiktokInvoice::create([
            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
            'kol_management_id' => $validatedData['kol_id'],
            'bank_id' => $validatedData['bank_id'],
            'account_name' => $validatedData['account_name'],
            'account_number' => $validatedData['account_number'],
        ]);

        return redirect()->back()->with('success', 'Invoice berhasil dibuat.');
    }

    public function storeKolShipment(Request $request)
    {
        $validated = $request->validate([
            'kol_id' => 'required|integer|exists:kol_management,id',
            'shipping_provider_id' => 'required|integer|exists:shipping_providers,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'receiver_name' => 'required|string',
            'province_id' => 'required|integer|exists:provinces,id',
            'regency_id' => 'required|integer|exists:regencies,id',
            'district_id' => 'required|integer|exists:districts,id',
            'village_id' => 'required|integer|exists:villages,id',
            'destination_address' => 'required|string',
            'shipment_date' => 'required|date',
            'shipping_cost' => 'required|numeric',
            'status' => 'required|in:pending,shipped,delivered,returned',
            'notes' => 'nullable|string',
        ]);

        $existingShipment = KolShipment::where('kol_management_id', $request->kol_id)->first();
        if ($existingShipment) {
            return redirect()->back()->with('error', 'Pengiriman untuk KOL ini sudah ada.');
        }

        $kolManagement = KolManagement::find($request->kol_id);
        if (!$kolManagement) {
            return redirect()->back()->with('error', 'KOL management tidak ditemukan.');
        }

        $shippingProvider = ShippingProvider::find($request->shipping_provider_id);
        if (!$shippingProvider) {
            return redirect()->back()->with('error', 'Penyedia jasa pengiriman tidak ditemukan.');
        }

        $warehouse = Warehouse::find($request->warehouse_id);
        if (!$warehouse) {
            return redirect()->back()->with('error', 'Gudang tidak ditemukan.');
        }

        $province = Province::find($request->province_id);
        $regency = Regency::find($request->regency_id);
        $district = District::find($request->district_id);
        $village = Village::find($request->village_id);

        if (!$province || !$regency || !$district || !$village) {
            return redirect()->back()->with('error', 'Lokasi pengiriman tidak valid.');
        }

        $shipment = KolShipment::create([
            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
            'kol_management_id' => $request->kol_id,
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
            'notes' => $request->notes,
        ]);

        TiktokInvoice::create([
            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
            'kol_management_id' => $request->kol_id,
            'bank_id' => 0,
            'account_name' => "-",
            'account_number' => 0,
            'amount' => $request->shipping_cost,
            'type' => 'shipping_cost'
        ]);

        return redirect()->back()->with('success', 'Pengajuan pengirman barang berhasil dibuat untuk KOL ' . $kolManagement->kol_trx_no);
    }

    // public function index()
    // {
    //     $rawTikTokAccounts = RawTiktokAccount::all();
    //     $picUsers = User::where('role', 'kol_pic')
    //         ->get();
    //     $banks = Bank::all();

    //     $shippingProviders = ShippingProvider::where('status', 1)
    //     ->get();

    //     $warehouses = Warehouse::get();
    //     $provinces = Province::all();
    //     return view('kol.management.index', compact('rawTikTokAccounts', 'picUsers', 'banks', 'shippingProviders', 'warehouses', 'provinces'));
    // }

    // public function approve(Request $request)
    // {
    //     $a = $request->id;
    //     $kolManagement = KolManagement::where('id', $a)
    //         ->where('status', 'pending')
    //         ->first();
    //     if ($kolManagement) {
    //         $kolManagement->status = 'approved';
    //         $kolManagement->save();

    //         return response()
    //             ->json([
    //                 'status' => 'success',
    //                 'code' => 200,
    //                 'message' => 'success approve data'
    //             ], 200);
    //     } else {
    //         return response()
    //             ->json([
    //                 'status' => 'success',
    //                 'code' => 400,
    //                 'message' => 'failed approve data'
    //             ], 400);
    //     }
    // }

    // public function reject(Request $request)
    // {
    //     $a = $request->id;
    //     $kolManagement = KolManagement::where('id', $a)
    //         ->where('status', 'pending')
    //         ->first();
    //     if ($kolManagement) {
    //         $kolManagement->status = 'rejected';
    //         $kolManagement->save();

    //         return response()
    //             ->json([
    //                 'status' => 'success',
    //                 'code' => 200,
    //                 'message' => 'success rejected data'
    //             ], 200);
    //     } else {
    //         return response()
    //             ->json([
    //                 'status' => 'success',
    //                 'code' => 400,
    //                 'message' => 'failed rejected data'
    //             ], 400);
    //     }
    // }


    // public function storeTiktokInvoice(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'kol_id' => 'required|exists:kol_management,id',
    //         'bank_id' => 'required|exists:banks,id',
    //         'account_name' => 'required|string|max:255',
    //         'account_number' => 'required|string|max:255',
    //     ]);

    //     $kolManagement = KolManagement::find($validatedData['kol_id']);
    //     if (!$kolManagement) {
    //         return redirect()->back()->withErrors('KOL management tidak ditemukan.');
    //     }

    //     $existingInvoice = TiktokInvoice::where('kol_management_id', $validatedData['kol_id'])->first();
    //     if ($existingInvoice) {
    //         return redirect()->back()->withErrors('Invoice untuk KOL ini sudah ada.');
    //     }

    //     TiktokInvoice::create([
    //         'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
    //         'kol_management_id' => $validatedData['kol_id'],
    //         'bank_id' => $validatedData['bank_id'],
    //         'account_name' => $validatedData['account_name'],
    //         'account_number' => $validatedData['account_number'],
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'code' => '200',
    //         'message' => 'Success store invoice'
    //     ]);
    // }


    // public function storeKolShipment(Request $request)
    // {
    //     $validated = $request->validate([
    //         'kol_id' => 'required|integer|exists:kol_management,id',
    //         'shipping_provider_id' => 'required|integer|exists:shipping_providers,id',
    //         'warehouse_id' => 'required|integer|exists:warehouses,id',
    //         'receiver_name' => 'required|string',
    //         'province_id' => 'required|integer|exists:provinces,id',
    //         'regency_id' => 'required|integer|exists:regencies,id',
    //         'district_id' => 'required|integer|exists:districts,id',
    //         'village_id' => 'required|integer|exists:villages,id',
    //         'destination_address' => 'required|string',
    //         'shipment_date' => 'required|date',
    //         'shipping_cost' => 'required|numeric',
    //         'status' => 'required|in:pending,shipped,delivered,returned',
    //         'notes' => 'nullable|string',
    //     ]);

    //     $existingShipment = KolShipment::where('kol_management_id', $request->kol_id)->first();
    //     if ($existingShipment) {
    //         return response()->json(['error' => 'Pengiriman untuk KOL ini sudah ada.'], 400);
    //     }

    //     $kolManagement = KolManagement::find($request->kol_id);
    //     if (!$kolManagement) {
    //         return response()->json(['error' => 'KOL management tidak ditemukan.'], 404);
    //     }

    //     $shippingProvider = ShippingProvider::find($request->shipping_provider_id);
    //     if (!$shippingProvider) {
    //         return response()->json(['error' => 'Penyedia jasa pengiriman tidak ditemukan.'], 404);
    //     }

    //     $warehouse = Warehouse::find($request->warehouse_id);
    //     if (!$warehouse) {
    //         return response()->json(['error' => 'Gudang tidak ditemukan.'], 404);
    //     }

    //     $province = Province::find($request->province_id);
    //     $regency = Regency::find($request->regency_id);
    //     $district = District::find($request->district_id);
    //     $village = Village::find($request->village_id);

    //     if (!$province || !$regency || !$district || !$village) {
    //         return response()->json(['error' => 'Lokasi pengiriman tidak valid.'], 400);
    //     }

    //     $shipment = KolShipment::create([
    //         'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
    //         'kol_management_id' => $request->kol_id,
    //         'shipping_provider_id' => $request->shipping_provider_id,
    //         'warehouse_id' => $request->warehouse_id,
    //         'shipment_number' => $request->shipment_number,
    //         'receiver_name' => $request->receiver_name,
    //         'province_id' => $request->province_id,
    //         'regency_id' => $request->regency_id,
    //         'district_id' => $request->district_id,
    //         'village_id' => $request->village_id,
    //         'destination_address' => $request->destination_address,
    //         'shipment_date' => $request->shipment_date,
    //         'shipping_cost' => $request->shipping_cost,
    //         'status' => $request->status,
    //         'notes' => $request->notes,
    //     ]);


    //     TiktokInvoice::create([
    //         'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
    //         'kol_management_id' => $request->kol_id,
    //         'bank_id' => 0,
    //         'account_name' => "-",
    //         'account_number' => 0,
    //         'amount' => $request->shipping_cost,
    //     ]);

    //     return redirect()->back()->with('success', 'Pengajuan pengirman barang berhasil dibuat untuk KOL '. $kolManagement->kol_trx_no);
    //     // return response()->json(['message' => 'Pengiriman KOL berhasil disimpan.', 'data' => $shipment], 201);
    // }

}
