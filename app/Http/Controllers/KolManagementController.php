<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\KolManagement;
use App\Models\Province;
use App\Models\RawTiktokAccount;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
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
        return view('kol.management.index', compact('rawTikTokAccounts', 'picUsers'));
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        
        $startDate = $request->input('start_date');
        if(!$startDate) {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
        }
        $endDate = $request->input('end_date');
        if(!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        }
        $kols = KolManagement::when($search, function ($query) use ($search) {
                $query->where('nickname', 'LIKE', "%$search%")
                    ->orWhere('unique_id', 'LIKE', "%$search%");
            })
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with('rawTiktokAccount', 'assignCategory')
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
            $store->target_views =  $request->input('ratecard_deal') / 10;
            $store->status = 'pending';
            $store->deal_date = $request->input('deal_date');
            $store->deal_post = $request->input('deal_post');
            $store->notes = $request->input('notes');
            $store->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approve(Request $request) 
    {
        $a = $request->id;
        $kolManagement = KolManagement::where('id', $a)
            ->where('status', 'pending')
            ->first();
        if($kolManagement) {
            $kolManagement->status = 'approved';
            $kolManagement->save();

            return response()
                ->json([
                    'status' => 'success',
                    'code' => 200, 
                    'message' => 'success approve data'
                ], 200);
        }else{
            return response()
                ->json([
                    'status' => 'success',
                    'code' => 400, 
                    'message' => 'failed approve data'
                ], 400);
        }
    }
    
    public function reject(Request $request) 
    {
        $a = $request->id;
        $kolManagement = KolManagement::where('id', $a)
            ->where('status', 'pending')
            ->first();
        if($kolManagement) {
            $kolManagement->status = 'rejected';
            $kolManagement->save();

            return response()
                ->json([
                    'status' => 'success',
                    'code' => 200, 
                    'message' => 'success rejected data'
                ], 200);
        }else{
            return response()
                ->json([
                    'status' => 'success',
                    'code' => 400, 
                    'message' => 'failed rejected data'
                ], 400);
        }
    }
}
